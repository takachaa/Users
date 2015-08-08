<?php

class ActivationController extends Controller
{
    /**
     * @var string Controller's default action name.
     */
    public $defaultAction = 'activation';

    /**
     * This function is action for activation user account.
     * @var $active_key string $_GET data.
     * @var $pre_user_registration instance pre_user_registration model.
     */
    public function actionActivation () {

        $active_key = Yii::app()->request->getQuery('active_key',null);

        if ($active_key) {

            $pre_user_registration = PreUserRegistration::model()->expire()->notsafe()->findByAttributes(array('active_key'=>$active_key ,'user_id'=>0));

            if ($pre_user_registration && $this->activateUser($pre_user_registration)) {

                $this->redirect(array('/users/activation/activationcomplete'));

            }
        }

        $this->redirect(array('/users/activation/activationfailure'));

    }

    /**
     * This function is action for rendering view about activating complete.
     */
    public function actionActivationComplete(){

        $this->render('complete',array('title'=>UsersModule::t("User Activation"),'content'=>UsersModule::t("Your account is active.")));

    }

    /**
     * This function is action for rendering view about activating failure.
     */
    public function actionActivationFailure(){

        $this->render('failure',array('title'=>UsersModule::t("User Activation"),'content'=>UsersModule::t("Incorrect activation URL.")));

    }

    /**
     * This function is for validating attributes and saving attributes.
     * This function is called from actionActivation.
     * @param $pre_user_registration instance pre_user_registration model.
     * @var $user instance user model.
     * @return boolean when you fail to save attributes, it returns false.
     */
    private function activateUser($pre_user_registration){

        $user = new User('insert');
        $pre_user_registration->setPrimaryKey($pre_user_registration->id);
        unset($pre_user_registration->id);

        $user->_attributes = $pre_user_registration->getAttributes(false);
        $user->create_time = new CDbExpression('current_timestamp');

        try {

            $transaction = Yii::app()->db->beginTransaction();

            if (!$user->save()) {
                throw new Exception("couldn't save user");
            }

            $pre_user_registration->user_id = $user->getPrimaryKey();

            if (!$pre_user_registration->save()) {
                throw new Exception("couldn't save pre_user_registration");
            }

            $transaction->commit();
            return true;

        }catch(Exception $e){

            $transaction->rollback;
            return false;


        }


    }

}