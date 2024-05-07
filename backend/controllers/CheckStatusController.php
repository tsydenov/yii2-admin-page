<?php

namespace backend\controllers;

use backend\models\UrlStatus;
use Yii;
use yii\rest\Controller;

class CheckStatusController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $urls = UrlStatus::find()->all();
        return $urls;
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $urls = $request->getBodyParam('url');

        if (isset($urls)) {
            $response = Yii::$app->urlChecker->check($urls);

            return ["codes" => $response];
        }

        return ["error" => "url attribute is required!"];
    }
}
