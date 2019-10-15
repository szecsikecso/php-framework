<?php

namespace Homework3\_Framework;


class MySQLOperationProvider implements DataOperationProvider
{

    /**
     * @var \PDO $databaseConnection
     */
    private $databaseConnection;

    /**
     * @var FrameworkEntity $entity
     */
    private $entity;

    /**
     * @var string $className
     */
    private $className;


    public function __construct(FrameworkEntity $entity, string $className)
    {
        $this->entity = $entity;
        $this->className = $className;

        $pdo = new \PDO("mysql:dbname=mydb;host=localhost", "root", "");
        $pdo->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_NATURAL);
        $this->databaseConnection = $pdo;
    }

    public function read(int $id)
    {
        $sql = "SELECT id, " . $this->generateSelectFieldList() . " FROM " . $this->entity->getTableName() .
            " WHERE " . 'id' . "=?" . " LIMIT 1";
        $statement = $this->databaseConnection->prepare($sql);

        $message = $statement->execute([$id]);
        var_dump($statement->errorInfo());
        echo $message . " ";

        return $statement->fetchObject($this->className);
    }

    public function readAll()
    {
        $sql = "SELECT id, " . $this->generateSelectFieldList() . " FROM " . $this->entity->getTableName();
        $statement = $this->databaseConnection->prepare($sql);

        $message = $statement->execute([]);
        var_dump($statement->errorInfo());
        echo $message . " ";

        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->className);
    }

    public function write(FrameworkEntity $entity)
    {
        $sql = "INSERT INTO " . $entity->getTableName() .
            " ( " . $this->generateInsertFieldList() . " ) VALUES (?,?,?,?)";

        $statement = $this->databaseConnection->prepare($sql);
        $message = $statement->execute($entity->getData());
        echo $this->databaseConnection->lastInsertId();

        echo $message . "\n";
    }

    public function modify(FrameworkEntity $entity)
    {
        $sql = "UPDATE " . $entity->getTableName() .
            " SET " . $this->generateUpdateFieldList() . "WHERE id=?";
        $statement = $this->databaseConnection->prepare($sql);

        $data_with_id = array_merge($entity->getData(), [$entity->getId()]);
        $message = $statement->execute($data_with_id);

        var_dump($statement->errorInfo());

        echo $message . "\n";
    }

    public function delete(int $id)
    {
        $sql = "DELETE FROM " . $this->entity->getTableName() . " WHERE id=?";
        $statement = $this->databaseConnection->prepare($sql);

        $message = $statement->execute([$id]);
        var_dump($statement->errorInfo());
        echo $message . " ";
    }

    private function generateSelectFieldList() {
        $field_list = '';
        foreach ($this->entity->getAttributes() as $attributeKey => $attributeValue) {
            $field_list .= $attributeKey;
            if ($attributeKey != $attributeValue) {
                $field_list .= ' AS ' . $attributeValue;
            }
            $field_list .= ', ';
        }
        return rtrim($field_list, ', ');
    }

    private function generateInsertFieldList() {
        return implode(', ', $this->entity->getFields());
    }

    private function generateUpdateFieldList() {
        $update_field_list = implode('=?, ', $this->entity->getFields());
        $update_field_list .= '=? ';
        return $update_field_list;
    }

}
