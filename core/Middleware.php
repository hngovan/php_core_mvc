<?php
namespace core;

abstract class Middleware
{
  abstract public function execute(): void;
}
