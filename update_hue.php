<?php

$file = 'c:\\www\\callbly\\resources\\views\\home.blade.php';
$content = file_get_contents($file);

// Replace 165 with 250 in all oklch() colors to match Callbly logo
$content = preg_replace('/(oklch\([^)]*)\b165\b([^)]*\))/', '${1}250$2', $content);

file_put_contents($file, $content);
echo "Replaced hues.";

