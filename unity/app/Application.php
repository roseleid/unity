<?php namespace App;

require_once "../vendor/core/bootstrap.php";

require_once "../app/models/ArticleModel.php";
require_once "../app/models/UserModel.php";

use Core\Auth\AuthService;

use Core\Controllers\ControllerBase;

use Core\Router\Router;

use Core\DatabaseService;

use App\Models\ArticleModel;
use App\Models\UserModel;

final class Application
{
    private static AuthService $authService;
    private static DatabaseService $databaseService;

    private static ControllerBase $controller;
    private static Router $router;

    public final static function getAuthService(): AuthService
    {
        return static::$authService;
    }

    public final static function setAuthService(AuthService $authService): void
    {
        static::$authService = $authService;
        return;
    }

    public final static function getDatabaseService(): DatabaseService
    {
        return static::$databaseService;
    }

    public final static function setDatabaseService(DatabaseService $databaseService): void
    {
        static::$databaseService = $databaseService;
        return;
    }

    public final static function getController(): ControllerBase
    {
        return static::$controller;
    }

    public final static function setController(ControllerBase $controller): void
    {
        static::$controller = $controller;
        return;
    }

    public final static function getRouter(): Router
    {
        return static::$router;
    }

    public final static function setRouter(Router $router): void
    {
        static::$router = $router;
        return;
    }

    public final function run(): void
    {
        session_start();

        ArticleModel::read();
        UserModel::read();

        static::getRouter()->resolve();
        return;
    }
}

?>