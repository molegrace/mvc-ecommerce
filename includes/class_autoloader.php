<?php
$CLASS_DIR = __DIR__ . '/../classes/';

// build a lookup table once: lower(filename without .class.php) => actual filename
$CLASS_MAP = [];
foreach (glob($CLASS_DIR . '*.class.php') as $f) {
  $base = basename($f, '.class.php');          // e.g. loginContr
  $CLASS_MAP[strtolower($base)] = $f;          // key: logincontr
}

spl_autoload_register(function ($className) use ($CLASS_MAP) {
  $key = strtolower($className);
  if (isset($CLASS_MAP[$key])) {
    require_once $CLASS_MAP[$key];
    return;
  }
  throw new RuntimeException("Autoload failed for {$className}");
});
