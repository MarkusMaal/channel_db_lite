<?php
namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination;

use app\models\Gallery;

class GalleryController extends Controller
{
    public function actionIndex()
    {
        $query = Gallery::find();
        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);

        $channels = $query->orderBy('Loomiskuupäev DESC')
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