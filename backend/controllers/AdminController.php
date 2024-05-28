<?php

namespace backend\controllers;

use backend\models\UrlStatusSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ]
                ]
            ]
        ];
    }

    /**
     * Renders table
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'main';

        $searchModel = new UrlStatusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Exports data to csv table
     *
     * @return string
     */
    public function actionExportToCsv()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->set('Content-type', 'text/csv');
        Yii::$app->response->headers->add('Content-disposition', 'attachment; filename=report_' . date('YmdHi') . '.csv');

        $searchModel = new UrlStatusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $dataProvider->pagination = false;

        $result = 'url,created_at,updated_at,status_code';

        $models = $dataProvider->getModels();
        foreach ($models as $model) {
            $result .= "\n$model->url,$model->created_at,$model->updated_at,$model->status_code";
        }

        return $result;
    }
}
