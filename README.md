# Auto

## 📥 Клонування проєкту

```sh
git clone https://github.com/litecoding/auto.git
cd auto
```

## 🐳 Встановлення Docker
Якщо Docker ще не встановлений, скачайте і встановіть його:
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## 🚀 Запуск контейнерів

```sh
docker-compose up -d
```
Ця команда запустить усі необхідні контейнери у фоновому режимі.

## 📦 Встановлення залежностей

```sh
docker-compose exec php composer install
```

## 🛠️ Накатка міграцій
Якщо використовується Doctrine, виконайте:
```sh
docker-compose exec php bin/console doctrine:migrations:migrate
```

## 📥 Імпорт CSV даних

Після розгортання проєкту виконайте команду для імпорту CSV:

```sh
docker-compose exec php bin/console app:import-csv
```

## ⚡ Очистка кешу

```sh
docker-compose exec php bin/console cache:clear
```

## 🔥 Перевірка роботи
Відкрийте в браузері:
```
http://localhost:8080
```

Готово! 🎉 Якщо виникнуть проблеми — звертайтеся! 🚀

