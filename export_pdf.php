<?php
$jsonFile = __DIR__ . '/data.json';
$raw = file_get_contents($jsonFile);
$data = json_decode($raw, true);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Экспорт в PDF</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; font-size: 12pt; }
            .cat-image { max-width: 300px !important; }
        }
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px;
            background: white;
        }
        h1 { 
            color: #333; 
            text-align: center; 
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
        }
        th, td { 
            border: 1px solid #333; 
            padding: 10px; 
            text-align: left; 
        }
        th { 
            background-color: #f4f4f4; 
            font-weight: bold;
        }
        .footer { 
            margin-top: 30px; 
            text-align: center; 
            color: #666; 
            font-size: 10pt;
        }
        .button { 
            display: inline-block; 
            padding: 10px 20px; 
            background: #1976d2; 
            color: white; 
            text-decoration: none; 
            border-radius: 4px; 
            margin: 10px;
            border: none;
            cursor: pointer;
        }
        .cat-container {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
        }
        .cat-image {
            max-width: 400px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 3px solid #ff6b6b;
        }
        .cat-title {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            color: #ff6b6b;
            font-size: 24px;
            margin-bottom: 15px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .cat-subtitle {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            color: #4ecdc4;
            font-size: 18px;
            margin-top: 10px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button class="button" onclick="window.print()">🖨️ Печать / Сохранить как PDF</button>
        <a class="button" href="index.php">← Назад</a>
    </div>

    <h1>Список студентов группы ИС-235.1</h1>
    
    <?php if (empty($data)): ?>
        <p>Данных нет.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Группа</th>
                    <th>Порядковый номер</th>
                    <th>ФИО</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['group'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['index'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['fio'] ?? '') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Котовый блок с изображением -->
    <div class="cat-container">
        <div class="cat-title">🐱 Мур-мур момент! 🐱</div>
        <img src="https://i.ytimg.com/vi/OKx9mbcSkw4/maxresdefault.jpg" 
             alt="Милый котик" 
             class="cat-image">
        <div class="cat-subtitle">Этот котик следит за вашей успеваемостью!</div>
    </div>
    
    <div class="footer">
        <p>Сгенерировано: <?= date('d.m.Y H:i:s') ?></p>
        <p>Всего записей: <?= count($data) ?></p>
    </div>

    <script>
        // Автоматически открываем диалог печати
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>