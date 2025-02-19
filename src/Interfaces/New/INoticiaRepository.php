<?php

namespace App\Interfaces\New;

interface INoticiaRepository {

    public function allNotices(array $params);

    public function create(array $params);

    public function update(array $params, int $id);

    public function findByUuid(string $uuid);

    public function findById(int $id);

    public function delete(int $id);

}