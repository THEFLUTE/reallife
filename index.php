<?php
$id = isset($_GET['id']) ? rtrim(trim($_GET['id']), '/') : 'default';
$f = fopen('redirects.txt', 'r');
$urls = array();

// The file didn't open correctly.
if (!$f) {
    echo 'Asegúrate de crear tu archivo redirects.txt y que sea legible por el script de redirección.';
    die;
}

// Read the input file and parse it into an array
while ($data = fgetcsv($f)) {
    if (!isset($data[0]) || !isset($data[1]))
        continue;

    $key = trim($data[0]);
    $val = trim($data[1]);
    $urls[$key] = $val;
}

// Check if the given ID is set, if it is, set the URL to that, if not, default
$url = (isset($urls[$id])) ? $urls[$id] : (isset($urls['default'])) ? $urls['default'] : false;

if ($url) {
    // Redirigir según el tipo de dispositivo
    $device = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (strpos($device, 'iphone') !== false || strpos($device, 'ipod') !== false || strpos($device, 'ipad') !== false || strpos($device, 'android') !== false) {
        header("X-Robots-Tag: noindex, nofollow", true);
        header("Location: " . $url, 302);
        die;
    } else {
        echo 'Redirección de dispositivo no compatible.';
    }
} else {
    echo '<p>Asegúrate de que tu archivo redirects.txt contenga un valor predeterminado, sintaxis:</p>
    <pre>default,http://example.com</pre>
    <p>Donde debes reemplazar example.com con tu dominio.</p>';
}
?>
