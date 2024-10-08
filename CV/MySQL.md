# ЗАДАЧА

Задача №1: сформировать массив с данными для блока «Опыт работы».
Вывести данные массива в HTML-шаблоне.

Задача №2: создать БД, состоящую из одной таблицы (информация об одногруппниках) с четырьмя полями (добавить поле «Адрес»): id, name, age, address.

## РЕШЕНИЕ

CREATE TABLE job_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255),
    job_data VARCHAR(255),
    job_desc TEXT
);

INSERT INTO job_history (company_name, job_data, job_desc) VALUES
('АО «GNIVC»', 'July 2013', 'Development of web services for taxpayers, layout of web applications, pages, development of layouts, work with corporate identity, implementation of flexible methodologies in the work of tax authorities.'),
('FEDERAL TAX SERVICE OF RUSSIA', 'Mar 2006 - July 2013', 'Interaction with the media, organization of meetings, interviews with management, preparation of press releases, articles, notes, news reports, organization of work of subordinate inspections of the region, development of layouts for advertising.'),
('Opec.ru', 'Jun 2003 - Mar 2005', 'Participation in economic research, preparation of reviews, notes, news for the website, interviews, comments from.');

## ПОДКЛЮЧЕНИЕ К БАЗЕ ДАННЫХ

<?php
// Подключение к базе данных
$db = new SQLite3('cvdatabase.db');

// Проверка соединения
if (!$db) {
    die("Ошибка подключения к базе данных.");
}
$query = "SELECT * FROM job_history";
$result = $db->query($query);

if ($result) {
    while ($row = $result->fetchArray()) {
        echo "<p>Company Name: " . $row['company_name'] . "</p>";
        echo "<p>Job Data: " . $row['job_data'] . "</p>";
        echo "<p>Job Description: " . $row['job_desc'] . "</p>";
    }
} else {
    echo "Ошибка выполнения запроса.";
}

$db->close();
