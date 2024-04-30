<?php

namespace console\controllers;

use backend\models\UrlStatus;
use Exception;
use LucidFrame\Console\ConsoleTable;
use yii\console\Controller;
use yii\console\ExitCode;

class CheckStatusController extends Controller
{
    public function actionStatistics(): int
    {
        try {
            $urls = UrlStatus::find()
                ->where(['!=', 'status_code', 200])
                ->andWhere('updated_at > DATE_SUB(NOW(), INTERVAL 1 DAY)')
                ->all();
        } catch (Exception $e) {
            echo "Something went wrong!\n";
            echo $e->getMessage() . "\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $table = new ConsoleTable;
        $table->addHeader('url')->addHeader('status_code');
        foreach ($urls as $url) {
            $table->addRow()
                ->addColumn($url->url)
                ->addColumn($url->status_code);
        }
        $table->display();
        return ExitCode::OK;
    }
}
