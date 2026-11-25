<?php
header('Content-Type: application/json; charset=utf-8');

$clientDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'Client';
$files = scandir($clientDirectory);

if ($files === false) {
    http_response_code(500);
    echo json_encode(array('error' => 'Unable to read the client logo directory.'));
    exit;
}

$allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'svg');
$logos = array();

foreach ($files as $file) {
    $filePath = $clientDirectory . DIRECTORY_SEPARATOR . $file;
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if (!is_file($filePath) || !in_array($extension, $allowedExtensions, true)) {
        continue;
    }

    $name = pathinfo($file, PATHINFO_FILENAME);
    $name = trim(preg_replace('/[-_]+/', ' ', $name));

    $logos[] = array(
        'src' => 'img/Client/' . rawurlencode($file),
        'alt' => ($name === '' ? 'Client' : $name) . ' logo'
    );
}

usort($logos, function ($left, $right) {
    return strnatcasecmp($left['src'], $right['src']);
});

$response = json_encode($logos, JSON_UNESCAPED_SLASHES);
if ($response === false) {
    http_response_code(500);
    echo json_encode(array('error' => 'Unable to encode the client logo list.'));
    exit;
}

echo $response;
