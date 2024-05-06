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
