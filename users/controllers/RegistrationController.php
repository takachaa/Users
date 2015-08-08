<?php

class RegistrationController extends Controller
{

    /**
     * @var string Controller's default action name.
     */
    public $defaultAction = 'registration';

    /**
     * This function is action for registration.
     * @var $pre_user_registration instance pre_user_registration model.
     * @var $pre_user_get_data string $_POST data.
     */
    public function actionRegistration()
    {
        $pre_user_registration = new PreUserRegistration('insert');

        $pre_user_get_data = Yii::app()->request->getPost('PreUserRegistration',null);

        if($pre_user_get_data){

            $pre_user_registration->attributes = $pre_user_get_data;
            $pre_user_registration->active_key = UsersModule::encrypting(microtime() . $pre_user_registration->password);
            $pre_user_registration->password = $pre_user_registration->password == '' ? '' : UsersModule::encrypting($pre_user_registration->password);
            $pre_user_registration->password_repeat = $pre_user_registration->password_repeat == '' ? '' : UsersModule::encrypting($pre_user_registration->password_repeat);
            $pre_user_registration->create_time = new CDbExpression('current_timestamp');

            if ($pre_user_registration->save()) {

                $this->registrationMail($pre_user_registration);
                $this->redirect(array("/users/registration/registrationcomplete"));

            }else{

                $pre_user_registration->password = '';
                $pre_user_registration->password_repeat = '';

            }

        }

        return $this->render('registration',array('model'=>$pre_user_registration));

    }


    /**
     * This function is for sending email to user to activate user account.
     * This function is called from actionRegistration.
     * @param $pre_user_registration instance pre_user_registration model.
     * @var $activation_url string activation URL.
     * @var $subject string mail subject.
     * @var $message string mail body sentence.
     */
    private function registrationMail($pre_user_registration){

        $activation_url = $this->createAbsoluteUrl('/users/activation/activation', array("active_key" => $pre_user_registration->active_key));
        $subject = UsersModule::t("You registered from {site_name}", array('{site_name}' => Yii::app()->name));
        $message = UsersModule::t("Please activate your account go to {activation_url}", array('{activation_url}' => $activation_url));
        UsersModule::sendMail($pre_user_registration->email, $subject, $message);
    }

    /**
     * This function is action for rendering view complete about registration complete.
     */
    public function actionRegistrationComplete(){

        $this->render('registrationcomplete');

    }


}