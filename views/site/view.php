<?php
use yii\helpers\Html;
?>

<section class="text-center container">
    <div class="row py-lg-5">
        <div class="col-lg-12 col-md-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="fw-light"><?= $image->name ?></h3>
                <?= Html::a('Download', ['download', 'name' => $image->name ], ['class' => 'btn btn-warning']) ?>
            </div>
            <div class="justify-content-between align-items-center">
                <div class="row">
                    Дата загрузки: <?= $image->create_date ?>
                </div>
                <div class="row">
                    Время загрузки: <?= $image->loading_time ?>
                </div>
            </div>
            <div class="pt-2">
                <img src="<?php echo $image->getImage(); ?>" alt="Image">
            </div>
        </div>
    </div>
</section>


