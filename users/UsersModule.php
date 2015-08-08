<?php

class UsersModule extends CWebModule
{

    /**
     * @var string
     * @desc hash method (md5,sha1 or algo hash function http://www.php.net/manual/en/function.hash.php)
     */
    public $hash='md5';

    /**
     * @var array　you can use when you invoke redirect method.
     */
    public $registrationUrl = array("/users/registration");
    public $recoveryUrl = array("/users/recovery");
    public $changeemailUrl = array("/users/member/changeemail");
    public $loginUrl = array("/users/login");
    public $logoutUrl = array("/users/logout");
    public $returnUrl = array("/users/member");
    public $returnLogoutUrl = array("/users/login");

    /**
     * @var instance user model.
     */
    static private $_user;

    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

    }


    /**
     * @param $str string
     * @param $params array()
     * @param $dic string
     * @return string
     */
    public static function t($str='',$params=array(),$dic='user') {
        return Yii::t("UsersModule.".$dic, $str, $params);
    }

    public static function encrypting($string="") {
        $hash = Yii::app()->getModule('users')->hash;
        if ($hash=="md5") {
            return md5($string);
        }
        if ($hash=="sha1") {
            return sha1($string);
        }
        else {
            return hash($hash, $string);
        }
    }


    /**
     * Send E-mail method.
     * Please modify this method depends on your situation.
     * @param $activation_url string activation URL.
     * @param $subject string mail subject.
     * @param $message string mail body sentence.
     * @return boolean. when you fail to send email, it returns false.
     */
    public static function sendMail($email,$subject,$message) {

        $adminEmail = Yii::app()->params['adminEmail'];
        $headers = "MIME-Version: 1.0\r\nFrom: $adminEmail\r\nReply-To: $adminEmail\r\nContent-Type: text/html; charset=utf-8";
        $message = wordwrap($message, 70);
        $message = str_replace("\n.", "\n..", $message);
        return mail($email,'=?UTF-8?B?'.base64_encode($subject).'?=',$message,$headers);
    }


    /**
     * Return safe user data.
     * @param user id not required
     * @return user object or false
     */
    public static function user($id=0) {

        if ($id) {

            return User::model()->findbyPk($id);

        }else {

            if(Yii::app()->user->isGuest) {

                return false;

            } else {

                if (!self::$_user) {

                    self::$_user = User::model()->findbyPk(Yii::app()->user->id);

                }

                return self::$_user;

            }
        }
    }


    /**
     * This function for considering user login or not.
     * If user is login, login user instance sets in $controller->user.
     * and if user is not login, redirect login page. But those process is depends on any Controllers.
     * @param $controller instance controller which is calling this method.
     */
    public static function checkLogin($controller)
    {

        if (!Yii::app()->user->isGuest) {
            //Process of login.
            if($controller instanceof LoginController || $controller instanceof RecoveryController) {

                $controller->redirect(Yii::app()->controller->module->returnUrl);

            }

            $controller->user = Yii::app()->controller->module->user();

        } else {

            //Process of not login.
            if($controller instanceof MemberController){

                $controller->redirect(Yii::app()->controller->module->loginUrl);

            }

        }

    }


}
