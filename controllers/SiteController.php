<?php

namespace app\controllers;

use app\models\ArticleSearch;
use app\models\Image;
use app\models\ImageSearch;
use app\models\ImageUpload;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;
use ZipArchive;

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
        $searchModel = new ImageSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = new Image();
        $image = $model->getById($id);

        return $this->render('view', [
            'image' => $image
        ]);
    }

    /**
     * Displays uploads image page.
     *
     * @return string | Response
     */
    public function actionUploads(): Response|string
    {
        $model = new ImageUpload();

        if(Yii::$app->request->isPost)
        {
            $model->image = UploadedFile::getInstances($model, 'image');
            if ($model->upload()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('uploadsImage', [
            'model' => $model
        ]);
    }

    public function actionDownload($name)
    {
        $model = new ImageUpload();

        return $model->download($name);
    }
}
