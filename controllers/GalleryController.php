<?php
namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination;

use app\models\Gallery;

class GalleryController extends Controller
{
    public function actionIndex($q = '', $del = '-1')
    {
        $query = Gallery::find()
        ->where(["like", "CONCAT(Kanal,Kirjeldus,Loomiskuupäev,URL)", $q]);
        if ($del != "-1") $query->andWhere("Kustutatud=:del", ["del" => $del]);
        $pagination = new Pagination([
            'defaultPageSize' => $_COOKIE["results"]??20,
            'totalCount' => $query->count(),
        ]);

        $channels = $query->orderBy('Loomiskuupäev '.($_GET["ord"]??'DESC'))
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        return $this->render('index', [
            'channels'     => $channels,
            'pagination' => $pagination,
        ]);
    }

    public function actionView($id) {
        $channel = Gallery::findOne($id);
        return $this->render('view', [
            'channel' => $channel,
        ]);
    }
}