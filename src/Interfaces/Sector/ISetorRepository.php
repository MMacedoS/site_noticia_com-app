<?php

namespace App\Interfaces\Sector;

interface ISetorRepository 
{
    public function allSector(array $params);

    public function create(array $params);
    
    public function update(array $params, int $id);

    public function findByUuid(string $uuid);

    public function findById(int $id);

    public function delete(int $id);
}