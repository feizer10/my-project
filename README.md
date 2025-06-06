# Flight Booking System

A web application for booking flights and managing seats.

## Project Structure

```
/
├── public/           # Публичные файлы
│   ├── index.php    # Точка входа
│   ├── assets/      # Статические файлы
│   │   ├── css/     # Стили
│   │   ├── js/      # JavaScript
│   │   └── images/  # Изображения
├── app/             # Основная логика приложения
│   ├── controllers/ # Контроллеры
│   ├── models/      # Модели
│   ├── views/       # Представления
│   └── config/      # Конфигурационные файлы
├── includes/        # Общие включаемые файлы
└── vendor/         # Зависимости
```

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite enabled

## Installation

1. Clone the repository
2. Configure your web server to point to the `public` directory
3. Copy `app/config/database.example.php` to `app/config/database.php` and update the credentials
4. Import the database schema from `database/schema.sql`

## Development

The application follows MVC architecture pattern:
- Models: Handle data and business logic
- Views: Handle the display of data
- Controllers: Handle user input and application flow #   m y - p r o j e c t  
 