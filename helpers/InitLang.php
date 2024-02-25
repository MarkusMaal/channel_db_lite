<?php
// initialize multi-language support
$cookies = Yii::$app->request->cookies;
if (!isset($_COOKIE["lang_"]) && isset($_COOKIE["lang"])) {
    $cookies = Yii::$app->response->cookies;
    $cookies->add(new \Yii\web\Cookie([
        "name"=> "lang_",
        "value"=> $_COOKIE["lang"],
    ]));
    Yii::$app->language = $_GET["lang"];
} else if ($cookies->getValue("lang_", "et-EE") != "et-EE") {
    Yii::$app->language = "en-US";
}