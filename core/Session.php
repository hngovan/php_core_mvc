<?php
namespace core;

class Session
{
  protected const string FLASH_KEY = 'flash_messages';

  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
      $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
      foreach ($flashMessages as $key => &$flashMessage) {
        $flashMessage['remove'] = true;
      }
      $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
  }

  public function set(string $key, $value): void
  {
    $_SESSION[$key] = $value;
  }

  public function get(string $key, $default = null)
  {
    return $_SESSION[$key] ?? $default;
  }

  public function remove(string $key)
  {
    unset($_SESSION[$key]);
  }

  public function setFlash(string $key, $message): void
  {
    $_SESSION[self::FLASH_KEY][$key] = [
      'remove' => false,
      'value' => $message
    ];
  }

  public function getFlash(string $key)
  {
    return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
  }

  private function removeFlashMessages()
  {
    $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
    foreach ($flashMessages as $key => $flashMessage) {
      if ($flashMessage['remove']) {
        unset($flashMessages[$key]);
      }
    }
    $_SESSION[self::FLASH_KEY] = $flashMessages;
  }

  public function __destruct()
  {
    $this->removeFlashMessages();
  }
}
