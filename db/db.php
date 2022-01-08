<?php

const DB_SERVER = "wdt";
const DB_USER = "root";
const DB_PASSWORD = "";
const DB_DATABASE = "pineapple_db";

function connect()
{
    static $connection = null;

    if (null === $connection) {
        $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die('connection error');
    }

    return $connection;
}

function pushEmail($email)
{
    $checkedEmail = mysqli_real_escape_string(connect(), $email);
    mysqli_query(connect(), "INSERT INTO `pineapple_db`.`subscribed` (`email`) VALUES ('$checkedEmail')");
}

function getEmails()
{
    $emails = [];
    $dates = [];
    $result = mysqli_query(connect(), "SELECT `id`,`email`, `date` FROM `pineapple_db`.`subscribed`");

    foreach ($result as $elem) {
        $emails[$elem['id']] = $elem['email'];
        $dates[$elem['id']] = $elem['date'];
    }
    return ['emails' => $emails, 'dates' => $dates];
}

function getFilteredEmails($toFind, $sortBy, $sortOrder, $filters)
{
    $emails = [];
    $dates = [];

    $filtersString = '';

    $search = empty($filters) ? '' : "where `email`  like '%$toFind%'";

    if ($toFind === '') {
        $toFind = current($filters);
        unset($filters[$toFind]);
    }

    foreach ($filters as $key => $value) {
        $filtersString .= "OR `email` LIKE '%$key%'";
    }

    $query = "
SELECT `id`,`email`, `date`
FROM `pineapple_db`.`subscribed`
#SEARCH
#FILTERS
ORDER BY `$sortBy` $sortOrder; ";

    $query = str_replace('#SEARCH', $search, $query);
    $query = str_replace('#FILTERS', $filtersString, $query);

    $result = mysqli_query(connect(), $query);

    foreach ($result as $elem) {
        $emails[$elem['id']] = $elem['email'];
        $dates[$elem['id']] = $elem['date'];
    }
    return ['emails' => $emails, 'dates' => $dates];
}

function deleteEmail($id)
{
    mysqli_query(connect(), "DELETE FROM `pineapple_db`.`subscribed` WHERE id = $id");
}
