<?php

//namespace App\DBCommunication;
//require($_SERVER['DOCUMENT_ROOT'] . '/Model/DBCommunication.php');


class DBCommunication
{

    private const DB_SERVER = "wdt";
    private const DB_USER = "root";
    private const DB_PASSWORD = "";
    private const DB_DATABASE = "pineapple_db";

    private static function connect()
    {
        static $connection = null;

        if ($connection === null) {
            $connection = mysqli_connect(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB_DATABASE) or die('connection error');
        }

        return $connection;
    }

    public static function pushEmail($email)
    {
        $checkedEmail = mysqli_real_escape_string(DBCommunication::connect(), $email);
        mysqli_query(DBCommunication::connect(), "INSERT INTO `pineapple_db`.`subscribed` (`email`) VALUES ('$checkedEmail')");
    }

    public static function getEmails(): array
    {
        $emails = [];
        $dates = [];
        $result = mysqli_query(DBCommunication::connect(), "SELECT `id`,`email`, `date` FROM `pineapple_db`.`subscribed`");

        foreach ($result as $elem) {
            $emails[$elem['id']] = $elem['email'];
            $dates[$elem['id']] = $elem['date'];
        }
        return ['emails' => $emails, 'dates' => $dates];
    }

    public static function getFilteredEmails($toFind, $sortBy, $sortOrder, $filters): array
    {
        $emails = [];
        $dates = [];

        $queryPart = '';

        $query = "
SELECT `id`,`email`, `date`
FROM `pineapple_db`.`subscribed`
#QUERYPART
ORDER BY `$sortBy` $sortOrder;";

        $isToFind = $toFind !== '';
        $isNotEmptyFilters = !empty($filters);

        if ($isToFind || $isNotEmptyFilters) {
            $queryPart = 'where ';

            if ($isToFind) {
                $queryPart .= "`email`  like '%$toFind%'\n";

                if ($isNotEmptyFilters) {
                    $queryPart .= 'AND ';
                }
            }

            if ($isNotEmptyFilters) {
                $currentElement = current($filters);
                $queryPart .= "(`email` LIKE '%$currentElement%' ";
                unset($filters[$currentElement]);

                if (!empty($filters)) {
                    foreach ($filters as $key => $value) {
                        $queryPart .= "OR `email` LIKE '%$key%' ";
                    }
                }
                $queryPart .= ')';
            }
        }

//        if ($toFind !== '') {
//            $search = "where `email`  like '%$toFind%'";
//        } elseif (!empty($filters)) {
//            $search = current($filters);
//            unset($filters[$search]);
//            $search = "where `email`  like '%$search%'";
//        } else {
//            $search = '';
//        }
//
//        if (!empty($filters)) {
//            foreach ($filters as $key => $value) {
//                $filtersString .= "OR `email` LIKE '%$key%'";
//            }
//        } else {
//            $filtersString = '';
//        }
//
//        $query = str_replace('#SEARCH', $search, $query);
//        $query = str_replace('#FILTERS', $filtersString, $query);

        $query = str_replace('#QUERYPART', $queryPart, $query);

        $result = mysqli_query(DBCommunication::connect(), $query);

        foreach ($result as $elem) {
            $emails[$elem['id']] = $elem['email'];
            $dates[$elem['id']] = $elem['date'];
        }
        return ['emails' => $emails, 'dates' => $dates];
    }

    public
    static function deleteEmail($id)
    {
        mysqli_query(DBCommunication::connect(), "DELETE FROM `pineapple_db`.`subscribed` WHERE id = $id");
    }

}
