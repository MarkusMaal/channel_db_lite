<?php
namespace app\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\data\Pagination;

use app\models\Ideas;

class IdeasController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(["/ideas/adv-search"]);
    }

    public function actionView($id) {
        $idea = Ideas::findOne($id);
        return $this->render('view', [
            'idea' => $idea,
        ]);
    }

    private function filterResults($query, $q = '', $done = '', $class = '', $live = '', $ch = '') {
        $query
        ->where(["like", "CONCAT(Kanal, Video, Kirjeldus)", $q]);
        if ($done != "") $query->andWhere("Valmis=:done", ["done" => $done]);
        if ($class != "") $query->andWhere("Klass=:class", ["class" => $class]);
        if ($live != "") $query->andWhere("Ülekanne=:live", ["live" => $live]);
        if ($ch != "") $query->andWhere("Kanal=:ch", ["ch" => $ch]);
        return $query;
    }

    public function actionAdvSearch($q = '', $done = '', $class = '', $live = '', $ch = '', $sort = "id") {
        require_once(Yii::getAlias("@app/helpers/InitLang.php"));
        if ($class != "" && (!preg_match("[[0-9]+]", $class) || preg_match("[^0]", $class) || $class < 1)) {
            throw new BadRequestHttpException(Yii::t("ideas", "Klass peab olema naturaalarv"));
        }
        $query = Ideas::find();
        $query = $this->filterResults($query, $q, $done, $class, $live, $ch);
        $pagination = new Pagination([
            'defaultPageSize' => $_COOKIE["results"]??20,
            'totalCount' => $query->count(),
        ]);
        $cols = Ideas::getTableSchema()->getColumnNames();
        $error = 0;
        if (!in_array($sort, $cols)) {
            $sort = "id";
            $error = 1;
        }
        $ideas = $query->orderBy([
            $sort => ((isset($_GET["ord"]) && $_GET["ord"] == "ASC") ? SORT_ASC: SORT_DESC)
        ])
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        $classes = Ideas::find()->orderBy('Klass')->select('Klass')->distinct()->all();
        $channels = Ideas::find()->orderBy('Kanal')->select('Kanal')->distinct()->all();
        return $this->render('index', [
            'ideas'     => $ideas,
            'pagination' => $pagination,
            'channels'   => $channels,
            'classes' => $classes,
            'cols'       => $cols,
            'error'      => $error
        ]);
    }


    public function actionReport($q = '', $done = '', $class = '', $live = '', $ch = '', $save = false, $frmt = "", $sort = "id") {
        $query = Ideas::find();
        $query = $this->filterResults($query, $q, $done, $class, $live, $ch);
        $cols = Ideas::getTableSchema()->getColumnNames();
        $format = $_COOKIE["reportformat"]??"html";
        if ($frmt != "") {
            $format = $frmt;
        }
        if (!in_array($sort, $cols)) {
            $sort = "id";
        }
        $ideas = $query->orderBy(([
            $sort => ((isset($_GET["ord"]) && $_GET["ord"] == "ASC") ? SORT_ASC: SORT_DESC)
        ]))->all();
        switch ($format) {
            case "csv":
                return Yii::t("app", "CSV raporti vormingut ei toetata");
            case "json":
                if ($save) {
                    header("Content-type: application/json");
                    header('Content-Disposition: attachment; filename="'.Yii::t("app", "vaste").'.json"'); 
                }
                return $this->asJson($ideas);
            case "html":
            default:
                if ($save) {
                    header("Content-type: text/html");
                    header('Content-Disposition: attachment; filename="'.Yii::t("app", "vaste").'.html"'); 
                }
                $this->layout = "report_html";
                return $this->render('report', [
                    'ideas' => $ideas,
                    'cols' => $cols,
                ]);
        }
    }
}