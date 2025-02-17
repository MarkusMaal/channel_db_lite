<?php
// initialize multi-language support
$cookies = Yii::$app->request->cookies;
if ($cookies->getValue("lang_", "et-EE") != "et-EE") {
    Yii::$app->language = "en-US";
}
if ($cookies->getValue("theme_", "light") != "light") {
    Yii::$app->params["theme"] = "dark";
} else {
    Yii::$app->params["theme"] = "light";
}