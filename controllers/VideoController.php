<?php
namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination;

use app\models\Video;

class VideoController extends Controller
{
    public function actionIndex()
    {
        $query = Video::find();
        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);

        $videos = $query->orderBy('Kuupäev '.($_GET["ord"]??'DESC'))
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        return $this->render('index', [
            'videos'     => $videos,
            'pagination' => $pagination,
        ]);
    }

    public function actionView($id) {
        $video = Video::findOne($id);
        return $this->render('view', [
            'video' => $video,
        ]);
    }

    public static function changeTitle($view) {
        $view->title = "Video";
    }
}