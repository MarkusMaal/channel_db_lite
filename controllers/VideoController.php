<?php
namespace app\controllers;

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
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);

        $videos = $query->orderBy('Kuupäev '.($_GET["ord"]??'DESC'))
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();

        $categories = Video::find()->orderBy('Category')->select('Category')->distinct()->all();
        $channels = Video::find()->orderBy('Kanal')->select('Kanal')->distinct()->all();
        return $this->render('index', [
            'videos'     => $videos,
            'channels'   => $channels,
            'pagination' => $pagination,
            'categories' => $categories,
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
        $query = Video::find()->where(["like", "CONCAT(Video, Kanal, Kirjeldus, URL, Kuupäev, Filename, Category, Tags, OdyseeURL)", $q]);
        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);

        $videos = $query->orderBy('Kuupäev '.($_GET["ord"]??'DESC'))
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        $categories = Video::find()->orderBy('Category')->select('Category')->distinct()->all();
        $channels = Video::find()->orderBy('Kanal')->select('Kanal')->distinct()->all();
        return $this->render('index', [
            'videos'     => $videos,
            'pagination' => $pagination,
            'channels'   => $channels,
            'categories' => $categories,
        ]);
    }

    // advanced search (url: adv-search)
    public function actionAdvSearch($q = "", $ch = "", $del = "", $sub = "", $pub = "", $live = "", $hd = "", $cat = "") {
        $query = Video::find()
        ->where(["like", "CONCAT(Video, Kanal, Kirjeldus, URL, Kuupäev, Filename, Category, Tags, OdyseeURL)", $q])
        ->andWhere(["like", "Kanal", $ch])
        ->andWhere(["like", "Kustutatud", $del])
        ->andWhere(["like", "Subtiitrid", $sub])
        ->andWhere(["like", "Avalik", $pub])
        ->andWhere(["like", "Ülekanne", $live])
        ->andWhere(["like", "HD", $hd])
        ->andWhere(["like", "Category", $cat]);
        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);

        $videos = $query->orderBy('Kuupäev '.($_GET["ord"]??'DESC'))
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        $categories = Video::find()->orderBy('Category')->select('Category')->distinct()->all();
        $channels = Video::find()->orderBy('Kanal')->select('Kanal')->distinct()->all();
        return $this->render('index', [
            'videos'     => $videos,
            'pagination' => $pagination,
            'channels'   => $channels,
            'categories' => $categories,
        ]);
    }

    public static function changeTitle($view) {
        $view->title = "Video";
    }
}