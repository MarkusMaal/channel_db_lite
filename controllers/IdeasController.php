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
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);

        $ideas = $query->orderBy('id DESC')
        ->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
        return $this->render('index', [
            'ideas'     => $ideas,
            'pagination' => $pagination,
        ]);
    }

    public function actionView($id) {
        $idea = Ideas::findOne($id);
        return $this->render('view', [
            'idea' => $idea,
        ]);
    }
}