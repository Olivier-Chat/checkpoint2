<?php


namespace App\Model;

class CupcakeManager extends AbstractManager
{
    public const TABLE = 'cupcake';
    public const SELECT_QUERY = '
                SELECT c.id AS "cupcake_id", c.name AS "cupcake_name", c.color1, c.color2, c.color3, c.created_at, 
                a.id AS  "accessory_id" , a.name AS  "accessory_name", a.url 
                FROM cupcake c
                JOIN accessory a ON c.accessory_id = a.id';

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
        $query = self::SELECT_QUERY;
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        $cupcakes =  $this->pdo->query($query)->fetchAll();
        foreach ($cupcakes as $cupcake) {
            $cupcake['cupcake_id'] = (int)$cupcake['cupcake_id'];
            $cupcake['accessory_id'] = (int)$cupcake['accessory_id'];
        }
        return $cupcakes;
    }
    public function selectOneById(int $id)
    {
        $query = self::SELECT_QUERY . " WHERE c.id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }
}
