<?php
namespace core;

class Session
{
  protected const string FLASH_KEY = 'flash_messages';

  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
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

  public function has(string $key): bool
  {
    return isset($_SESSION[$key]);
  }

  public function remove(string $key)
  {
    if ($this->has($key)) {
      unset($_SESSION[$key]);
    }
  }

  public function setFlash(string $key, $message): void
  {
    $_SESSION[self::FLASH_KEY][$key] = $message;
  }

  public function getFlash(string $key)
  {
    $flash = $_SESSION[self::FLASH_KEY][$key] ?? null;
    // after taking flash, clear it to show only once
    if (isset($_SESSION[self::FLASH_KEY][$key])) {
      unset($_SESSION[self::FLASH_KEY][$key]);
    }
    return $flash;
  }
}
