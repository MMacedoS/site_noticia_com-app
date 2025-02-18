<?php

namespace App\Config;

use App\Controllers\v1\Sector\SetorController;
use App\Interfaces\Sector\ISetorRepository;
use App\Repositories\Sector\SetorRepository;

use App\Controllers\v1\Colaborator\ColaboradorController;
use App\Interfaces\Colaborator\IColaboradorRepository;
use App\Repositories\Colaborator\ColaboradorRepository;

class AppServiceProvider 
{
    protected $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function registerDependencies() {
        // Registra as dependências
        $this->container
            ->set(
                ISetorRepository::class, 
                new SetorRepository()
        );

        $this->container
            ->set(
                IColaboradorRepository::class, 
                new ColaboradorRepository()
        );
    }
}