<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;
    const ERROR_EMAIL_INVALID=3;
    public $email;
    
    public function __construct($email,$password)
    {
        $this->email=$email;
        $this->password=$password;
    }

    /**
     * This function is for autheticating email.
     * @return boolean If you have errors, it return false.
     */
    public function authenticate()
    {

        $user=User::model()->notsafe()->findByAttributes(array('email'=>$this->email));

        if($user===null) {

            if ($this->email) {

                $this->errorCode = self::ERROR_EMAIL_INVALID;
            }

        }else if(UsersModule::encrypting($this->password) !== $user->password) {

            $this->errorCode = self::ERROR_PASSWORD_INVALID;

        }else{

            $this->_id=$user->user_id;
            $this->username = $user->username;
            $this->errorCode=self::ERROR_NONE;
        }

        return !$this->errorCode;
    }

    /**
     * @return integer the ID of the user record
     */
    public function getId()
    {
        return $this->_id;
    }
}
