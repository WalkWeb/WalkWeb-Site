<?php

$this->title = APP_NAME . ' — Функционал проекта';

?>

<h1><?= $this->title ?></h1>

<p>Пояснение по цветовому выделению:</p>

<ul>
    <li><span class="red">красный</span> — функционал не готов</li>
    <li><span class="green">зеленый</span> — функционал готов</li>
    <li><span class="orange">оранжевый</span> — функционал есть, но требует доработок</li>
</ul>

<div class="line_box"><div class="line_row"><div class="line_left"></div><div class="line_right"></div><div class="line_center">&nbsp;</div></div></div>


<h2>Пользователи</h2>

<ul>
    <li><span class="orange">Регистрация</span></li>
    <li><span class="orange">Подтверждение email</span></li>
    <li><span class="red">Восстановление пароля</span></li>
    <li><span class="red">Изменение пароля</span></li>
    <li><span class="green">Авторизация</span></li>
    <li><span class="green">Умная авторизация (с переадресацией на изначальную страницу)</span></li>
    <li><span class="green">Выход</span></li>
    <li><span class="green">Энергия</span></li>
    <li><span class="green">Уровень</span></li>
    <li><span class="green">Карма</span></li>
    <li><span class="orange">Уведомления</span></li>
    <li><span class="green">Страница пользователя</span></li>
    <li><span class="red">Установка своего аватара (для высокоуровневых пользователей)</span></li>
    <li><span class="red">Установка статуса (для высокоуровневых пользователей)</span></li>
    <li><span class="red">Смена отображаемого имени (для высокоуровневых пользователей)</span></li>
</ul>

<h2>Личный кабинет</h2>

<ul>
    <li><span class="green">Профиль</span></li>
    <li><span class="red">Функционал увеличения энергии</span></li>
    <li><span class="red">Функционал увеличения места на диске</span></li>
    <li><span class="red">Список своих постов</span></li>
    <li><span class="red">Список своих комментариев</span></li>
    <li><span class="green">Список событий</span></li>
    <li><span class="red">Страница настроек</span></li>
</ul>

<h2>Посты</h2>

<ul>
    <li><span class="orange">Список постов на главной, фильтрация по рейтингу</span></li>
    <li><span class="green">Просмотр поста</span></li>
    <li><span class="green">Добавление поста</span></li>
    <li><span class="red">Редактирование поста</span></li>
    <li><span class="green">Изменение рейтинга чужих постов</span></li>
    <li><span class="red">Повышение статуса поста при получении определенного количества лайков и получение дополнительного опыта автору поста</span></li>
    <li><span class="red">Увеличение функционала при создании постов с ростом уровня аккаунта</span></li>
    <li><span class="red">Черновик</span></li>
    <li><span class="red">Создание поста на основе чистого html</span></li>
    <li><span class="red">Добавление уведомления автору посту при добавлении комментария его посту. Группировка таких уведомлений</span></li>
    <li><span class="red">Голосования в постах</span></li>
    <li><span class="red">Загрузка сразу нескольких картинок</span></li>
    <li><span class="red">Добавление ссылок</span></li>
    <li><span class="red">Выделение текста (курсив, жирный, зачеркнутый)</span></li>
    <li><span class="red">Цитаты</span></li>
    <li><span class="red">Вложения</span></li>
    <li><span class="red">Таблицы</span></li>
</ul>

<h2>Теги</h2>

<ul>
    <li><span class="orange">Добавление постам тегов</span></li>
    <li><span class="orange">Страница просмотра постов по определенному тегу</span></li>
    <li><span class="red">Поиск по тегам</span></li>
</ul>

<h2>Комментарии</h2>

<ul>
    <li><span class="orange">Просмотр комментариев в посте</span></li>
    <li><span class="red"><i>На подумать: несколько вариантов вывода постов: деревом, списком</i></span></li>
    <li><span class="orange">Добавление комментария</span></li>
    <li><span class="red">Редактирование комментария</span></li>
    <li><span class="green">Изменение рейтинга чужих комментариев</span></li>
    <li><span class="red">Уведомление автору комментария при добавлении ответа на его комментарий</span></li>
    <li><span class="red">Прикрепление к комментарию изображение</span></li>
</ul>

<h2>Сообщества</h2>

<ul>
    <li><span class="orange">Просмотр списка сообществ</span></li>
    <li><span class="orange">Просмотр сообщества</span></li>
    <li><span class="red">Просмотр участников сообщества</span></li>
    <li><span class="red">Создание сообщества</span></li>
    <li><span class="green">Подписка/отписка на сообщество</span></li>
    <li><span class="red">Привязка постов к сообществу</span></li>
    <li><span class="red">Отвязка поста от сообщества (для владельца сообщества)</span></li>
    <li><span class="red">Авторское сообщество: тип сообществ, публиковать посты в который могут только его создатели</span></li>
    <li><span class="red">Получение особой валюты создателю сообщества при новых подписчиках</span></li>
    <li><span class="red">Повышение уровня сообщества, новые возможности с новыми уровнями</span></li>
    <li><span class="red">Черный список пользователей (заблокированных) у сообщества</span></li>
</ul>

<h2>База языков программирования</h2>

<ul>
    <li><span class="red">Просмотр списка языков программирования</span></li>
    <li><span class="red">Страница языка программирования</span></li>
    <li><span class="red">Добавление языка программирования</span></li>
    <li><span class="red">Отметка языка программирования любимым</span></li>
    <li><span class="red">График выходов языков</span></li>
    <li><span class="red">Поиск по языкам</span></li>
</ul>

<h2>Рейтинги</h2>

<ul>
    <li><span class="orange">Рейтинг пользователей по уровню аккаунта</span></li>
    <li><span class="green">Рейтинг пользователей по карме</span></li>
    <li><span class="green">Рейтинг профессий</span></li>
    <li><span class="red">Рейтинг языков программирования</span></li>
    <li><span class="red">Рейтинг сообществ</span></li>
</ul>

<h2>Чат</h2>

<p><i>Пока под вопросом</i></p>

<h2>Прочее</h2>

<ul>
    <li><span class="orange">Возможность менять шаблон сайта</span></li>
    <li><span class="orange">Общая статистика портала</span></li>
    <li><span class="red">Поиск по постам</span></li>
    <li><span class="red">Поиск по языкам программирования</span></li>
    <li><span class="red">Поиск по сообществам</span></li>
    <li><span class="red">Адаптивная верстка для мобильных устройств</span></li>
    <li><span class="red">Добавить мультиязычность</span></li>
</ul>

<h2>Админка</h2>

<ul>
    <li><span class="red">Функционал бана пользователей</span></li>
    <li><span class="red">Функционал бана пользователя с опцией «твинк» — в этом случае все лайки с такого поста будут обнулены</span></li>
    <li><span class="red">Функционал создания новой эры (обнуление всех рейтингов)</span></li>
    <li><span class="red">Одобрение тегов и установка для них иконок</span></li>
    <li><span class="red">Одобрение пользовательских аватаров</span></li>
    <li><span class="red">Страница для просмотра новых, не проверенных постов и комментариев, с кнопкой их одобрения или бана пользователя</span></li>
</ul>

<h2>Панель модератора</h2>

<ul>
    <li><span class="red">Страница для просмотра новых, не проверенных постов и комментариев, с кнопкой их одобрения или бана пользователя</span></li>
</ul>

<h2>Безопасность</h2>

<ul>
    <li><span class="red">Защита от перебора паролей</span></li>
    <li><span class="red">Защита от перебора авторизационного токена</span></li>
    <li><span class="red">Защита от регистрации с временных почтовых ящиков</span></li>
    <li><span class="green">Защита от csrf-атак</span></li>
    <li><span class="red">Функционал для выявления твинко-аккаунтов</span></li>
    <li><span class="red">Закрытие тяжелых страниц только для авторизованных пользователей</span></li>
    <li><span class="red">Защита от спам-запросов</span></li>
</ul>
