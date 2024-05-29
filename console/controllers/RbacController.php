<?php

namespace console\controllers;

use common\models\User;
use Exception;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

class RbacController extends Controller
{
    /**
     * Creates admin and user roles
     *
     * @return int
     */
    public function actionInit(): int
    {
        $auth = Yii::$app->authManager;

        $admin = $auth->createRole('admin');
        $user = $auth->createRole('user');
        $auth->add($admin);
        $auth->add($user);

        return ExitCode::OK;
    }

    /**
     * Assigns user an admin role
     *
     * @return int
     */
    public function actionAssignAdmin(): int
    {
        $auth = Yii::$app->authManager;
        $admin = $auth->getRole('admin');
        Console::stdout("Who is going to be assigned an admin role?\n");
        $username = Console::input('Enter username: ');

        try {
            $user = User::findByUsername($username);
            $auth->assign($admin, $user->getId());
        } catch (Exception $e) {
            Console::stdout($e->getMessage());
            return ExitCode::UNSPECIFIED_ERROR;
        }
        return ExitCode::OK;
    }
}
