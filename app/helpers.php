<?php

function preg_grep_keys($pattern, $input, $flags = 0)
{
  $keys = preg_grep($pattern, array_keys($input), $flags);
  $vals = [];
  foreach ($keys as $key) {
    $vals[$key] = $input[$key];
  }

  return $vals;
}

function round_up($value, $places = 0)
{
  if ($places < 0) {
    $places = 0;
  }
  $mult = pow(10, $places);

  return ceil($value * $mult) / $mult;
}

function concat($glue, ...$props)
{
  return implode($glue, array_filter($props));
}

function merge(array $a, ...$b)
{
  return array_merge($a, $b);
}

function getModels($path)
{
  $out = [];
  $results = scandir($path);
  foreach ($results as $result) {
    if ($result === '.' or $result === '..')
      continue;
    $filename = $path.'/'.$result;
    if (is_dir($filename) || $result === 'helpers.php') {
      continue;
    } else {
      $out[] = substr($result, 0, -4);
    }
  }

  return $out;
}
