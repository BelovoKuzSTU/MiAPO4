<?php
declare(strict_types=1);

$jsonFile = __DIR__ . '/data.json';
if (!file_exists($jsonFile)) {
    http_response_code(500);
    echo 'Файл data.json не найден';
    exit;
}
$raw = file_get_contents($jsonFile);
$data = json_decode($raw, true);
if (!is_array($data)) {
    http_response_code(500);
    echo 'Некорректный JSON';
    exit;
}

// Ожидаемые поля
$columns = [
    'group' => 'Номер группы',
    'index' => 'Порядковый номер',
    'fio'   => 'ФИО'
];

// Экранирование
function h(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Студенты гр. ИС-235.1</title>
  <style>
    body { font-family: Arial, sans-serif; margin:20px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border:1px solid #ddd; padding:8px; }
    th { background:#f4f4f4; text-align:left; }
    tr:nth-child(even) { background:#fafafa; }
    .toolbar { margin-bottom:12px; }
    .btn { display:inline-block; padding:8px 12px; background:#1976d2; color:#fff; text-decoration:none; border-radius:4px; }
    .btn:hover { background:#125a9c; }
  </style>
</head>
<body>
  <h1>Таблица: группа, номер, ФИО</h1>
  <div class="toolbar">
    <a class="btn" href="export_pdf.php">Экспорт в PDF</a>
  </div>

  <?php if (empty($data)): ?>
    <p>Данных нет.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <?php foreach ($columns as $key => $title): ?>
            <th><?= h($title) ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $row): ?>
          <tr>
            <td><?= h($row['group'] ?? '') ?></td>
            <td><?= h(isset($row['index']) ? (string)$row['index'] : '') ?></td>
            <td><?= h($row['fio'] ?? '') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</body>
</html>