<?php
namespace core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
  public PDO $pdo;

  public function __construct(array $config)
  {
    $dsn = $config['dsn'] ?? '';
    $user = $config['user'] ?? '';
    $password = $config['password'] ?? '';

    try {
      $this->pdo = new PDO($dsn, $user, $password);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    } catch (PDOException $e) {
      throw new PDOException("Database connection failed: " . $e->getMessage());
    }
  }

  protected function createMigrationsTable()
  {
    $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=INNODB;");
  }

  protected function getAppliedMigrations(): array
  {
    $statement = self::prepare("SELECT migration FROM migrations");
    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_COLUMN);
  }

  public function applyMigrations()
  {
    $this->createMigrationsTable();
    $appliedMigrations = $this->getAppliedMigrations();

    $newMigrations = [];
    $files = scandir(Application::$ROOT_DIR . '/database/migrations');
    $toApplyMigrations = array_diff($files, $appliedMigrations);
    foreach ($toApplyMigrations as $migration) {
      if ($migration === '.' || $migration === '..') {
        continue;
      }

      require_once Application::$ROOT_DIR . '/database/migrations/' . $migration;
      $className = pathinfo($migration, PATHINFO_FILENAME);
      $instance = new $className();
      $this->log("Applying migration $migration");
      $instance->up();
      $this->log("Applied migration $migration");
      $newMigrations[] = $migration;
    }

    if (!empty($newMigrations)) {
      $this->saveMigrations($newMigrations);
    } else {
      $this->log("There are no migrations to apply");
    }
  }

  protected function saveMigrations(array $newMigrations)
  {
    $str = implode(',', array_map(fn($m) => "('$m')", $newMigrations));
    $statement = self::prepare("INSERT INTO migrations (migration) VALUES 
            $str
        ");
    $statement->execute();
  }

  public function prepare($sql): bool|PDOStatement
  {
    return $this->pdo->prepare($sql);
  }

  public function lastInsertId(): bool|string
  {
    return $this->pdo->lastInsertId();
  }

  private function log($message)
  {
    echo "[" . date("Y-m-d H:i:s") . "] - " . $message . PHP_EOL;
  }
}
