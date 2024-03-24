<?php

/** @var yii\web\View $this */
/** @var app\models\ArticleSearch $searchModel */

/** @var yii\data\ActiveDataProvider $dataProvider */

use app\models\Image;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'html',
                'label' => 'Image',
                'value' => function ($data) {
                    return Html::img($data->getImage(), ['width' => 200]);
                }
            ],
            [
                'attribute' => 'name',
                'value' => function ($model) {
                    return Html::a(
                        $model->name,
                        ['view', 'id' => $model->id],
                        [
                            'title' => 'View',
                        ]
                    );
                },
                'format' => 'raw',
            ],
            'create_date',
        ],
    ]); ?>

</div>
