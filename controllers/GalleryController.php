<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;

use app\models\Gallery;

class GalleryController extends Controller
{
    public function actionIndex($q = '', $del = '-1', $sort = "Loomiskuupäev")
    {
        $query = Gallery::find();
        $query = $this->filterResults($query, $q, $del);
        $pagination = new Pagination([
            'defaultPageSize' => $_COOKIE["results"]??20,
            'totalCount' => $query->count(),
        ]);

        $channels = $query->orderBy(([
            $sort => ((isset($_GET["ord"]) && $_GET["ord"] == "ASC") ? SORT_ASC: SORT_DESC)
        ]))
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        return $this->render('index', [
            'channels'     => $channels,
            'pagination' => $pagination,
            'cols'       => Gallery::getTableSchema()->getColumnNames(),
        ]);
    }

    public function actionView($id) {
        $channel = Gallery::findOne($id);
        return $this->render('view', [
            'channel' => $channel,
        ]);
    }

    private function filterResults($query, $q, $del) {
        $query->where(["like", "CONCAT(Kanal,Kirjeldus,Loomiskuupäev,URL)", $q]);
        if ($del != "-1") $query->andWhere("Kustutatud=:del", ["del" => $del]);
        return $query;
    }

    public function actionReport_($q = '', $del = '-1', $save = false, $frmt = "", $sort = "Loomiskuupäev") {
        $query = Gallery::find();
        $query = $this->filterResults($query, $q, $del);
        $cols = Gallery::getTableSchema()->getColumnNames();
        $format = $_COOKIE["reportformat"]??"html";
        if ($frmt != "") {
            $format = $frmt;
        }
        $channels = $query->orderBy([
            $sort => ((isset($_GET["ord"]) && $_GET["ord"] == "ASC") ? SORT_ASC: SORT_DESC)
        ])->all();
        switch ($format) {
            case "csv":
                return Yii::t("app", "CSV raporti vormingut ei toetata");
            case "json":
                if ($save) {
                    header("Content-type: application/json");
                    header('Content-Disposition: attachment; filename="'.Yii::t("app", "vaste").'.json"'); 
                }
                return $this->asJson($channels);
            case "html":
            default:
                if ($save) {
                    header("Content-type: text/html");
                    header('Content-Disposition: attachment; filename="'.Yii::t("app", "vaste").'.html"'); 
                }
                $this->layout = "report_html";
                return $this->render('report', [
                    'channels' => $channels,
                    'cols' => $cols,
                ]);
        }
    }
}