<?php

use App\Config\AppServiceProvider;
use App\Config\Auth;
use App\Config\Container;
use App\Config\Router;
use App\Controllers\v1\NotFound\NotFoundController;
use App\Controllers\v1\Sector\SetorController;
use App\Controllers\v1\Site\SiteController;
use App\Controllers\v1\Colaborator\ColaboradorController;

$container = new Container();
$appServiceProvider = new AppServiceProvider($container);
$appServiceProvider->registerDependencies();

$siteController = $container->get(SiteController::class);
$setorController = $container->get(SetorController::class);
$colaboradorController = $container->get(ColaboradorController::class);

$router = new Router();
$auth = new Auth();

$setorController = $container->get(SetorController::class);
$siteController = $container->get(SiteController::class);
$colaboradorController = $container->get(ColaboradorController::class);

$notFoundController = new NotFoundController();

$router->create("GET", "/", [$siteController, "index"], null);
$router->create("GET", "/sobre", [$siteController, "about"], null);

$router->create("GET", "/noticias", [$siteController, "noticias"], null);

$router->create("GET", "/noticias/{title}", [$siteController, "noticia"], null);

$router->create('GET', '/not-found', [$notFoundController, 'index']);

//colaboradores
$router->create('GET', '/colaboradores', [$colaboradorController, 'index'], $auth);
$router->create('GET', '/colaboradores/criar', [$colaboradorController, 'create'], $auth);
$router->create('POST', '/colaboradores/criar', [$colaboradorController, 'store'], $auth);
$router->create('GET', '/colaboradores/{id}/editar', [$colaboradorController, 'edit'], $auth);
$router->create('POST', '/colaboradores/{id}/editar', [$colaboradorController, 'update'], $auth);
$router->create('DELETE', '/colaboradores/{id}', [$colaboradorController, 'destroy'], $auth);

return $router;