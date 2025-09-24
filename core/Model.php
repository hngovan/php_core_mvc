<?php

namespace core;

use PDOStatement;

abstract class Model
{
  use Validation;

  protected Database $db;

  public function __construct()
  {
    $this->db = Application::$app->db;
  }

  abstract public static function tableName(): string;

  public function loadData(array $data)
  {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->{$key} = $value;
      }
    }
  }

  public function findOne($where): mixed
  {
    $whereConditions = [];
    $params = [];

    foreach ($where as $key => $value) {
      $whereConditions[] = "$key = :$key";
      $params[":$key"] = $value;
    }

    $whereClause = implode(' AND ', $whereConditions);
    $sql = "SELECT * FROM {$this->tableName()} WHERE $whereClause LIMIT 1";

    $stmt = $this->db->prepare($sql);

    $stmt->execute($params);

    return $stmt->fetchObject(static::class);
  }

  public function findAll($where = []): array
  {
    $sql = "SELECT * FROM {$this->tableName()}";
    $params = [];

    if (!empty($where)) {
      $whereConditions = [];
      foreach ($where as $key => $value) {
        $whereConditions[] = "$key = :$key";
        $params[":$key"] = $value;
      }
      $sql .= " WHERE " . implode(' AND ', $whereConditions);
    }

    $stmt = $this->db->prepare($sql);

    foreach ($params as $key => $value) {
      $stmt->bindValue($key, $value);
    }

    $stmt->execute($params);

    return $stmt->fetchAll();
  }

  public function create(array $data): bool|string
  {
    $columns = implode(', ', array_keys($data));
    $placeholders = ':' . implode(', :', array_keys($data));
    $sql = "INSERT INTO {$this->tableName()} ($columns) VALUES ($placeholders)";
    $stmt = $this->db->prepare($sql);

    foreach ($data as $key => $value) {
      $stmt->bindValue(":$key", $value);
    }

    $stmt->execute();
    return $this->db->lastInsertId();
  }

  public function update($id, array $data): bool
  {
    $setClause = [];
    $params = ['id' => $id];

    foreach ($data as $key => $value) {
      $setClause[] = "$key = :$key";
      $params[":$key"] = $value;
    }

    $sql = "UPDATE {$this->tableName()} SET " . implode(', ', $setClause) . " WHERE id = :id";

    $stmt = $this->db->prepare($sql);

    foreach ($params as $key => $value) {
      $stmt->bindValue($key, $value);
    }

    return $stmt->execute();
  }

  public function delete($id)
  {
    $sql = "DELETE FROM {$this->tableName()} WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute(['id' => $id]);
  }

  public function query($sql, $params = []): bool|PDOStatement
  {
    $stmt = $this->db->prepare($sql);

    foreach ($params as $key => $value) {
      if (is_int($key)) {
        $stmt->bindValue($key + 1, $value);
      } else {
        $stmt->bindValue($key, $value);
      }
    }

    $stmt->execute();
    return $stmt;
  }
}
