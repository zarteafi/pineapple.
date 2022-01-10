<?php

namespace App\Controller;

use App\Model\DBCommunication as DBCommunication;

class EmailTableController
{
    public static function act()
    {
        if (isset($_POST['delete'])) {
            DBCommunication::deleteEmail($_POST['delete']);
        }

        if (count($_POST) > 2 ||
            (isset($_POST['toFind']) && $_POST['toFind'] !== '') ||
            (isset($_POST['sort']) && $_POST['sort'] !== 'dasc')) {

            $toFind = htmlspecialchars($_POST['toFind']);
            $sortBy = $_POST['sort'][0] === 'e' ? 'email' : 'date';
            $sortOrder = $_POST['sort'][1] === 'd' ? 'DESC' : 'ASC';
            $filters = $_POST;
            unset($filters['toFind']);
            unset($filters['sort']);
            $data = DBCommunication::getData($toFind, $sortBy, $sortOrder, $filters);
            $isFiltered = true;
        } else {
            $data = DBCommunication::getData();
            $isFiltered = false;
        }

        $emails = $data['emails'];
        $dates = $data['dates'];

        if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
            DBCommunication::deleteEmail($_GET['delete']);
        }

        $domains = [];
        foreach ($emails as $email) {
            preg_match('/(?<=@)[^.]+/', $email, $domain);
            if (isset($domains[$domain[0]])) {
                $domains[$domain[0]]++;
            } else {
                $domains[$domain[0]] = 1;
            }
        }

        $filteredDomains = [];
        if (isset($domains['gmail']) && $domains['gmail'] >= 3 && isset($domains['yahoo']) && $domains['yahoo'] >= 5 && isset($domains['outlook']) && $domains['outlook'] >= 2) {
            foreach ($domains as $domain => $count) {
                if ($count >= 2) {
                    $filteredDomains[] = $domain;
                }
            }
        }
        return [$emails, $dates, $filteredDomains, $isFiltered];
    }
}

