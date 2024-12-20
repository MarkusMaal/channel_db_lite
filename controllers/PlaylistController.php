<?php
namespace app\controllers;

use app\models\Gallery;
use app\models\Playlists;
use yii\data\Pagination;
use yii\web\Controller;

class PlaylistController extends Controller
{
    // all playlists
    public function actionIndex($success = false): string
    {
        $playlists = Playlists::find();
        $gallery = Gallery::find();
        $_GET["success"] = false;
        $pagination = new Pagination([
            'defaultPageSize' => $_COOKIE["results"]??20,
            'totalCount' => $playlists->count(),
        ]);
        return $this->render('list', [
            'playlists' => $playlists->offset($pagination->offset)
                ->limit($pagination->limit)->all(),
            'gallery' => $gallery,
            'pagination' => $pagination,
            'success' => $success,
        ]);
    }

}