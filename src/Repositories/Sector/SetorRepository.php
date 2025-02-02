<?php

namespace App\Repositories\Sector;

use App\Config\Database;
use App\Interfaces\Sector\ISetorRepository;
use App\Models\Sector\Setor;
use App\Repositories\Traits\FindTrait;
use App\Utils\LoggerHelper;

class SetorRepository implements ISetorRepository 
{
    const CLASS_NAME = Setor::class;
    const TABLE = 'setor';

    use FindTrait;
    protected $conn;
    protected $model;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
        $this->model = new Setor();
    }

    public function allSector(array $params)
    {
        $sql = "SELECT * FROM " . self::TABLE;

        $conditions = [];
        $bindings = [];

        if (isset($params['sector'])) {
            $conditions[] = "setor = :setor";
            $bindings[':setor'] = $params['sector'];
        }

        if (isset($params['active'])) {
            $conditions[] = "ativo = :ativo";
            $bindings[':ativo'] = $params['active'];
        }

        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY setor DESC";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute($bindings);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::CLASS_NAME);  
        
    }

    public function create(array $params)
    {
        $existingSector = $this->findBySector($params['sector']);
        
        if ($existingSector) {
            return $existingSector;
        }

        $sector = $this->model->create(
            $params
        );

        try {
            $stmt = $this->conn
            ->prepare(
                "INSERT INTO " . self::TABLE . " 
                  set 
                    uuid = :uuid,
                    setor = :sector
            ");
            $create = $stmt->execute([
                ':uuid' => $sector->uuid,
                ':sector' => $sector->setor
            ]);
    
            if (is_null($create)) {
                return null;
            }

            $created = $this->findByUuid($sector->uuid);
           
            return $created;
        } catch (\Throwable $th) {
            LoggerHelper::logInfo($th->getMessage());
            return null;
        } finally {          
            Database::getInstance()->closeConnection();
        }        
    }

    public function update(array $params, int $id)
    {
        $sector = $this->findById((int)$id);
        
        if (is_null($sector)) {
            return null;
        }

        $sector = $this->model->update(
            $params,
            $sector
        );

        try {
            $stmt = $this->conn
            ->prepare(
                "UPDATE " . self::TABLE . " 
                  set 
                    setor = :sector
                WHERE 
                id = :id
            ");
            $update = $stmt->execute([
                ':sector' => $sector->setor,
                ':id' => $id
            ]);
    
            if (is_null($update)) {
                return null;
            }

            $updated = $this->findById($id);
           
            return $updated;
        } catch (\Throwable $th) {
            LoggerHelper::logInfo($th->getMessage());
            return null;
        } finally {          
            Database::getInstance()->closeConnection();
        }
    }

    public function delete(int $id)
    {
        $stmt = $this->conn
        ->prepare(
            "UPDATE " . self::TABLE . " 
             SET ativo = 0 
             WHERE id = :id"
        );

        $updated = $stmt->execute([':id' => $id]);

        return $updated;
    }

    private function findBySector(string $sector)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM " . self::TABLE . " WHERE setor= :sector LIMIT 1"
            );
            $stmt->execute([':sector' => $sector]);
            $stmt->setFetchMode(\PDO::FETCH_CLASS, self::CLASS_NAME);

            return $stmt->fetch() ?: null;
        } catch (\Throwable $th) {
            LoggerHelper::logInfo($th->getMessage());
            return null;
        } finally {          
            Database::getInstance()->closeConnection();
        }
    }

}