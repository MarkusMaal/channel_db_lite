<?php
use yii\helpers\Url;
use yii\helpers\Html;

class Filters {
    // I like this method, because it just works lol
    public static function ClearFilter(&$url, $toRemove) {
        if (!isset($_GET[$toRemove])) {
            return $url;
        }
        $initialSymbol = "?";
        $url = explode("?", $url)[0];
        foreach ($_GET as $key => $value) {
            if ($key != $toRemove) {
                $url .= $initialSymbol . urlencode($key) . "=" . urlencode($value);
                $initialSymbol = "&";
            }
        }
        return Url::to(Html::encode($url));
    }

    public static function AddFilter($preurl, $name, $value) {
        $preurl = Filters::ClearFilter($preurl, $name);
        if (str_contains($preurl, "?")) {
            return Url::to($preurl."&$name=$value");
        } else {
            return Url::to($preurl."?$name=$value");
        }
    }
}