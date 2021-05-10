<?php


namespace App\Model;

class CupcakeManager extends AbstractManager
{
    public const TABLE = 'cupcake';

    public function add(array $cupcakeProperties): int
    {
        $query = "INSERT INTO " . self::TABLE . " (name, color1, color2, color3, accessory_id, created_at)
                VALUES (:name, :color1, :color2, :color3, :accessory_id ,NOW())";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('name', $cupcakeProperties['name'], \PDO::PARAM_STR);
        $statement->bindValue('color1', $cupcakeProperties['color1'], \PDO::PARAM_STR);
        $statement->bindValue('color2', $cupcakeProperties['color2'], \PDO::PARAM_STR);
        $statement->bindValue('color3', $cupcakeProperties['color3'], \PDO::PARAM_STR);
        $statement->bindValue('accessory_id', $cupcakeProperties['accessory'], \PDO::PARAM_INT);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
    public function selectAll(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT c.id, c.name AS "cupcake_name", c.color1, c.color2, c.color3, c.created_at, a.name 
                AS  "accessory_name" FROM cupcake c';
        $query .= ' JOIN accessory a ON c.accessory_id = a.id';
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll();
    }
}
