<?php
use yii\helpers\Html;

// helper functions for choosing correct field depending on the language and content

class LangUtils {
    public static function GetLangValue(&$primary, $mui_et, $mui_en, $lang) {
        if ($lang == "et-EE") {
            return Html::encode((($mui_et!=".")?$mui_et:$primary));
        } else if ($lang == "en-US") {
            return Html::encode((($mui_en!=".")?$mui_en:$primary));
        } else {
            return Html::encode($primary);
        }
    }

    public static function GetMuiTitle($lang, $video) {
        $primary = $video->Video;
        $mui_et = $video->TitleMUI_et;
        $mui_en = $video->TitleMUI_en;
        return LangUtils::GetLangValue($primary, $mui_et, $mui_en, $lang);
    }

    public static function GetMuiDesc($lang, $video) {
        $primary = $video->Kirjeldus;
        $mui_et = $video->KirjeldusMUI_et;
        $mui_en = $video->KirjeldusMUI_en;
        return LangUtils::GetLangValue($primary, $mui_et, $mui_en, $lang);
    }

    public static function GetMuiChannel($lang, $video) {
        $primary = $video->Kanal;
        $mui_et = $video->KanalMUI_et;
        $mui_en = $video->KanalMUI_en;
        return LangUtils::GetLangValue($primary, $mui_et, $mui_en, $lang);
    }
    public static function GetMuiCategory($lang, $video) {
        if ($lang == "et-EE") {
            return $video->Category;
        } else {
            return $video->CategoryMUI_en;
        }
    }
}