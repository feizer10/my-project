<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1350&q=80') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .container {
            background-color: rgba(0,0,0,0.75);
            padding: 50px 70px;
            border-radius: 20px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.8);
            text-align: center;
            max-width: 600px;
            animation: fadeIn 1.5s ease forwards;
        }

        h1 {
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 2.8rem;
            text-shadow: 0 0 10px #00aaff;
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .btn {
            background: linear-gradient(45deg, #007BFF, #00d4ff);
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            padding: 14px 38px;
            border-radius: 12px;
            display: inline-block;
            box-shadow: 0 6px 20px rgba(0,123,255,0.6);
            transition: 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(45deg, #0056b3, #00a9cc);
            box-shadow: 0 8px 25px rgba(0,86,179,0.9);
            transform: translateY(-3px);
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(15px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= $title ?></h1>
        <p><?= $description ?></p>
        <a href="/route" class="btn">Поиск маршрута</a>
    </div>
</body>
</html> 