<?php
namespace utils;

class Validator {
    public static function validateRoute($fromCity, $toCity) {
        if (empty($fromCity) || empty($toCity)) {
            throw new \Exception("Міста відправлення та призначення обов'язкові");
        }

        if ($fromCity === $toCity) {
            throw new \Exception("Місто відправлення та призначення не можуть бути однаковими");
        }

        // Перевіряємо, що міста містять тільки літери та пробіли
        if (!preg_match('/^[a-zA-Zа-яА-ЯіІїЇєЄґҐ\s]+$/u', $fromCity) || 
            !preg_match('/^[a-zA-Zа-яА-ЯіІїЇєЄґҐ\s]+$/u', $toCity)) {
            throw new \Exception("Назви міст можуть містити лише літери та пробіли");
        }

        return true;
    }

    public static function validateDistance($distance) {
        if (!is_numeric($distance)) {
            throw new \Exception("Відстань має бути числом");
        }

        $distance = (float)$distance;
        
        if ($distance <= 0) {
            throw new \Exception("Відстань має бути більше 0");
        }

        if ($distance > 20000) { // Максимальна відстань між містами на Землі
            throw new \Exception("Некоректна відстань між містами");
        }

        return true;
    }

    public static function validatePrice($price) {
        if (!is_numeric($price)) {
            throw new \Exception("Ціна має бути числом");
        }

        $price = (float)$price;

        if ($price <= 0) {
            throw new \Exception("Ціна має бути більше 0");
        }

        if ($price > 100000) { // Максимальна допустима ціна
            throw new \Exception("Ціна перевищує допустимий ліміт");
        }

        return true;
    }

    public static function validateDate($date) {
        if (empty($date)) {
            throw new \Exception("Дата обов'язкова");
        }

        $timestamp = strtotime($date);
        
        if ($timestamp === false) {
            throw new \Exception("Некоректний формат дати");
        }

        $currentTime = time();
        $oneYearFromNow = strtotime('+1 year');

        if ($timestamp < $currentTime) {
            throw new \Exception("Дата не може бути в минулому");
        }

        if ($timestamp > $oneYearFromNow) {
            throw new \Exception("Дата не може бути більше ніж через рік");
        }

        return true;
    }

    public static function validateSeats($seats) {
        if (!is_numeric($seats)) {
            throw new \Exception("Кількість місць має бути числом");
        }

        $seats = (int)$seats;

        if ($seats <= 0) {
            throw new \Exception("Кількість місць має бути більше 0");
        }

        if ($seats > 853) { // Максимальна кількість місць в найбільшому пасажирському літаку (A380)
            throw new \Exception("Некоректна кількість місць");
        }

        return true;
    }
} 