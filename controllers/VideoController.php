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
    public function actionAdvSearch($q = "", $ch = "", $del = "-1", $sub = "-1", $pub = "-1", $live = "-1", $hd = "-1", $cat = "") {
        $query = Video::find()
        ->where(["like", "CONCAT(Video, Kanal, Kirjeldus, URL, Kuupäev, Filename, Category, Tags, OdyseeURL)", $q]);
        if ($del != "-1") $query->andWhere("Kustutatud=:del", ["del" => $del]);
        if ($sub != "-1") $query->andWhere("Subtiitrid=:sub", ["sub" => $sub]);
        if ($pub != "-1") $query->andWhere("Avalik=:pub", ["pub" => $pub]);
        if ($live != "-1") $query->andWhere("Ülekanne=:live", ["live" => $live]);
        if ($hd != "-1") $query->andWhere("HD=:hd", ["hd" => $hd]);
        if ($ch != "") $query->andWhere("Kanal=:ch", ["ch" => $ch]);
        if ($cat != "") $query->andWhere("Category=:cat", ["cat" => $cat]);
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