<?php
use yii\helpers\Url;
use yii\helpers\Html;

require_once(Yii::getAlias("@app/helpers/LangUtils.php"));

class Filters {
    // I like this method, because it just works lol
    public static function ClearFilter(&$url, $toRemove) {
        if (!isset($_GET["page"])) {
            $_GET["page"] = "1";
        }
        if (!isset($_GET[$toRemove])) {
            return $url;
        }
        $initialSymbol = "?";
        $url = explode("?", $url)[0];
        if (count($_GET) == 1) {
            return Url::to($url);
        }
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

    public static function DisplayFilters($filterset) {
        $o = "<p>". Yii::t("app", "Filtrid") . ":&nbsp;";
        $hasFilters = false;
        foreach ($filterset as $key => $value) {
            if (isset($_GET[$key])) {
                if ($value["type"] == "string") {
                    $o .= "<a href='".Filters::ClearFilter($preurl, $key)."'>"."<span class='badge bg-secondary mx-2'>".$value["label"].": ".Html::encode($_GET[$key])."</span></a>";
                }
                else if ($value["type"] == "boolean") {
                    $o .= "<a href='".Filters::ClearFilter($preurl, $key)."'>"."<span class='badge bg-secondary mx-2'> ".$value["label"].($_GET[$key]=="1"?"++":"--")."</span></a>";
                }
                $hasFilters = true;
            }
        }
        $o .= "</p>";
        if ($hasFilters) {
            return $o;
        }
        return "";

    }

    public static function DisplayBooleanSelectors($preurl, $filterset) {
        $o = '<div class="dropdown d-inline ms-2">';
        $o.= '<button class="btn btn-secondary dropdown-toggle" type="button" id="displayOnlyButton" data-bs-toggle="dropdown" aria-expanded="false">';
        $o.= Yii::t('app', "Kuva ainult");
        $o.= "</button>";
        $o.= '<ul class="dropdown-menu" aria-labelledby="displayOnlyButton">';
        foreach ($filterset as $key => $value) {
            if ($value["type"] == "boolean") {
                $o.='<li><a class="dropdown-item" href="'.Filters::AddFilter($preurl, $key, "1").'">'. $value["label"] . '</a></li>';
            }
        }
        $o.="</ul>";
        $o.="</div>";
        
        $o.= '<div class="dropdown d-inline ms-2">';
        $o.= '<button class="btn btn-secondary dropdown-toggle ms-1" type="button" id="dontDisplay" data-bs-toggle="dropdown" aria-expanded="false">';
        $o.= Yii::t('app', "Ära kuva");
        $o.= "</button>";
        $o.= '<ul class="dropdown-menu" aria-labelledby="dontDisplay">';
        foreach ($filterset as $key => $value) {
            if ($value["type"] == "boolean") {
                $o.='<li><a class="dropdown-item" href="'.Filters::AddFilter($preurl, $key, "0").'">'. $value["label"] . '</a></li>';
            }
        }
        $o.="</ul>";
        $o.="</div>";
        return $o;
    }
}