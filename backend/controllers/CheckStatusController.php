<?php

namespace backend\controllers;

use backend\models\UrlStatus;
use backend\components\UrlChecker;
use DateTime;
use Yii;
use yii\httpclient\Client;
use yii\web\Controller;

class CheckStatusController extends Controller
{
    public $urlStatusClass = 'backend\models\UrlStatus';
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $urls = UrlStatus::find()->all();
        return $this->asJson($urls);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $urls = $request->getBodyParam('url');

        if (isset($urls)) {
            $response = Yii::$app->urlChecker->check($urls);

            return $this->asJson(["codes" => $response]);
        }

        return $this->asJson(["error" => "url attribute is required!"]);
    }
}
