<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { 
            font-family: sans-serif; 
            background: #0f3460; 
            color: white; 
            padding: 20px; 
            margin: 0;
        }
        h1 { 
            text-align: center; 
            margin-bottom: 20px; 
        }
        .logout { 
            float: right; 
            color: #ff4d4d; 
            text-decoration: none; 
            font-weight: bold; 
        }
        .filters {
            text-align: center;
            margin-bottom: 20px;
        }
        .filters input, .filters select {
            padding: 10px;
            margin: 5px;
            border-radius: 6px;
            border: none;
            font-size: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #16213e;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #1a1a2e;
            text-align: center;
        }
        th { 
            background: #0f3460; 
        }
        tr:hover { 
            background: #1a1a2e; 
        }
        .add-plane {
            display: inline-block;
            background: #00aa55;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .add-plane:hover {
            background: #008844;
        }
    </style>
</head>
<body>
    <a href="/logout" class="logout">Выход</a>
    <h1>Панель управления</h1>

    <div class="filters">
        <input type="text" id="search" placeholder="Поиск по рейсам...">
        <select id="sort">
            <option value="">Сортировать по...</option>
            <option value="date_asc">Дата ↑</option>
            <option value="date_desc">Дата ↓</option>
            <option value="price_asc">Цена ↑</option>
            <option value="price_desc">Цена ↓</option>
        </select>
    </div>

    <a href="/admin/add-plane" class="add-plane">Добавить рейс</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Откуда</th>
            <th>Куда</th>
            <th>Цена</th>
            <th>Дата вылета</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($planes as $id => $plane): ?>
        <tr>
            <td><?= $id ?></td>
            <td><?= htmlspecialchars($plane['from']) ?></td>
            <td><?= htmlspecialchars($plane['to']) ?></td>
            <td><?= number_format($plane['price'], 0, ',', ' ') ?> грн</td>
            <td><?= htmlspecialchars($plane['flight_date']) ?></td>
            <td>
                <a href="/admin/edit-plane/<?= $id ?>" style="color: #00aa55;">Редактировать</a>
                |
                <a href="/admin/delete-plane/<?= $id ?>" style="color: #ff4d4d;" 
                   onclick="return confirm('Вы уверены?')">Удалить</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <script>
        // Здесь будет JavaScript для фильтрации и сортировки
        document.getElementById('search').addEventListener('input', function(e) {
            // Реализация поиска
        });

        document.getElementById('sort').addEventListener('change', function(e) {
            // Реализация сортировки
        });
    </script>
</body>
</html> 