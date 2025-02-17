<?php

namespace App\Interfaces\Affiliate;

interface IAffiliateRepository{
    public function allAffiliate(array $params);

    public function saveAll(array $params);

    public function create(array $params);

    public function updateAll(array $params);

    public function update(array $params, int $id);

    public function findByUuid(string $uuid);

    public function findById(int $id);

    public function deleteAll($filiado);

    public function delete(int $id);

    public function findByAffiliateId(string $data);
}