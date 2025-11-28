<?php
$jsonFile = __DIR__ . '/data.json';
$raw = file_get_contents($jsonFile);
$data = json_decode($raw, true);

echo "<pre>";
echo "JSON ошибка: " . json_last_error_msg() . "\n";
echo "Данные:\n";
print_r($data);
echo "Количество записей: " . count($data) . "\n";
echo "</pre>";
?>