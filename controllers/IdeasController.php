<?php
namespace app\controllers;

use yii\web\Controller;
use yii\data\Pagination;

use app\models\Ideas;

class IdeasController extends Controller
{
    public function actionIndex()
    {
        $query = Ideas::find();
        $pagination = new Pagination([
            'defaultPageSize' => $_COOKIE["results"]??20,
            'totalCount' => $query->count(),
        ]);

        $ideas = $query->orderBy('id '.($_GET["ord"]??'DESC'))
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
        ]);
    }

    public function actionView($id) {
        $idea = Ideas::findOne($id);
        return $this->render('view', [
            'idea' => $idea,
        ]);
    }

    public function actionAdvSearch($q = '', $done = '', $class = '', $live = '', $ch = '') {
        $query = Ideas::find()
        ->where(["like", "CONCAT(Kanal, Video, Kirjeldus)", $q]);
        if ($done != "") $query->andWhere("Valmis=:done", ["done" => $done]);
        if ($class != "") $query->andWhere("Klass=:class", ["class" => $class]);
        if ($live != "") $query->andWhere("Ülekanne=:live", ["live" => $live]);
        if ($ch != "") $query->andWhere("Kanal=:ch", ["ch" => $ch]);
        $pagination = new Pagination([
            'defaultPageSize' => $_COOKIE["results"]??20,
            'totalCount' => $query->count(),
        ]);

        $ideas = $query->orderBy('id '.($_GET["ord"]??'DESC'))
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
        ]);
    }
}