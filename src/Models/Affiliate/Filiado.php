<?php

namespace App\Models\Filiado;

use App\Models\Traits\UuidTrait;

class Filiado{
    
    use UuidTrait;

    public $id;
    public string $uuid;
    public int $pessoa_fisica_id;
    public string $profissao;
    public string $ativo;
    public $created_at;
    public $updated_at;

    public function __construct () {}

    public function create(
        array $data
    ): Filiado {
        $filiado = new Filiado();
        $filiado->id = $data['id'] ?? null;
        $filiado->uuid = $data['uuid'] ?? this->generateUUID();
        $filiado->pessoa_fisica_id = (int)$data['person_id'];
        $filiado->profissao = $data['profession'] ?? null;
        $filiado->ativo = (int)$data['active'] ?? 1;
        $filiado->created_at = $data['created_at'] ?? null;
        $filiado->updated_at = $data['updated_at'] ?? null;
        return $filiado;    
    }

    public function update(array $data, Filiado $filiado): Filiado
    {
        $filiado->pessoa_fisica_id = $data['person_id'] ?? $filiado->pessoa_fisica_id;
        $filiado->ativo = $data['active'] ?? $filiado->ativo;

        return $filiado;
    }
}