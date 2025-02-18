<?php

use App\Config\AppServiceProvider;
use App\Config\Auth;
use App\Config\Container;
use App\Config\Router;
use App\Controllers\v1\NotFound\NotFoundController;
use App\Controllers\v1\Sector\SetorController;
use App\Controllers\v1\Site\SiteController;
use App\Controllers\v1\Affiliate\FiliadoRepository;

$container = new Container();
$appServiceProvider = new AppServiceProvider($container);
$appServiceProvider->registerDependencies();

$siteController = $container->get(SiteController::class);
$setorController = $container->get(SetorController::class);
$filiadoController = $container->get(FiliadoController::class);

$router = new Router();
$auth = new Auth();

$setorController = $container->get(SetorController::class);
$siteController = $container->get(SiteController::class);
$filiadoController = $container->get(FiliadoController::class);

$notFoundController = new NotFoundController();

$router->create("GET", "/", [$siteController, "index"], null);
$router->create("GET", "/sobre", [$siteController, "about"], null);

$router->create("GET", "/noticias", [$siteController, "noticias"], null);

$router->create("GET", "/noticias/{title}", [$siteController, "noticia"], null);

$router->create('GET', '/not-found', [$notFoundController, 'index']);

//filiados
$router->create('GET', '/filiados', [$filiadoController, 'index'], $auth);
$router->create('GET', '/filiados/criar', [$filiadoController, 'create'], $auth);
$router->create('POST', '/filiados/criar', [$filiadoController, 'store'], $auth);
$router->create('GET', '/filiados/{id}/editar', [$filiadoController, 'edit'], $auth);
$router->create('POST', '/filiados/{id}/editar', [$filiadoController, 'update'], $auth);
$router->create('DELETE', '/filiados/{id}', [$filiadoController, 'destroy'], $auth);

return $router;