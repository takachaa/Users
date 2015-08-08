<?php
/**
 * Created by PhpStorm.
 * User: takachaa
 * Date: 15/07/21
 * Time: 12:15
 */

class RecoveryController extends Controller
{
    /**
     * @var string Controller's default action name.
     */
    public $defaultAction = 'recovery';

    /**
     * This function is action for Recovering password.
     * @var $recovery_key string $_GET data.
     * @var $user_post_data array $_POST data.
     * @var $user instance user model.
     */
    public function actionRecovery ()
    {
        UsersModule::checkLogin($this);
        $recovery_key = Yii::app()->request->getQuery('recovery_key', null);

        //メールからのリカバリキーがあるか？　かつ　リカバリーキーは有効か?
        if ($recovery_key && $user = User::model()->notsafe()->expire()->findByAttributes(array('recovery_key' => $recovery_key))) {

            $user_post_data = Yii::app()->request->getPost('User', null);
            $user->password = Yii::app()->request->getPost('User', null)['password'] == '' ? '' : UsersModule::encrypting(Yii::app()->request->getPost('User', null)['password']);
            $user->password_repeat = Yii::app()->request->getPost('User', null)['password_repeat'] == '' ? '' : UsersModule::encrypting(Yii::app()->request->getPost('User', null)['password_repeat']);

            if ($user_post_data && $this->recoveryDone($user)) {

                $this->redirect(array('/users/recovery/recoverycomplete'));

            }

            $user->password = '';
            $user->password_repeat = '';
            $this->render('changepassword', array('form' => $user));

        }else {

            $user = new User('recovery');
            $user_post_data = Yii::app()->request->getPost('User', null);
            $user->attributes = $user_post_data;

            //If $_POST is not set,this provides input form.
            if ($user_post_data && $this->recoveryUser($user)) {

                $this->redirect(array('/users/recovery/sendcomplete'));

            }

            $this->render('recovery', array('form' => $user));

        }
    }

    /**
     * This function is  for validating attributes and saving new password.
     * This function is called from actionRecovery.
     * @param $user instance user model.
     * @return boolean when you fail to validate or save attributes, it returns false.
     */
    private function recoveryDone($user){

        $user->setScenario('compare');

        if ($user->validate()) {

            $user->setScenario('update');
            $user->setIsNewRecord(false);

            if ($user->save()) {

                return true;

            }
        }

        return false;

    }

    /**
     * This function is for validating attributes and saving recovery_key and recovery_time.
     * This function is called from actionRecovery.
     * @param $user instance user model
     * @return boolean when you fail to validate or save attributes, it returns false.
     */
    private function recoveryUser($user){

        if ($user->validate()) {

            $user->recovery_key = UsersModule::encrypting(microtime() . $user->password);
            $user->recovery_time = new CDbExpression('current_timestamp');
            $user->setPrimaryKey($user->user_id);
            $user->setScenario('update');
            $user->setIsNewRecord(false);

            if ($user->save()) {

                $this->recoveryMail($user);

                return true;

            }

        }

        return false;

    }

    /**
     * This function is for sending email to recovering password
     * This function is called from recoveryUser.
     * @param $user instance user model
     * @var $activation_url string activation URL.
     * @var $subject string mail subject.
     * @var $message string mail body sentence.
     */
    private function recoveryMail($user){

        $activation_url = 'http://' . $_SERVER['HTTP_HOST'] . $this->createUrl(implode(Yii::app()->controller->module->recoveryUrl), array("recovery_key" => $user->recovery_key, "email" => $user->email));

        $subject = UsersModule::t("You have requested the password recovery site {site_name}",
                                  array(
                                    '{site_name}' => Yii::app()->name,
                                  ));
        $message = UsersModule::t("You have requested the password recovery site {site_name}. To receive a new password, go to {activation_url}.",
                                  array(
                                    '{site_name}' => Yii::app()->name,
                                    '{activation_url}' => $activation_url,
                                  ));

        UsersModule::sendMail($user->email, $subject, $message);
    }

    /**
     * This function is action for rendering view about complete sending email.
     */
    public function actionSendComplete(){

        $this->render("sendcomplete", array('title' => UsersModule::t("Restore") , 'content' => UsersModule::t('Please check your email. An instructions was sent to your email address.')));

    }

    /**
     * This function is action for rendering view about complete saving new password.
     */
    public function actionRecoveryComplete(){

        $this->render("recoverycomplete", array('title' => UsersModule::t("Restore") , 'content' => UsersModule::t('Your Password Changed. Please login')));



    }

}