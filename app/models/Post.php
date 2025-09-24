<?php

namespace app\models;

use core\Application;
use core\Model;

class Post extends Model
{
  public int $id = 0;
  public string $title = '';
  public string $content = '';
  public ?string $image = null;
  public int|string|null $reading_time = 0;
  public int $status = 1;
  public int $created_by = 0;
  public ?string $created_at = null;
  public ?string $updated_at = null;

  // For form validation
  public string $confirm_delete = '';

  public static function tableName(): string
  {
    return 'posts';
  }

  public static function primaryKey(): string
  {
    return 'id';
  }

  public function rules(): array
  {
    return [
      'title' => [
        self::RULE_REQUIRED,
        [
          'class' => self::class
        ],
        [self::RULE_MIN, 'min' => 5],
        [self::RULE_MAX, 'max' => 255]
      ],
      'content' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 20]],
      'reading_time' => [self::RULE_INTEGER, [self::RULE_MIN, 'min' => 1], [self::RULE_MAX, 'max' => 30]],
      'image' => [], // Optional
    ];
  }

  public function save(): bool|string
  {
    $this->created_by = Application::$app->user->id;

    $data = [
      'title' => $this->title,
      'content' => $this->content,
      'reading_time' => $this->reading_time,
      'created_by' => $this->created_by,
    ];

    if (!empty($this->image) && is_string($this->image)) {
      $data['image'] = $this->image;
    }

    if ($this->id > 0) {
      // Update
      return $this->update($this->id, $data);
    } else {
      // Create
      $result = $this->create($data);
      if ($result) {
        $this->id = (int) $result;
      }
      return $result;
    }
  }

  public function deletePost(): bool
  {
    if ($this->image) {
      $imagePath = Application::$ROOT_DIR . '/public/uploads/' . $this->image;
      if (file_exists($imagePath)) {
        unlink($imagePath);
      }
    }

    if ($this->id > 0) {
      return parent::delete($this->id);
    }
    return false;
  }

  public function uploadImage($file): bool|string
  {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
      return false;
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = mime_content_type($file['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
      return false;
    }

    // check size file (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
      return false;
    }

    // Create unique file names
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = uniqid() . '_' . time() . '.' . $fileExtension;

    // File save path
    $uploadDir = Application::$ROOT_DIR . '/public/uploads/';

    // Create directory if it does not exist
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0755, true);
    }

    $uploadPath = $uploadDir . $fileName;

    // Move files from temp to uploads folder
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
      $this->image = $fileName;
      return $fileName;
    }

    return false;
  }

  public function getAuthor()
  {
    $sql = "SELECT first_name, last_name FROM users WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $this->created_by]);
    return $stmt->fetch();
  }

  public function getAuthorName(): string
  {
    $author = $this->getAuthor();
    return $author ? $author->first_name . ' ' . $author->last_name : 'Unknown Author';
  }


  public function getExcerpt(int $length = 100): string
  {
    $content = strip_tags($this->content);
    if (strlen($content) <= $length) {
      return $content;
    }
    return substr($content, 0, $length) . '...';
  }

  public function getImageUrl(): string
  {
    if ($this->image) {
      return '/uploads/' . $this->image;
    }
    return 'https://placehold.co/600x400';
  }

  public function isActive(): bool
  {
    return $this->status == 1;
  }

  public function canEdit(): bool
  {
    return Application::$app->user->id == $this->created_by;
  }
}
