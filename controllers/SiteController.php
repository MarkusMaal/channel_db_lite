<?php

namespace app\controllers;

use app\models\Gallery;
use app\models\Playlists;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntryForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionHello()
    {
        return $this->render('hello');
    }

    public function actionSync() {

        $query = Gallery::find();
        foreach ($query->all() as $channel) {
            $full_uri = $channel->getAttributes()["URL"];
            $channel_id = str_replace("https://www.youtube.com/channel/", "", $full_uri);
            if (($full_uri != $channel_id) && ($channel["Kustutatud"] != 1)) {
                $playlists = $this->getPlaylists($channel_id);
                foreach ($playlists as $response) {
                    if (($response["kind"] ?? "null") == "youtube#playlistListResponse") {
                        foreach ($response["items"] as $entry) {
                            if ((int)$channel->getAttributes()["ID"] == 1) {
                                continue;
                            }
                            $playlist = new Playlists();
                            $thumbs = $entry["snippet"]["thumbnails"];
                            $thumb_url = "";
                            $types = ["maxres", "high", "medium", "standard", "default"];
                            foreach ($types as $type) {
                                if (isset($thumbs[$type])) {
                                    $thumb_url = $thumbs[$type]["url"];
                                    break;
                                }
                            }
                            $desc = $entry["snippet"]["description"];
                            $playlist->setAttribute("YT_ID", $entry["id"]);
                            $playlist->setAttribute("TITLE", $entry["snippet"]["title"]);
                            $playlist->setAttribute("DESCRIPTION", (!empty($desc) ? $desc : "N/A"));
                            $playlist->setAttribute("THUMBNAIL", $thumb_url);
                            $playlist->setAttribute("GALLERY_ID", $channel->getAttributes()["ID"]);
                            try {
                                if ($playlist->validate()) {
                                    $playlist->save();
                                } else {
                                    var_dump($playlist->getErrors());
                                    die();
                                }
                            } catch (Exception $e) {
                                // ignored
                            }
                        }
                    }
                }
            }
        }
        return $this->redirect(Yii::getAlias("@web/playlist/index"));
    }

    public function getPlaylists($channelId, $refreshToken = null) {
        $youtube_key = file_exists(Yii::getAlias("@app/API_KEY.DAT")) ? file_get_contents(Yii::getAlias("@app/API_KEY.DAT")) : null;
        if ($youtube_key == null) {
            return null;
        }
        $url = "https://www.googleapis.com/youtube/v3/playlists";

        static $all =[];
        $params =[
            'key' => $youtube_key,
            'channelId' => $channelId,
            'part' => "snippet,id",
            'order' => "date",
            'maxResults' => 20,
            'pageToken' => $refreshToken,
        ];

        $call = $url.'?'.http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $call);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $data = NULL;
        $data = json_decode($output,true);
        $all[] = $data;
        if(isset($data['nextPageToken'])){
            if($data['nextPageToken'] != NULL ){
                $pageToken = $data['nextPageToken'];
                $all[] = $this->getPlaylists($channelId,$pageToken);
            }
        }
        curl_close($ch);
        return $all;
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

   /**
    * Say hello
    *
    * @return string
    */
   public function actionSay($message = "Hello")
   {
   	    return $this->render('say', ['message' => $message]);
   } 

   public function actionEntry() {
        $model = new EntryForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->render('entry-confirm', ['model' => $model]);
        } else {
            return $this->render('entry', ['model' => $model]);
        }
   }
}
