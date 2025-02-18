<?php

namespace App\Config;

use App\Controllers\v1\Sector\SetorController;
use App\Interfaces\Sector\ISetorRepository;
use App\Repositories\Sector\SetorRepository;

use App\Controllers\v1\Affiliate\FiliadoController;
use App\Interfaces\Affiliate\IFiliadoRepository;
use App\Repositories\Affiliate\FiliadoRepository;

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
                IFiliadoRepository::class,
                new FiliadoRepository()
        );
    }
}