<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Cookie;

class CthmController extends Controller
{
    public function actionIndex(): \yii\web\Response
    {
        $cookies = Yii::$app->request->cookies;
        $new_cookies = Yii::$app->response->cookies;
        if (($cookies->getValue('theme_', 'light')) == "light") {
            $new_cookies->add(new Cookie([
                'name' => 'theme_',
                'value' => 'dark',
            ]));
        } else {
            $new_cookies->add(new Cookie([
                'name' => 'theme_',
                'value' => 'light',
            ]));
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
}