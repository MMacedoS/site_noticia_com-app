<?php

namespace App\Interfaces\Colaborator;

interface IColaboradorRepository {
    public function allColaborators(array $params);

    public function saveAll(array $params);

    public function create(array $params);
    
    public function updateAll(array $params);

    public function update(array $params, int $id);

    public function findByUuid(string $uuid);

    public function findById(int $id);

    public function deleteAll($colaborador);

    public function delete(int $id);
    
    public function findByColaboratorId(string $data);
}