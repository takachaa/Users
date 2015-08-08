<?php


class LogoutController extends Controller
{

    /**
     * @var string Controller's default action name.
     */
    public $defaultAction = 'logout';

    /**
    *  This function is action for Logout the current user and redirect to returnLogoutUrl.
    */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->controller->module->returnLogoutUrl);
    }

}