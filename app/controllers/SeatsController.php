<?php
$mysqli = new mysqli('localhost', 'root', '', 'MyShop', 3322);
if ($mysqli->connect_error) {
    die("Помилка з'єднання з базою даних: " . $mysqli->connect_error);
}

$plane_id = isset($_GET['plane']) ? intval($_GET['plane']) : 1;

function randomFlightDate($plane_id) {
    $start = strtotime('2025-06-11 00:00:00');
    $end = strtotime('2025-06-30 23:59:59');
    if ($plane_id == 1) {
        $rand_timestamp = mt_rand($start, strtotime('2025-06-20 23:59:59'));
    } elseif ($plane_id == 2) {
        $rand_timestamp = mt_rand(strtotime('2025-06-21 00:00:00'), $end);
    } else {
        $rand_timestamp = mt_rand($start, $end);
    }
    return date('d.m.Y H:i', $rand_timestamp);
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $passport_number = trim($_POST['passport_number'] ?? '');
    $seats_str = trim($_POST['seats'] ?? '');
    $plane_id = intval($_POST['plane_id'] ?? $plane_id);

    if ($first_name === '' || $last_name === '' || $passport_number === '' || $seats_str === '' || $plane_id === 0) {
        $message = "Помилка: не всі необхідні дані передані.";
    } else {
        $name = $mysqli->real_escape_string($first_name . ' ' . $last_name);
        $passport = $mysqli->real_escape_string($passport_number);
        $seats = array_filter(array_map('trim', explode(',', $seats_str)));

        $bookedSeats = [];
        $takenSeats = [];

        foreach ($seats as $seat) {
            $seat = $mysqli->real_escape_string($seat);
            $check = $mysqli->query("SELECT * FROM bookings WHERE plane_id = $plane_id AND seat = '$seat'");
            if ($check->num_rows > 0) {
                $takenSeats[] = $seat;
            } else {
                $insert = $mysqli->query("INSERT INTO bookings (plane_id, seat, name, passport) VALUES ($plane_id, '$seat', '$name', '$passport')");
                if ($insert) {
                    $bookedSeats[] = $seat;
                } else {
                    $message = "Сталася помилка при бронюванні місця $seat: " . $mysqli->error;
                    break;
                }
            }
        }

        if ($takenSeats) {
            $message .= "<strong>Наступні місця вже зайняті:</strong> " . implode(', ', $takenSeats) . ". Будь ласка, оберіть інші.<br>";
        }

        if ($bookedSeats) {
            $flightDate = randomFlightDate($plane_id);
            $message .= "<strong>Бронювання успішне!</strong> Місця: " . implode(', ', $bookedSeats) . ". Дякуємо, $name.<br>";
            $message .= "Дата і час вильоту літака №$plane_id: <b>$flightDate</b>.";
        }
    }
}

$result = $mysqli->query("SELECT seat FROM bookings WHERE plane_id = $plane_id");
$occupied_seats = [];
while ($row = $result->fetch_assoc()) {
    $occupied_seats[] = $row['seat'];
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
<meta charset="UTF-8">
<title>Бронювання місць літака №<?= $plane_id ?></title>
<style>
  body {
    font-family: 'Roboto', sans-serif;
    background: #f5f8fa;
    display: flex;
    justify-content: center;
    padding: 30px;
  }
  .container {
    background: white;
    padding: 30px 40px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    max-width: 600px;
    width: 100%;
  }
  h2 {
    margin-bottom: 20px;
    color: #007BFF;
    text-align: center;
  }
  form label {
    display: block;
    margin-bottom: 15px;
    font-weight: 600;
    color: #333;
  }
  form input[type="text"] {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
  }
  form input[type="text"]:focus {
    border-color: #007BFF;
    outline: none;
  }
  #seats-container {
    margin-top: 25px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
  }
  .seat {
    width: 45px; height: 45px;
    border: 2px solid gray;
    margin: 6px;
    border-radius: 10px;
    line-height: 45px;
    text-align: center;
    cursor: pointer;
    font-weight: 700;
    color: #333;
    user-select: none;
    transition: all 0.3s ease;
    box-shadow: 0 3px 5px rgba(0,0,0,0.1);
  }
  .seat.occupied {
    border-color: #cc0000;
    color: #cc0000;
    cursor: not-allowed;
    background: #fbeaea;
  }
  .seat.selected {
    border-color: #009933;
    color: #009933;
    background: #d9f7d9;
    box-shadow: 0 0 15px #00cc44;
  }
  #seats-container .seat:hover:not(.occupied):not(.selected) {
    border-color: #007BFF;
    color: #007BFF;
    transform: scale(1.15);
  }
  .buttons {
    margin-top: 20px;
    text-align: center;
  }
  button, .back-link {
    background: #007BFF;
    color: white;
    font-weight: 700;
    padding: 12px 30px;
    margin: 10px 12px 0 12px;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 8px 25px rgba(0,123,255,0.5);
    transition: background 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
    text-decoration: none;
    display: inline-block;
  }
  button:hover, .back-link:hover {
    background: #0056b3;
    box-shadow: 0 10px 30px rgba(0,86,179,0.9);
    transform: translateY(-3px);
  }
  .message {
    margin: 15px 0 0 0;
    padding: 15px;
    border-radius: 10px;
    background: #e7f3ff;
    border: 1px solid #007BFF;
    color: #004085;
    font-weight: 600;
    line-height: 1.3;
  }
</style>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const seatsContainer = document.getElementById('seats-container');
    const selectedSeatsInput = document.getElementById('seats-input');
    const occupiedSeats = new Set(<?= json_encode($occupied_seats); ?>);
    const totalSeats = 30;
    const seatLetters = ['A', 'B', 'C', 'D', 'E', 'F'];

    let selectedSeats = [];

    function seatLabel(num) {
      const row = Math.floor((num-1)/6) + 1;
      const col = seatLetters[(num-1)%6];
      return row + col;
    }

    function renderSeats() {
      seatsContainer.innerHTML = '';
      for (let i = 1; i <= totalSeats; i++) {
        const label = seatLabel(i);
        const seatDiv = document.createElement('div');
        seatDiv.classList.add('seat');
        seatDiv.textContent = label;

        if (occupiedSeats.has(label)) {
          seatDiv.classList.add('occupied');
        }

        if (selectedSeats.includes(label)) {
          seatDiv.classList.add('selected');
        }

        seatDiv.addEventListener('click', () => {
          if (seatDiv.classList.contains('occupied')) return;
          if (seatDiv.classList.contains('selected')) {
            selectedSeats = selectedSeats.filter(s => s !== label);
          } else {
            selectedSeats.push(label);
          }
          updateSelectedSeats();
          renderSeats();
        });

        seatsContainer.appendChild(seatDiv);
      }
    }

    function updateSelectedSeats() {
      selectedSeatsInput.value = selectedSeats.join(',');
    }

    renderSeats();

    const form = document.getElementById('booking-form');
    form.addEventListener('submit', (e) => {
      if (selectedSeats.length === 0) {
        e.preventDefault();
        alert('Будь ласка, оберіть хоча б одне місце.');
      }
    });
  });
</script>
</head>
<body>
<div class="container">
  <h2>Бронювання місць літака №<?= $plane_id ?></h2>
  <?php if ($message): ?>
    <div class="message"><?= $message ?></div>
  <?php endif; ?>
  <form method="post" id="booking-form" action="seats.php?plane=<?= $plane_id ?>">
    <label>Ім'я:
      <input type="text" name="first_name" required />
    </label>
    <label>Прізвище:
      <input type="text" name="last_name" required />
    </label>
    <label>Номер паспорта:
      <input type="text" name="passport_number" required />
    </label>
    <label>Оберіть місця:</label>
    <div id="seats-container"></div>
    <input type="hidden" id="seats-input" name="seats" value="" />
    <input type="hidden" name="plane_id" value="<?= $plane_id ?>" />
    <div class="buttons">
      <button type="submit">Прийняти</button>
      <a href="home.php" class="back-link">Назад</a>
    </div>
  </form>
</div>
</body>
</html>
