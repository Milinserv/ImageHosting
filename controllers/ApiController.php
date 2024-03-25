<?php

namespace app\controllers;

use app\models\Image;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

class ApiController extends ActiveController
{
    public $modelClass = 'app\models\Image';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    public function actions(){
        $actions = parent::actions();
        unset($actions['view']);
        return $actions;
    }

    protected function verbs(){
        return [
            'view' => ['GET'],
        ];
    }

    public function actionView($id)
    {
        $model = new Image();

        return $model->getById($id);
    }
}