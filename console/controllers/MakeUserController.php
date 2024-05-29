<?php

namespace console\controllers;

use common\models\User;
use Exception;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

class MakeUserController extends Controller
{
    /**
     * Makes active user 
     *
     * @return int
     */
    public function actionIndex(): int
    {
        $auth = Yii::$app->authManager;
        $user_role = $auth->getRole('user');
        try {
            $user = new User();
            $user->username = Console::input('Enter username: ');
            $user->email = Console::input('Enter email: ');
            $user->setPassword(Console::input('Enter password: '));
            $user->status = User::STATUS_ACTIVE;
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            $user->save();
            $auth->assign($user_role, $user->getId());
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }

        Console::stdout("User $user->username is created!\n");
        return ExitCode::OK;
    }
}
