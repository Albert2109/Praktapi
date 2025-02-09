Документація [тиць](https://documenter.getpostman.com/view/41725631/2sAYX3r3U5)

Щоб запустити проект потрібно виконати такі інструкції

Symfony потребує PHP 8.1+. Якщо PHP не встановлений, його потрібно встановити.


1.Якщо composer не встановлений то його потрібно встановити ось [офіційний сайт](https://getcomposer.org/)

2.Після того як встановили проект потрібно зайти в термінал і прописати таку команду:
```
composer --version
```
3. Перейти  в директорію потрібно виконати таку команду
```
cd "тут має бути шлях де знаходиться проект"
```
4. Встановіть залежності  Composer
```
composer install
```

5. Переконайтесь  що файл autoload_runtime.php існує
```
тут ваш шлях де знаходиться проект\vendor\autoload_runtime.php
```
6. Якщо після виконання ```composer install``` проблема не зникла попробуйте ввести таку команду

```
composer dump-autoload
```
7. Далі потрібно згенерувати  SSL keys потрібно ввести таку команду
```
php bin/console lexik:jwt:generate-keypair

```
8. Після цього можна запускати локальний сервер
   

