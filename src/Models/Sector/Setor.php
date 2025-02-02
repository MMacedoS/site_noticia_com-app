<?php

namespace App\Models\Sector;

use App\Models\Traits\UuidTrait;

class Setor {
    
    use UuidTrait;

    public $id;
    public string $uuid;
    public string $setor;
    public string $ativo;
    public $created_at;
    public $updated_at;

    public function __construct () {}

    public function create(
        array $data
    ): Setor {
        $sector = new Setor();
        $sector->id = $data['id'] ?? null;
        $sector->uuid = $data['uuid'] ?? $this->generateUUID();
        $sector->setor = (string)$data['sector'];
        $sector->ativo = (int)$data['active'] ?? 1; 
        $sector->created_at = $data['created_at'] ?? null;
        $sector->updated_at = $data['updated_at'] ?? null;
        return $sector;
    }

    public function update(array $data, Setor $sector): Setor
    {
        $sector->setor = $data['sector'] ?? $sector->setor;
        $sector->ativo = $data['active'] ?? $sector->ativo;

        return $sector;
    }
}