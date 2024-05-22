<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

// when "export to csv" button is clicked
// parse GET parameters in address bar and send a GET request to /admin/export-to-csv
$this->registerJs("
document.getElementById('export-button').addEventListener('click', function() {
    let exportUrl = this.getAttribute('data-url');

    // get GET parameters from address bar in browser
    function getUrlParams() {
        let params = {};
        let queryString = window.location.search.slice(1);
        let pairs = queryString.split('&');
        for (let i = 0; i < pairs.length; i++) {
            let pair = pairs[i].split('=');
            params[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1].replace(/\+/g, ' ') || '');
        }
        return params;
    }

    let params = getUrlParams();
    let form = document.createElement('form');
    form.method = 'GET';
    form.action = exportUrl;

    for (let key in params) {
        if (params.hasOwnProperty(key)) {
            let hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = key;
            hiddenField.value = params[key];
            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
});");

?>

<div class="post-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div style="width:30%">
        <?= $form->field($model, 'url') ?>
        <?= $form->field($model, 'status_code')->textInput(['placeholder' => 'Перечислите через пробел нужные значения']) ?>
    </div>

    <div>
        <?= Html::checkbox('last24Hours', false, ['label' => 'За последние 24 часа', 'value' => 1]) ?>
        <br>
        <?= Html::checkbox('statusCodeIsNot200', false, ['label' => 'Код ответа не равен 200', 'value' => 1]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Искать', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сбросить', Url::to(['admin/']), ['class' => 'btn btn-default']) ?>
        <?php
        $exportUrl = Url::to(['admin/export-to-csv']);
        echo Html::button('Экспортировать в CSV', ['id' => 'export-button', 'class' => 'btn btn-success', 'data-url' => $exportUrl]);
        ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>