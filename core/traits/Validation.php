<?php

namespace core\traits;

use core\Application;

trait Validation
{
  public const string RULE_REQUIRED = 'required';
  public const string RULE_EMAIL = 'email';
  public const string RULE_STRING = 'string';
  public const string RULE_NUMERIC = 'numeric';
  public const string RULE_INTEGER = 'integer';
  public const string RULE_MIN = 'min';
  public const string RULE_MAX = 'max';
  public const string RULE_MATCH = 'match';
  public const string RULE_UNIQUE = 'unique';

  protected array $errors = [];

  public function rules()
  {
    return [];
  }

  public function validate(): bool
  {
    $this->errors = [];
    foreach ($this->rules() as $attribute => $rules) {
      $value = $this->{$attribute} ?? null;

      foreach ($rules as $rule) {
        $ruleName = $rule;
        $params = [];

        if (is_array($rule)) {
          $ruleName = $rule[0];
          $params = array_slice($rule, 1);
        }
        if ($ruleName === self::RULE_REQUIRED && !$value) {
          $this->addErrorByRule($attribute, self::RULE_REQUIRED);
        }
        if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $this->addErrorByRule($attribute, self::RULE_EMAIL);
        }
        if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
          $this->addErrorByRule($attribute, self::RULE_MIN, ['min' => $rule['min']]);
        }
        if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
          $this->addErrorByRule($attribute, self::RULE_MAX);
        }
        if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
          $this->addErrorByRule($attribute, self::RULE_MATCH, ['match' => $rule['match']]);
        }
        if ($ruleName === self::RULE_UNIQUE) {
          $className = $rule['class'];
          $uniqueAttr = $rule['attribute'] ?? $attribute;
          $tableName = $className::tableName();
          $db = Application::$app->db;
          // $statement = $db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :$uniqueAttr");
          // $statement->bindValue(":$uniqueAttr", $value);
          // $statement->execute();
          // $record = $statement->fetchObject();
          // if ($record) {
          //   $this->addErrorByRule($attribute, self::RULE_UNIQUE);
          // }
        }
      }
    }

    return empty($this->errors);
  }

  public function errorMessages(): array
  {
    return [
      self::RULE_REQUIRED => '{field} is required',
      self::RULE_EMAIL => '{field} must be a valid email address',
      self::RULE_STRING => '{field} must be a string',
      self::RULE_MIN => '{field} must be at least {min} characters',
      self::RULE_MAX => '{field} must not exceed {max} characters',
      self::RULE_MATCH => 'This field must be the same as {match}',
      self::RULE_UNIQUE => 'Record with with this {field} already exists',
      self::RULE_NUMERIC => '{field} must be a number',
      self::RULE_INTEGER => '{field} must be an integer',
    ];
  }

  public function errorMessage($rule)
  {
    return $this->errorMessages()[$rule] ?? 'Field is invalid';
  }

  public function addErrorByRule(string $attribute, string $rule, array $params = [])
  {
    $params['field'] ??= $attribute;
    $errorMessage = $this->errorMessage($rule);

    foreach ($params as $key => $value) {
      $errorMessage = str_replace("{{$key}}", $value, $errorMessage);
    }

    $this->errors[$attribute][] = $errorMessage;
  }

  public function addError(string $attribute, string $message)
  {
    $this->errors[$attribute][] = $message;
  }

  protected function generateLabel(string $attribute): string
  {
    return ucwords(str_replace(['_', '-'], ' ', $attribute));
  }

  public function hasError(string $attribute): bool
  {
    return isset($this->errors[$attribute]);
  }

  public function getFirstError(string $attribute): string
  {
    return $this->errors[$attribute][0] ?? '';
  }

  public function getErrors(): array
  {
    return $this->errors;
  }

  public function clearErrors()
  {
    $this->errors = [];
  }

  // ========== VALIDATION RULES ========== //

  protected function validateRequired($value, string $attribute): bool
  {
    if (empty($value)) {
      $this->addErrorByRule($attribute, self::RULE_REQUIRED);
      return false;
    }
    return true;
  }

  protected function validateEmail($value, string $attribute): bool
  {
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
      $this->addErrorByRule($attribute, self::RULE_EMAIL);
      return false;
    }
    return true;
  }

  protected function validateString($value, string $attribute): bool
  {
    if (!is_string($value)) {
      $this->addErrorByRule($attribute, self::RULE_STRING);
      return false;
    }
    return true;
  }

  protected function validateMin($value, string $attribute, int $min): bool
  {
    if (strlen($value) < $min) {
      $this->addErrorByRule($attribute, self::RULE_MIN, [self::RULE_MIN => $min]);
      return false;
    }
    return true;
  }

  protected function validateMax($value, string $attribute, int $max): bool
  {
    if (strlen($value) > $max) {
      $this->addErrorByRule($attribute, self::RULE_MAX, [self::RULE_MAX => $max]);
      return false;
    }
    return true;
  }

  protected function validateMatch($value, string $attribute, string $matchAttribute): bool
  {
    if ($value !== $this->{$matchAttribute}) {
      $this->addErrorByRule($attribute, self::RULE_MATCH, [self::RULE_MATCH => $this->getAttributeLabel($matchAttribute)]);
      return false;
    }
    return true;
  }

  protected function validateUnique($value, string $attribute, string $className, string $column = null): bool
  {
    $column = $column ?? $attribute;
    $tableName = $className::tableName();

    $sql = "SELECT COUNT(*) as count FROM {$tableName} WHERE {$column} = :value";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['value' => $value]);
    $result = $stmt->fetch();

    if ($result->count > 0) {
      $this->addErrorByRule($attribute, self::RULE_UNIQUE);
      return false;
    }
    return true;
  }
}
