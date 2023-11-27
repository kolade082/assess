<?php
namespace CSY;
class DatabaseTable
{

    private $table;
    private $pdo;
    private $primaryKey;

    public function __construct($pdo, $table, $primaryKey)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }
    public function find($field, $value)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE ' . $field . ' = :value');
        $values = [
            'value' => $value
        ];
        $stmt->execute($values);
        return $stmt->fetchAll();
    }
    public function delete($field, $value): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM ' . $this->table . ' WHERE ' . $field . ' = :value');
        $values = [
            'value' => $value
        ];
        $stmt->execute($values);
    }

    public function findAll()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function insert($record): void
    {
        $keys = array_keys($record);
        $values = implode(', ', $keys);
        $valuesWithColon = implode(', :', $keys);
        $query = 'INSERT INTO ' . $this->table . ' (' . $values . ') VALUES (:' . $valuesWithColon . ')';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($record);
    }
    public function update($record): void
    {
        $query = 'UPDATE ' . $this->table . ' SET ';
        $parameters = [];
        foreach ($record as $key => $value) {
            $parameters[] = $key . ' = :' . $key;
        }
        $query .= implode(', ', $parameters);
        $query .= ' WHERE ' . $this->primaryKey . ' = :primaryKey';
        $record['primaryKey'] = $record[$this->primaryKey];
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($record);
    }

    public function customFind($statement, $criteria){
        $stmt = $this->pdo->prepare($statement);
        $stmt->execute($criteria);
        return $stmt->fetchAll();
    }

}