<?php
namespace app\controllers;


use Yii;
use yii\web\Controller;
use yii\web\Cookie;

class LangController extends Controller {
    public function actionIndex() {
        $cookies = Yii::$app->request->cookies;
        $new_cookies = Yii::$app->response->cookies;
        if (($cookies->getValue('lang_', 'et-EE')) == "et-EE") {
            $new_cookies->add(new Cookie([
                'name' => 'lang_',
                'value' => 'en-US',
            ]));
        } else {
            $new_cookies->add(new Cookie([
                'name' => 'lang_',
                'value' => 'et-EE',
            ]));
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
}