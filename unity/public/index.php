<?php

require_once "../vendor/core/bootstrap.php";

require_once "../app/bootstrap.php";

use Core\Auth\AuthService;

use Core\Router\MethodType;
use Core\Router\Router;

use Core\DatabaseService;

use App\Controllers\AccountController;
use App\Controllers\ArticleController;
use App\Controllers\AuthController;
use App\Controllers\ErrorController;
use App\Controllers\HomeController;

use App\Application;

$authService = new AuthService();

$databaseService = new DatabaseService();
$databaseService
    ->withServer("localhost", "unity")
    ->withUsername("root")
    ->withPassword()
    ->run();

$router = new Router();
$router->setRoute(MethodType::GET, "/", [ HomeController::class, "home" ]);
$router->setRoute(MethodType::GET, "/goodbye", [ HomeController::class, "goodbye" ]);
$router->setRoute(MethodType::GET, "/error", [ ErrorController::class, "error" ]);
$router->setRoute(MethodType::GET, "/articles", [ ArticleController::class, "list" ]);
$router->setRoute(MethodType::GET, "/article/{id:\d+}", [ ArticleController::class, "article" ]);
$router->setRoute(MethodType::GET, "/article/{id:\d+}/delete", [ ArticleController::class, "delete" ]);
$router->setRoute(MethodType::GET, "/article/{id:\d+}/edit", [ ArticleController::class, "edit" ]);
$router->setRoute(MethodType::GET, "/article/create", [ ArticleController::class, "create" ]);
$router->setRoute(MethodType::GET, "/auth/signIn", [ AuthController::class, "signIn" ]);
$router->setRoute(MethodType::GET, "/auth/signUp", [ AuthController::class, "signUp" ]);
$router->setRoute(MethodType::GET, "/auth/signOut", [ AuthController::class, "signOut" ]);
$router->setRoute(MethodType::GET, "/account", [ AccountController::class, "account" ]);
$router->setRoute(MethodType::GET, "/account/edit", [ AccountController::class, "edit" ]);
$router->setRoute(MethodType::GET, "/account/delete", [ AccountController::class, "delete" ]);
$router->setRoute(MethodType::GET, "/account/{id:\d+}", [ AccountController::class, "account" ]);

$router->setRoute(MethodType::POST, "/articles", [ ArticleController::class, "list" ]);
$router->setRoute(MethodType::POST, "/article/create", [ ArticleController::class, "create" ]);
$router->setRoute(MethodType::POST, "/article/{id:\d+}/edit", [ ArticleController::class, "edit" ]);
$router->setRoute(MethodType::POST, "/auth/signIn", [ AuthController::class, "signIn" ]);
$router->setRoute(MethodType::POST, "/auth/signUp", [ AuthController::class, "signUp" ]);
$router->setRoute(MethodType::POST, "/account/edit", [ AccountController::class, "edit" ]);
$router->setRoute(MethodType::POST, "/account/delete", [ AccountController::class, "delete" ]);

$app = new Application();
$app::setAuthService($authService);
$app::setDatabaseService($databaseService);
$app::setRouter($router);
$app->run();

?>