<?php

$file = 'people.csv';
file_put_contents($file, 'Иванов Иван Иванович,1985-05-15,г. Москва, ул. Ленина, д. 1,+7 (999) 123-45-67,ivanov@example.com');
echo file_get_contents($file);