<?php


class MemberController extends Controller{

    /**
     * @var string Controller's default action name.
     */
    public $defaultAction = 'member';

    /**
     * @var instance the currently loaded user model.
     */
    private $_user;

    /**
     * This function is action for rendering views about profile of login user.
     */
    public function actionMember(){

        UsersModule::checkLogin($this);
        $this->render('member',array('model'=>$this->_user,));
    }

    /**
     * This function is action for changing password of login user.
     * @var $user_post_data array $_POST data.
     */
    public function actionChangePassword() {

        //$this->guestRedirect();
        UsersModule::checkLogin($this);
        $user = new User('change_password');
        $user->_pk = $this->_user->getPrimaryKey();

        $user_post_data = Yii::app()->request->getPost('User',null);
        $user->password = Yii::app()->request->getPost('User',null)['password']  == null ? null : UsersModule::encrypting(Yii::app()->request->getPost('User',null)['password']);
        $user->password_repeat = Yii::app()->request->getPost('User',null)['password_repeat'] == null ? null : UsersModule::encrypting(Yii::app()->request->getPost('User',null)['password_repeat']);

        if($user_post_data && $this->changeDone($user)) {

            Yii::app()->user->setFlash('profileMessage', UsersModule::t("New password is saved."));
            $this->redirect(array('/users/member'));

        }

        $user->password = '';
        $user->password_repeat = '';
        $this->render('changepassword',array('model'=>$user));

    }

    /**
     * This function is action for changing username of login user.
     * @var $user_post_data array $_POST data.
     */
     public function actionChangeUsername()
     {
         //$this->guestRedirect();
         UsersModule::checkLogin($this);
         $user = new User('change_username');
         $user->_pk = $this->_user->getPrimaryKey();
         $user_post_data = Yii::app()->request->getPost('User',null)['username'];
         $user->username = $user_post_data;

         if($user_post_data !== null && $this->changeDone($user)) {

             Yii::app()->user->setName($user->username); //cookieファイルorDBに新しい名前をすぐ反映→ログアウトの表示にすぐ反映。
             Yii::app()->user->setFlash('profileMessage', UsersModule::t("New Username is saved."));
             $this->redirect(array('/users/member'));

         }

         $this->render('changeusername',array('model'=>$user));

     }

    /**
     * This function is action for changing email of login user.
     * @var $recovery_key string $_GET data.
     * @var $email string $_GET data.
     * @var $user_post_data array $_POST data.
     */
     public function actionChangeEmail()
     {
         //$this->guestRedirect();
         UsersModule::checkLogin($this);
         $recovery_key = Yii::app()->request->getQuery('recovery_key', null);
         $email = Yii::app()->request->getQuery('email', null);
         //メールからのリカバリキーあるか？　かつ　リカバリーキーは有効か?
         if ($recovery_key && $user = User::model()->email()->expire()->findByAttributes(array('recovery_key' => $recovery_key))) {

             $user->email = $email;

             if($this->changeEmailDone($user)){

                 Yii::app()->user->setFlash('profileMessage', UsersModule::t("New E-mail is saved"));
                 $this->redirect(array('/users/member'));

             }

             Yii::app()->user->setFlash('profileMessage', UsersModule::t("Incorrect activation URL for Changing Email"));
             $this->redirect(array('/users/member/'));

         }else {

             if($recovery_key) {

                 Yii::app()->user->setFlash('profileMessage', UsersModule::t("Incorrect activation URL for Changing Email. You can not do that. Try change password again"));
                 $this->redirect(array('/users/member/'));

             }

             $user = new User('change_email');
             $user_post_data = Yii::app()->request->getPost('User', null);
             $user->attributes = $user_post_data;

             if ($user_post_data && $this->changeEmailBefore($user)) {

                 Yii::app()->user->setFlash('profileMessage', UsersModule::t("Please check your email. An instructions was sent to your email address to activate new email."));
                 $this->redirect(array('/users/member'));

             }

             $this->render('changeemail', array('model' => $user));
         }
     }

    /**
     * This function is for validating attributes and saving new email.
     * This function is called from actionChangeEmail.
     * @param $user instance user model.
     * @return boolean when you fail to validate or save attributes, return false.
     */
     private function changeEmailDone($user){

        $user->setScenario('change_email');

        if($user->validate()) {

            $user->setScenario('update');
            $user->_new = false;

            if($user->save()) {

                return true;
            }

        }

        return false;

     }

    /**
     * This function is for validating attributes and saving recovery_key and recovery_time.
     * This function is called from actionChangeEmail.
     * @param $user instance user model.
     * @var $email string for mail recipient.
     * @return boolean when you fail to validate or save attributes, return false.
     */
     private function changeEmailBefore($user){

         if($user->validate()) {

             $user->_pk = $this->_user->getPrimaryKey();;
             $email = $user->email;
             unset($user->email);
             $user->recovery_key = UsersModule::encrypting(microtime() . $this->_user->password);
             $user->recovery_time = new CDbExpression('current_timestamp');
             $user->setScenario('update');
             $user->_new = false;

             if($user->save()) {

                 $this->recoveryMail($user,$email);
                 return true;

             }

         }

         return false;

     }

    /**
     * This function is for sending email to confirm email.
     * This function is called from changeEmailBefore.
     * @param $user instance user model.
     * @param $email string for mail recipient.
     * @var $activation_url string activation URL.
     * @var $subject string mail subject.
     * @var $message string mail body sentence.
     */
     private function recoveryMail($user,$email){

         $activation_url = 'http://' . $_SERVER['HTTP_HOST'] . $this->createUrl(implode(Yii::app()->controller->module->changeemailUrl), array("recovery_key" => $user->recovery_key, "email" => $email));
         $subject = UsersModule::t("You have requested the email change site {site_name}",
             array(
                 '{site_name}' => Yii::app()->name,
             ));
         $message = UsersModule::t("You have requested the email change site {site_name}. Go to {activation_url}.",
             array(
                 '{site_name}' => Yii::app()->name,
                 '{activation_url}' => $activation_url,
             ));

         UsersModule::sendMail($email, $subject, $message);

     }

    /**
     * This function is for validating attributes and saving new username or new password.
     * This function is called from actionChangeUsername in this controller.
     * @param $user instance user model.
     * @return boolean when you fail to validate or save attributes, return false.
     */
    private function changeDone($user){

        if($user->validate() ) {

            $user->setScenario('update');
            $user->_new = false;

            if($user->save()) {

                return true;

            }

        }

        return false;

     }

    /**
     * Sets a MemberController's private property.
     * @param $user instance user model.
     */
    public function setUser($user){

        $this->_user = $user;

    }

}