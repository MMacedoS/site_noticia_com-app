<?php

use App\Config\AppServiceProvider;
use App\Config\Auth;
use App\Config\Container;
use App\Config\Router;
use App\Controllers\v1\Dashboard\DashboardController;
use App\Controllers\v1\NotFound\NotFoundController;
use App\Controllers\v1\Sector\SetorController;
use App\Controllers\v1\Site\SiteController;
use App\Controllers\v1\New\NoticiaController;

$container = new Container();
$appServiceProvider = new AppServiceProvider($container);
$appServiceProvider->registerDependencies();

$siteController = $container->get(SiteController::class);
$dashboardController = $container->get(DashboardController::class);
$setorController = $container->get(SetorController::class);
$noticiaController = $container->get(NoticiaController::class);

$router = new Router();
$auth = new Auth();

$noticiaController = $container->get(NoticiaController::class);
$siteController = $container->get(SiteController::class);

$notFoundController = new NotFoundController();

$router->create("GET", "/", [$siteController, "index"], null);
$router->create("GET", "/sobre", [$siteController, "about"], null);

$router->create("GET", "/noticias", [$siteController, "noticias"], null);

$router->create("GET", "/noticias/{title}", [$siteController, "noticia"], null);

$router->create('GET', '/not-found', [$notFoundController, 'index']);

$router->create('GET', "/dashboard", [$dashboardController, 'index'], null);

//noticias
$router->create('GET', '/noticias', [$noticiaController, 'index'], $auth);
$router->create('GET', '/noticias/criar', [$noticiaController, 'create'], $auth);
$router->create('POST', '/noticias/criar', [$noticiaController, 'store'], $auth);
$router->create('GET', '/noticias/{id}/editar', [$noticiaController, 'edit'], $auth);
$router->create('POST', '/noticias/{id}/editar', [$noticiaController, 'update'], $auth);
$router->create('DELETE', '/noticias/{id}', [$noticiaController, 'destroy'], $auth);

return $router;