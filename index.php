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
    'fio'   => 'ФИО',
    'ide'   => 'IDE'
];

// Экранирование
function h(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Функция для форматирования значения
function formatValue($value): string
{
    if ($value === null || $value === '') {
        return '';
    }
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Определяем режим работы
$isExport = isset($_GET['export']) && $_GET['export'] === 'pdf';

?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $isExport ? 'Экспорт в PDF' : 'Студенты гр. ИС-235.1' ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="<?= $isExport ? 'print-mode' : '' ?>">
  <?php if ($isExport): ?>
    <div class="no-print" style="text-align: center; margin: 20px;">
        <button class="btn btn-print" onclick="window.print()">
            <span>🖨️</span> Печать / Сохранить как PDF
        </button>
        <a class="btn btn-secondary" href="?">
            <span>←</span> Назад к таблице
        </a>
    </div>

    <div class="container">
        <div class="header">
            <h1>Список студентов</h1>
            <p>Группа ИС-235.1 - Информация о студентах и используемых IDE</p>
        </div>
        
        <div class="content">
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-icon">👥</div>
                    <div>Всего студентов: <strong><?= count($data) ?></strong></div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">📅</div>
                    <div>Сгенерировано: <strong><?= date('d.m.Y H:i:s') ?></strong></div>
                </div>
            </div>

            <?php if (empty($data)): ?>
                <div class="no-data">
                    <div class="icon">📭</div>
                    <p>Данных нет</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Группа</th>
                                <th>Порядковый номер</th>
                                <th>ФИО</th>
                                <th>IDE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $row): ?>
                            <tr>
                                <td><?= formatValue($row['group'] ?? '') ?></td>
                                <td><?= formatValue($row['index'] ?? '') ?></td>
                                <td><?= formatValue($row['fio'] ?? '') ?></td>
                                <td><?= formatValue($row['ide'] ?? '') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            
            <div class="footer">
                <p>Отчёт сгенерирован автоматически • Группа ИС-235.1</p>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1000);
        };
    </script>

  <?php else: ?>
    <div class="container">
        <div class="header">
            <h1>Список студентов</h1>
            <p>Группа ИС-235.1 - Информация о студентах и используемых IDE</p>
        </div>
        
        <div class="content">
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-icon">👥</div>
                    <div>Всего студентов: <strong><?= count($data) ?></strong></div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">💻</div>
                    <div>Доступные IDE: <strong><?= count(array_unique(array_column($data, 'ide'))) ?></strong></div>
                </div>
            </div>

            <div class="toolbar">
                <a class="btn" href="?export=pdf">
                    <span>📄</span> Экспорт в PDF
                </a>
                <button class="btn btn-secondary" onclick="location.reload()">
                    <span>🔄</span> Обновить данные
                </button>
            </div>

            <?php if (empty($data)): ?>
                <div class="no-data">
                    <div class="icon">📭</div>
                    <p>Нет данных для отображения</p>
                    <p style="margin-top: 10px; font-size: 0.9rem;">Проверьте файл data.json</p>
                </div>
            <?php else: ?>
                <div class="table-container">
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
                                    <td><?= formatValue($row['group'] ?? '') ?></td>
                                    <td><?= formatValue($row['index'] ?? '') ?></td>
                                    <td><?= formatValue($row['fio'] ?? '') ?></td>
                                    <td><?= formatValue($row['ide'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            
            <div class="footer">
                <p>Система управления студентами • Группа ИС-235.1 • <?= date('Y') ?></p>
            </div>
        </div>
    </div>
  <?php endif; ?>
</body>
</html>