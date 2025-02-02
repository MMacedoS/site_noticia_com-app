<?php

namespace App\Models\Colaborator;

use App\Models\Traits\UuidTrait;

class Colaborador {
    
    use UuidTrait;

    public $id;
    public string $uuid;
    public string $setor_id;
    public string $pessoa_fisica_id;
    public $pessoa_fisica;
    public string $ativo;
    public $created_at;
    public $updated_at;

    public function __construct () {}

    public function create(
        array $data
    ): Colaborador {
        $colaborador = new Colaborador();
        $colaborador->id = $data['id'] ?? null;
        $colaborador->uuid = $data['uuid'] ?? $this->generateUUID();
        $colaborador->setor_id = (int)$data['sector_id'] ?? 1;
        $colaborador->pessoa_fisica_id = (int)$data['person_id'];
        $colaborador->ativo = (int)$data['active'] ?? 1; 
        $colaborador->created_at = $data['created_at'] ?? null;
        $colaborador->updated_at = $data['updated_at'] ?? null;
        return $colaborador;
    }

    public function update(array $data, Colaborador $colaborador): Colaborador
    {
        $colaborador->pessoa_fisica_id = $data['person_id'] ?? $colaborador->pessoa_fisica_id;
        $colaborador->setor_id = $data['sector_id'] ?? $colaborador->setor_id;
        $colaborador->ativo = $data['active'] ?? $colaborador->ativo;

        return $colaborador;
    }
}