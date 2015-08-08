<?php

class LoginController extends Controller
{

    /**
     * @var string Controller's default action name.
     */
    public $defaultAction = 'login';

    /**
     * This function is action for Displaying the login page.
     * This function is including session.
     * @var $user instance user model.
     * @var $user_post_data array $_POST data.
     */
    public function actionLogin()
    {

        UsersModule::checkLogin($this);
        $user = new User('login');
        $user_post_data = Yii::app()->request->getPost('User', null);
        $user->attributes = $user_post_data;

        if ($user_post_data && $user->validate()) {

            $duration=$user->rememberMe ? 3600*24*30 : 0; // 30 days
            Yii::app()->user->login($user->identity, $duration);
            $this->lastVisit();
            $url = Yii::app()->controller->module->returnUrl;
            $this->redirect(Yii::app()->controller->module->returnUrl);

        }

        $user->password ='';
        $this->render('login', array('model' => $user));

    }

    /**
     * This function is for saving login datetime to database.
     * This function is called from actionLogin.
     * @var $id int login user_id.
     * @var $user instance user model.
     */
    private function lastVisit() {
        $id = Yii::app()->user->id;
        $user = User::model()->notsafe()->findByPk($id);
        $user->lastvisit = new CDbExpression('current_timestamp');
        $user->save();
    }

}