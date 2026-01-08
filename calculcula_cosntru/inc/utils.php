<?php
function nf($n, $dec=2){
  if ($n === null || $n === '') return '';
  return number_format((float)$n, $dec, ',', '.');
}
function clampf($v, $min, $max){
  $v = (float)$v;
  if ($v < $min) return $min;
  if ($v > $max) return $max;
  return $v;
}
function postf($key, $default = 0.0){
  if (!isset($_POST[$key]) || $_POST[$key] === '') return (float)$default;
  // allow comma decimals
  $raw = str_replace(['.', ' '], ['', ''], (string)$_POST[$key]);
  $raw = str_replace(',', '.', $raw);
  return (float)$raw;
}
function posti($key, $default = 0){
  if (!isset($_POST[$key]) || $_POST[$key] === '') return (int)$default;
  return (int)$_POST[$key];
}
function wasteFactor($percent){
  return 1 + ((float)$percent / 100.0);
}
?>