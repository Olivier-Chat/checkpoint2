<?php


namespace App\Model;

class AccessoryManager extends AbstractManager
{
    public const TABLE = 'accessory';

    public function add(array $accessoryProperties): int
    {
        $query = "INSERT INTO " . self::TABLE . " (name, url) VALUES (:name, :url)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('name', $accessoryProperties['name'], \PDO::PARAM_STR);
        $statement->bindValue('url', $accessoryProperties['url'], \PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
    public function showAll(): array
    {
        $query = "SELECT * FROM . self::TABLE";
        $statement = $this->pdo->query($query);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
