<?php
$mysqli = new mysqli('localhost', 'root', '', 'MyShop', 3322);
if ($mysqli->connect_error) {
    die("Помилка з'єднання: " . $mysqli->connect_error);
}

$plane_id = 1; // Можна змінити
$result = $mysqli->query("SELECT seat_number, is_booked FROM seats WHERE plane_id = $plane_id");
$seats = [];
while ($row = $result->fetch_assoc()) {
    $seats[$row['seat_number']] = $row['is_booked'];
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Вибір місць</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #101820;
            color: #fff;
            text-align: center;
            padding: 40px;
        }
        .plane {
            display: inline-block;
            background: #1c1c1c;
            padding: 30px;
            border-radius: 12px;
        }
        .row {
            display: flex;
            justify-content: center;
            margin: 10px 0;
        }
        .seat {
            width: 50px;
            height: 50px;
            margin: 0 6px;
            border-radius: 50%;
            line-height: 50px;
            font-weight: bold;
            color: black;
            background: white;
            border: 3px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
        }
        .seat.taken {
            border: 3px solid red;
            color: red;
            cursor: not-allowed;
        }
        .seat.selected {
            border: 3px solid limegreen;
            color: green;
        }
        .buttons {
            margin-top: 20px;
        }
        button {
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            margin: 0 10px;
            cursor: pointer;
        }
        .confirm {
            background-color: #28a745;
            color: white;
        }
        .back {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>

<h1>Виберіть місця у літаку №<?php echo $plane_id; ?></h1>
<div class="plane">
    <?php
    $rows = ['A', 'B', 'C', 'D', 'E'];
    $cols = 6;
    foreach ($rows as $r) {
        echo "<div class='row'>";
        for ($i = 1; $i <= $cols; $i++) {
            $seat = $r . $i;
            $taken = isset($seats[$seat]) && $seats[$seat] ? 'taken' : '';
            echo "<div class='seat $taken' data-seat='$seat'>$seat</div>";
        }
        echo "</div>";
    }
    ?>
</div>

<div class="buttons">
    <button class="confirm" id="confirmBtn" disabled>Прийняти</button>
    <button class="back" onclick="history.back()">Назад</button>
</div>

<form method="POST" action="book_seat.php" id="form" style="display: none;">
    <input type="hidden" name="plane_id" value="<?php echo $plane_id; ?>">
    <input type="hidden" name="seats" id="seatsInput">
    <input type="hidden" name="first_name" id="first_name">
    <input type="hidden" name="last_name" id="last_name">
    <input type="hidden" name="passport_number" id="passport_number">
</form>

<script>
    const selectedSeats = [];
    const seats = document.querySelectorAll('.seat:not(.taken)');
    const confirmBtn = document.getElementById('confirmBtn');

    seats.forEach(seat => {
        seat.addEventListener('click', () => {
            const seatNumber = seat.dataset.seat;
            if (seat.classList.contains('selected')) {
                seat.classList.remove('selected');
                const index = selectedSeats.indexOf(seatNumber);
                if (index > -1) selectedSeats.splice(index, 1);
            } else {
                seat.classList.add('selected');
                selectedSeats.push(seatNumber);
            }
            confirmBtn.disabled = selectedSeats.length === 0;
        });
    });

    confirmBtn.addEventListener('click', () => {
        const fname = prompt("Введіть ім'я:");
        if (!fname) return alert("Ім’я обов’язкове");
        const lname = prompt("Введіть прізвище:");
        if (!lname) return alert("Прізвище обов’язкове");
        const pass = prompt("Введіть номер паспорта:");
        if (!pass) return alert("Паспорт обов’язковий");

        document.getElementById('first_name').value = fname;
        document.getElementById('last_name').value = lname;
        document.getElementById('passport_number').value = pass;
        document.getElementById('seatsInput').value = selectedSeats.join(',');
        document.getElementById('form').submit();
    });
</script>

</body>
</html>
