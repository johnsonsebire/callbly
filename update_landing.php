<?php

$file = 'c:\\www\\callbly\\resources\\views\\home.blade.php';
$content = file_get_contents($file);

// Replace CloudVoice with Callbly
$content = str_replace('CloudVoice', 'Callbly', $content);

// Convert <button> tags to <a> tags with appropriate hrefs for Callbly
$content = str_replace(
    '<button class="nav-cta">Start Free Trial</button>',
    '<a href="{{ route(\'register\') }}" class="nav-cta" style="text-decoration:none; display:inline-block; text-align:center;">Start Free Trial</a>',
    $content
);

$content = str_replace(
    '<button class="btn-primary">Start Free for 14 Days</button>',
    '<a href="{{ route(\'register\') }}" class="btn-primary" style="text-decoration:none; display:inline-block; text-align:center;">Start Free</a>',
    $content
);

$content = str_replace(
    '<button class="btn-secondary">Talk to Sales</button>',
    '<a href="{{ route(\'login\') }}" class="btn-secondary" style="text-decoration:none; display:inline-block; text-align:center;">Log In</a>',
    $content
);

file_put_contents($file, $content);
echo "Done replacing.";

