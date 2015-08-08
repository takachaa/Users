<?php

/**
 * PreUserRegistration class.
 * PreUserRegistration is the data structure for registration form data.
 * It is used by the 'registration' action of 'RegistrationController'
 * and 'activation' action of 'ActivationController'
 */
class PreUserRegistration extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @var string you can use this property when you compare password.
     */
    public $password_repeat;

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{pre_user_registration}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('username, password, password_repeat, email', 'required' ,'on' => 'insert'),
            array('username, password, email', 'required' ,'on' => 'update'),

            array('password_repeat', 'compare', 'compareAttribute'=>'password', 'message' => UsersModule::t("Retype Password is incorrect."), 'on'=>'insert'),
            array('email', 'emailUnique' ,'on'=>'insert'),
            array('email', 'email' ,'on'=>'insert')

            );

    }

    /**
     * Check email is email address has already been taken or not.
     * This is the 'emailUnique' validator as declared in rules().
     * @param $attribute string attribute name.
     **/
    public function emailUnique($attribute){

        $user = User::model()->findByAttributes(array($attribute => $this->email));

        if($user){

            $this->addError($attribute, 'Your email address has already been taken');

        }

    }

    /**
     * Returns the declaration of named scopes.
     * A named scope represents a query criteria that can be chained together with
     * other named scopes and applied to a query.
     * @return array.
     **/
    public function scopes()
    {
        return array(

            'expire'=>array(
                'condition'=>'((subdate(now(),interval 1 hour) <=create_time ))',
            ),

            'notsafe'=>array(
                'select' => 'id, username, password, email',

            ),
        );
    }

}