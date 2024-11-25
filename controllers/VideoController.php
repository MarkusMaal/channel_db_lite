<?php
namespace app\controllers;

use app\models\GeneralComments;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\data\Pagination;

use app\models\Video;
use yii\web\NotFoundHttpException;

class VideoController extends Controller
{
    // all videos
    public function actionIndex()
    {
        return $this->redirect(['/video/adv-search']);
    }

    // single entry
    public function actionView($id) {
        require_once(Yii::getAlias("@app/helpers/InitLang.php"));
        if (!preg_match("[[0-9]+]", $id)) {
            throw new BadRequestHttpException(Yii::t("app", "Video ID peab olema numbriline väärtus"));
        }
        $video = Video::findOne($id);
        // 00, 000, 0000, etc. aren't numbers, so let's play a special video instead of showing an error ---____---
        if (preg_match('[^0[0+]]', $id)) {
            return $this->redirect('https://www.youtube.com/watch?v=O4exTs8FQaw');
        } else if ($video == null) {
            throw new NotFoundHttpException(Yii::t("app", "Video ei ole andmebaasis"));
        }
        // SELECT * FROM general_comments WHERE PAGE_ID = $id AND THREAD = 1 AND REPLY = 0 ORDER BY(ID) DESC
        $comments = GeneralComments::find()->where(["PAGE_ID" => $id])->andWhere((["THREAD" => "1"]))->andWhere((["REPLY" => "0"]))->orderBy(["id" => SORT_DESC])->all();
        return $this->render('view', [
            'video' => $video,
            'comments' => $comments,
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
    public function actionAdvSearch($q = "", $ch = "", $del = "-1", $sub = "-1", $pub = "-1", $live = "-1", $hd = "-1", $cat = "", $year = "", $sort = "Kuupäev") {
        require_once(Yii::getAlias("@app/helpers/InitLang.php"));
        if ($year != "" && (!preg_match("[[0-9]+]", $year) || preg_match("[^0]", $year))) {
            throw new BadRequestHttpException(Yii::t("app", "Aastaarv peab olema numbriline väärtus"));
        }
        $query = Video::find();
        $query = $this->filterResults($query, $q, $ch, $del, $sub, $pub, $live, $hd, $cat, $year);
        $pagination = new Pagination([
            'defaultPageSize' => $_COOKIE["results"]??20,
            'totalCount' => $query->count(),
        ]);

        $cols = Video::getTableSchema()->getColumnNames();
        $error = 0;
        if (!in_array($sort, $cols)) {
            $sort = "Kuupäev";
            $error = 1;
        }
        $videos = $query->orderBy([
            $sort => ((isset($_GET["ord"]) && $_GET["ord"] == "ASC") ? SORT_ASC: SORT_DESC)
        ])
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
            'cols'       => $cols,
            'error'      => $error
        ]);
    }
    public function actionReport($q = "", $ch = "", $del = "-1", $sub = "-1", $pub = "-1", $live = "-1", $hd = "-1", $cat = "", $year = "", $save = false, $frmt = "", $sort = "Kuupäev") {
        require_once(Yii::getAlias("@app/helpers/InitLang.php"));
        if ($year != "" && (!preg_match("[[0-9]+]", $year) || preg_match("[^0]", $year))) {
            throw new BadRequestHttpException(Yii::t("app", "Aastaarv peab olema numbriline väärtus"));
        }
        $query = Video::find();
        $query = $this->filterResults($query, $q, $ch, $del, $sub, $pub, $live, $hd, $cat, $year);
        $cols = Video::getTableSchema()->getColumnNames();
        $format = $_COOKIE["reportformat"]??"html";
        if ($frmt != "") {
            $format = $frmt;
        }
        if (!in_array($sort, $cols)) {
            $sort = "Kuupäev";
        }
        $videos = $query->orderBy([
            $sort => ((isset($_GET["ord"]) && $_GET["ord"] == "ASC") ? SORT_ASC: SORT_DESC)
        ])->all();
        $cols[] = "has_thumbnail";
        $cols[] = "local_stream";
        foreach ($videos as $video) {
            $thumb_path = Yii::getAlias("@app/web/thumbs") . "/" . $video->getAttributes()["ID"] . ".jpg";
            $vid_path = Yii::getAlias("@app/web/stream") . "/" . $video->getAttributes()["ID"] . ".mp4";
            $www_thumb_path = Yii::getAlias("@web/thumbs") . "/" . $video->getAttributes()["ID"] . ".jpg";
            $www_vid_path = Yii::getAlias("@web/stream") . "/" . $video->getAttributes()["ID"] . ".mp4";
            $video["has_thumbnail"] = file_exists($thumb_path) ? $www_thumb_path : "N/A";
            $video["local_stream"] = file_exists($vid_path) ? $www_vid_path : "N/A";
        }
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