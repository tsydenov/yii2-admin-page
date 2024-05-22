<?php

/** @var yii\web\View $this */

use yii\grid\GridView;

$this->title = 'Admin page';
?>

<div class="url-status-index">
    <?= $this->render('_search', ['model' => $searchModel]) ?>

    <br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'url', 'created_at', 'updated_at', 'status_code'
        ]
    ]) ?>
</div>