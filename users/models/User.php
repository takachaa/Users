<?php

/**
 * User class.
 * User is the data structure for user data.
 * It is used by the various action of various controller
 * such as 'login' action of LoginController.
 */
class User extends CActiveRecord
{

    /**
     * @var string you can use this property when you compare password.
     */
    public $password_repeat;

    /**
     * @var instance UserIdentity.
     */
    public $identity;

    /**
     * @var boolean If it's true , setCookie with duration when 'allowAutoLogin' is true in main config
     */
    public $rememberMe;

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
        return '{{user}}';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(

            'username' => UsersModule::t("Username"),
            'password' => UsersModule::t("Password"),
            'rememberMe'=>UsersModule::t("Remember me next time"),
            'password_repeat' => UsersModule::t("Retype Password"),
            'email' => UsersModule::t("E-mail"),
            'user_id' => UsersModule::t("Id"),
            'activekey' => UsersModule::t("activation key"),
            'create_time' => UsersModule::t("Registration date"),
            'lastvisit' => UsersModule::t("Last visit")

        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('username, password, email', 'required', 'on'=>'insert'),

            array('password_repeat', 'compare', 'compareAttribute'=>'password', 'message' => UsersModule::t("Retype Password is incorrect."), 'on'=>'compare'),
            array('password ,password_repeat', 'required','on'=>'compare'),

            //array('password ,email', 'required', 'on'=>'login'),
            //array('email ,password', 'loginMatch', 'on'=>'login'),

            array('email, password', 'required', 'on'=>'login'),
            array('rememberMe', 'boolean' ,'on'=>'login'),
            array('password', 'authenticate','on'=>'login'),

            array('email', 'required', 'on'=>'recovery'),
            array('email', 'email', 'on'=>'recoevery'),
            array('email', 'emailValid', 'on'=>'recovery'),

            array('username', 'required' ,'on'=>'change_username'),

            array('password, password_repeat', 'required', 'on'=>'change_password'),
            array('password_repeat', 'compare', 'compareAttribute'=>'password', 'message' => UsersModule::t("Retype Password is incorrect."), 'on'=>'change_password'),

            array('email','required', 'on'=>'change_email'),
            array('email', 'email', 'on'=>'change_email'),
            array('email','emailUnique', 'on'=>'change_email'),

        );
    }

    /**
     * Check email and password is correct or not.
     * This is the 'loginMatch' validator as declared in rules().
     * @param $attribute string attribute name.
     **/
    public function loginMatch($attribute){

        $user = User::model()->notsafe()->findByAttributes(array('email' => $this->email, 'password' => $this->password));

        if(!$user){

            $this->addError($attribute, 'email or password is incorrect');

        }else {

            $this->_attributes = $user->getAttributes(false);

        }

    }

    /**
     * Check email in valid or not.
     * This is the 'emailValid' validator as declared in rules().
     * @param $attribute string attribute name.
     **/
    public function emailValid($attribute){

        $user = User::model()->notsafe()->findByAttributes(array('email' => $this->email));

        if(!$user){

            $this->addError($attribute, 'Your email is invalid');

        }else{

            $this->_attributes = $user->getAttributes(false);

        }

    }


    /**
     * Check email is email address has already been taken or not.
     * This is the 'emailUnique' validator as declared in rules().
     * @param $attribute string attribute name.
     **/
    public function emailUnique($attribute){

        $user = User::model()->notsafe()->findByAttributes(array($attribute => $this->email));

        if($user){

            $this->addError($attribute, 'Your email address has already been taken');

        }

    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute,$params)
    {
        if(!$this->hasErrors())  // we only want to authenticate when no input errors
        {
            $identity=new UserIdentity($this->email,$this->password);
            $identity->authenticate();
            switch($identity->errorCode)
            {
                case UserIdentity::ERROR_NONE:
                    $this->identity = $identity;
                    break;
                case UserIdentity::ERROR_EMAIL_INVALID:
                    $this->addError("email",UsersModule::t("Email is incorrect."));
                    break;
                case UserIdentity::ERROR_PASSWORD_INVALID:
                    $this->addError("password",UsersModule::t("Password is incorrect."));
                    break;
            }
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
                'condition'=>'((subdate(now(),interval 1 hour) <=recovery_time ))',
            ),

            'notsafe'=>array(
                'select' => 'user_id, username, password, email, create_time ,lastvisit'
            ),
            'email'=>array(
                'select' => 'user_id, email'
            ),
        );
    }

}