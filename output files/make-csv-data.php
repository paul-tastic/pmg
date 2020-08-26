<?php

$fp = fopen('input.csv', 'w');

$header = ["email_hash", "category"];
fputcsv($fp, $header);

for ($i=0; $i < 40000000; $i++) {
    $line = [hash('sha256', $i), substr(str_shuffle("qwer tyuiopasdfghjklzxcvbnm"),0,10)];
        fputcsv($fp, $line);
}
fclose($fp);