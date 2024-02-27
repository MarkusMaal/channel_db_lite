<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;

use app\models\Video;

class VideoController extends Controller
{
    // all videos
    public function actionIndex()
    {
        $query = Video::find();
        $pagination = new Pagination([
            'defaultPageSize' => $_COOKIE["results"]??20,
            'totalCount' => $query->count(),
        ]);

        $videos = $query->orderBy('Kuupäev '.($_GET["ord"]??'DESC'))
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();

        $categories = Video::find()->orderBy('Category')->select('Category,CategoryMUI_en')->distinct()->all();
        $channels = Video::find()->orderBy('Kanal')->select('Kanal,KanalMUI_et,KanalMUI_en')->distinct()->all();
        return $this->render('index', [
            'videos'     => $videos,
            'channels'   => $channels,
            'pagination' => $pagination,
            'categories' => $categories,
            'years'      => array(),
        ]);
    }

    // single entry
    public function actionView($id) {
        $video = Video::findOne($id);
        return $this->render('view', [
            'video' => $video,
        ]);
    }

    // simple search
    public function actionSearch($q) {
        $query = Video::find();
        $query = $this->filterResults($query, $ch = "", $del = "-1", $sub = "-1", $pub = "-1", $live = "-1", $hd = "-1", $cat = "", $year = "");
        $pagination = new Pagination([
            'defaultPageSize' => $_COOKIE["results"]??20,
            'totalCount' => $query->count(),
        ]);

        $videos = $query->orderBy('Kuupäev '.($_GET["ord"]??'DESC'))
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        $categories = Video::find()->orderBy('Category')->select('Category,CategoryMUI_en')->distinct()->all();
        $channels = Video::find()->orderBy('Kanal')->select('Kanal,KanalMUI_et,KanalMUI_en')->distinct()->all();
        return $this->render('index', [
            'videos'     => $videos,
            'pagination' => $pagination,
            'channels'   => $channels,
            'categories' => $categories,
            'years'      => array(),
        ]);
    }

    private function filterResults($query, $q = "", $ch = "", $del = "-1", $sub = "-1", $pub = "-1", $live = "-1", $hd = "-1", $cat = "", $year = "") {
        $query->where(["like", "CONCAT(Video, Kanal, Kirjeldus, URL, Kuupäev, Filename, Category, Tags, OdyseeURL)", $q]);
        if ($del != "-1") $query->andWhere("Kustutatud=:del", ["del" => $del]);
        if ($sub != "-1") $query->andWhere("Subtiitrid=:sub", ["sub" => $sub]);
        if ($pub != "-1") $query->andWhere("Avalik=:pub", ["pub" => $pub]);
        if ($live != "-1") $query->andWhere("Ülekanne=:live", ["live" => $live]);
        if ($hd != "-1") $query->andWhere("HD=:hd", ["hd" => $hd]);
        if ($ch != "") $query->andWhere("Kanal=:ch", ["ch" => $ch]);
        if ($cat != "") $query->andWhere("Category=:cat", ["cat" => $cat]);
        if ($year != "") $query->andWhere("Kuupäev > :year_start", ["year_start" => ($year-1)."-12-31"]);
        if ($year != "") $query->andWhere("Kuupäev < :year_end", ["year_end" => ($year+1)."-01-01"]);
        return $query;
    }

    // advanced search (url: adv-search)
    public function actionAdvSearch($q = "", $ch = "", $del = "-1", $sub = "-1", $pub = "-1", $live = "-1", $hd = "-1", $cat = "", $year = "") {
        $query = Video::find();
        $query = $this->filterResults($query, $q, $ch, $del, $sub, $pub, $live, $hd, $cat, $year);
        $pagination = new Pagination([
            'defaultPageSize' => $_COOKIE["results"]??20,
            'totalCount' => $query->count(),
        ]);

        $videos = $query->orderBy('Kuupäev '.($_GET["ord"]??'DESC'))
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        $categories = Video::find()->orderBy('Category')->select('Category,CategoryMUI_en')->distinct()->all();
        $channels = Video::find()->orderBy('Kanal')->select('Kanal,KanalMUI_et,KanalMUI_en')->distinct()->all();
        $years = Video::find()->orderBy("Kuupäev DESC")->select('Kuupäev')->distinct()->all();
        return $this->render('index', [
            'videos'     => $videos,
            'pagination' => $pagination,
            'channels'   => $channels,
            'categories' => $categories,
            'years'      => $years,
        ]);
    }
    public function actionReport($q = "", $ch = "", $del = "-1", $sub = "-1", $pub = "-1", $live = "-1", $hd = "-1", $cat = "", $year = "", $save = false, $frmt = "") {
        $query = Video::find();
        $query = $this->filterResults($query, $q, $ch, $del, $sub, $pub, $live, $hd, $cat, $year);
        $cols = Video::getTableSchema()->getColumnNames();
        $format = $_COOKIE["reportformat"]??"html";
        if ($frmt != "") {
            $format = $frmt;
        }
        $videos = $query->orderBy('Kuupäev '.($_GET["ord"]??'DESC'))->all();
        switch ($format) {
            case "csv":
                return Yii::t("app", "CSV raporti vormingut ei toetata");
            case "json":
                if ($save) {
                    header("Content-type: application/json");
                    header('Content-Disposition: attachment; filename="'.Yii::t("app", "vaste").'.json"'); 
                }
                return $this->asJson($videos);
            case "html":
            default:
                if ($save) {
                    header("Content-type: text/html");
                    header('Content-Disposition: attachment; filename="'.Yii::t("app", "vaste").'.html"'); 
                }
                $this->layout = "report_html";
                return $this->render('report', [
                    'videos' => $videos,
                    'cols' => $cols,
                ]);
        }
    }

    public static function changeTitle($view) {
        $view->title = "Video";
    }
}