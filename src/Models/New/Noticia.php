<?php

namespace App\Models\New;

use App\Models\Traits\UuidTrait;

class Noticia {

    use UuidTrait;

    public $id;
    public string $uuid;
    public string $titulo;
    public string $resumo;
    public string $noticia;
    public string $autor;
    public string $fonte;
    public string $tag;
    public string $ativo;
    public string $link;
    public $created_at;
    public $updated_at;

    public function __construct(){}

    public function create(
        array $data
    ) : Noticia {
        $noticia = new Noticia();
        $noticia->id = $data['id'] ?? null;
        $noticia->uuid = $data['uuid'] ?? $this->generateUUID();
        $noticia->titulo = $data['title'] ?? null;
        $noticia->resumo = $data['summary'] ?? null;
        $noticia->noticia = $data['new'] ?? null;
        $noticia->autor = $data['author'] ?? null;
        $noticia->fonte = $data['font'] ?? null;
        $noticia->tag = $data['tag'] ?? null;
        $noticia->ativo = $data['active'] ?? 1;
        $noticia->link = $data['link'] ?? null;
        $noticia->created_at = $data['created_at'] ?? null;
        $noticia->updated_at = $data['updated_at'] ?? null;
        return $noticia;
    }

    public function update(
        array $data, Noticia $noticia
    ) : Noticia {
        $noticia->titulo = $data['title'] ?? $noticia->titulo;
        $noticia->resumo = $data['summary'] ?? $noticia->resumo;
        $noticia->noticia = $data['new'] ?? $noticia->noticia;
        $noticia->autor = $data['author'] ?? $noticia->autor;
        $noticia->fonte = $data['font'] ?? $noticia->fonte;
        $noticia->tag = $data['tag'] ?? $noticia->tag;
        $noticia->ativo = $data['active'] ?? $noticia->ativo; 
        $noticia->link = $data['link'] ?? $noticia->link;
        return $noticia;
    }

}