# Библиотека, иллюстрирующая некоторые возможности ООП.

## Описание

Этот код был написан в качестве решения к тестовому заданию одной из компаний.
Однако задумка показалась мне интересной, и я еще немного расширил необходимый
функционал. В этом небольшом проекте не использованы никакие библиотеки, кроме
composer, для формирования неймспейсов, т.к. с ними куда удобнее.
Pull Request'ы, кстати, принимаются.

## Требования

1. Данные для сохранения FirstName, LastName, Email
2. Пользователь библиотеки может выбрать куда сохранить данные, в MySQL таблицу, либо в файл (формат файла на Ваше усмотрение).
3. Пользователь может получить FirstName, LastName по email
4. Пользователь может получить все записи либо из таблицы, либо из файла, либо из двух источников сразу.
5. Проверку на дубликаты можно опустить.

## Реализация

1. Готово.
2. Разработано три варианта сохранения: в json-файл, sqlite-базу и mysql-базу. Если как-то дойдет руки - прикручу mongo и nedb.
3. Поиск производится по любой базе. В левой колонке указываются чекбоксы, откуда брать данные.
4. Достаточно выбрать чекбоксы хранилищ, и отправить форму при пустом email
5. Проверку не сделал.

**Перед запуском выполнить `composer update`**

P.S. С версткой у меня так себе, посему вьюха может вызвать боль у верстальщиков.

Created by Rikosage, for great justice.