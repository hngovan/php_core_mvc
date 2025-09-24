<?php
use core\Application;

class m0003_create_posts_table
{
  public function up()
  {
    $db = Application::$app->db;
    $SQL = "CREATE TABLE posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            reading_time INT DEFAULT 5,
            status TINYINT DEFAULT 1,
            created_by INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=INNODB;";

    $db->pdo->exec($SQL);
  }

  public function down()
  {
    $db = Application::$app->db;
    $SQL = "DROP TABLE IF EXISTS posts;";

    $db->pdo->exec($SQL);
  }
}
