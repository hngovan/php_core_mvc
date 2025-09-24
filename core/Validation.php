<?php

namespace core;

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

  public function rules(): array
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
        $ruleParams = [];

        if (is_array($rule)) {
          $ruleName = $rule[0] ?? null;
          $ruleParams = $rule;
        }

        if (!$ruleName) {
          continue;
        }

        if ($ruleName === self::RULE_REQUIRED) {
          if (!$this->validateRequired($value, $attribute)) {
            break;
          }
          continue;
        }

        switch ($ruleName) {
          case self::RULE_EMAIL:
            if (!$this->validateEmail($value, $attribute)) {
              break 2;
            }
            break;

          case self::RULE_STRING:
            if (!$this->validateString($value, $attribute)) {
              break 2;
            }
            break;

          case self::RULE_NUMERIC:
            if (!$this->validateNumeric($value, $attribute)) {
              break 2;
            }
            break;

          case self::RULE_INTEGER:
            if (!$this->validateInteger($value, $attribute)) {
              break 2;
            }
            break;

          case self::RULE_MIN:
            $min = $ruleParams['min'] ?? null;
            if ($min === null || !$this->validateMin($value, $attribute, $min)) {
              break 2;
            }
            break;

          case self::RULE_MAX:
            $max = $ruleParams['max'] ?? null;
            if ($max === null || !$this->validateMax($value, $attribute, $max)) {
              break 2;
            }
            break;

          case self::RULE_MATCH:
            $matchAttribute = $ruleParams['match'] ?? null;
            if ($matchAttribute === null || !$this->validateMatch($value, $attribute, $matchAttribute)) {
              break 2;
            }
            break;

          case self::RULE_UNIQUE:
            $className = $ruleParams['class'] ?? null;
            $uniqueAttr = $ruleParams['attribute'] ?? $attribute;
            if ($className === null || !$this->validateUnique($value, $attribute, $className::tableName(), $uniqueAttr)) {
              break 2;
            }
            break;
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
      self::RULE_MIN => 'Min length of this {field} must be {min}',
      self::RULE_MAX => 'Max length of this {field} must be {max}',
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
    if (is_numeric($value)) {
      if ($value < $min) {
        $this->addErrorByRule($attribute, self::RULE_MIN, ['min' => $min]);
        return false;
      }
    } else if (is_string($value)) {
      if (strlen($value) < $min) {
        $this->addErrorByRule($attribute, self::RULE_MIN, ['min' => $min]);
        return false;
      }
    } else if (is_array($value)) {
      if (count($value) < $min) {
        $this->addErrorByRule($attribute, self::RULE_MIN, ['min' => $min]);
        return false;
      }
    }
    return true;
  }

  protected function validateMax($value, string $attribute, int $max): bool
  {
    if (is_numeric($value)) {
      if ($value > $max) {
        $this->addErrorByRule($attribute, self::RULE_MAX, ['max' => $max]);
        return false;
      }
    } else if (is_string($value)) {
      if (strlen($value) > $max) {
        $this->addErrorByRule($attribute, self::RULE_MAX, ['max' => $max]);
        return false;
      }
    } else if (is_array($value)) {
      if (count($value) > $max) {
        $this->addErrorByRule($attribute, self::RULE_MAX, ['max' => $max]);
        return false;
      }
    }

    return true;
  }

  protected function validateMatch($value, string $attribute, string $matchAttribute): bool
  {
    if ($value !== $this->{$matchAttribute}) {
      $this->addErrorByRule($attribute, self::RULE_MATCH, ['match' => $this->getAttributeLabel($matchAttribute)]);
      return false;
    }
    return true;
  }

  protected function validateUnique($value, string $attribute, string $table, string $column = null): bool
  {
    $column = $column ?? $attribute;
    $db = Application::$app->db;

    $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = :value";
    $stmt = $db->prepare($sql);
    $stmt->execute(['value' => $value]);
    $result = $stmt->fetch();

    if ($result && $result->count > 0) {
      $this->addErrorByRule($attribute, self::RULE_UNIQUE);
      return false;
    }
    return true;
  }

  protected function validateInteger($value, string $attribute): bool
  {
    if (!is_numeric($value) || !is_int((int) $value)) {
      $this->addErrorByRule($attribute, self::RULE_INTEGER);
      return false;
    }
    return true;
  }

  protected function validateNumeric($value, string $attribute): bool
  {
    if (!is_numeric($value)) {
      $this->addErrorByRule($attribute, self::RULE_NUMERIC);
      return false;
    }
    return true;
  }
}
