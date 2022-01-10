<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php');

use App\Controller\EmailTableController as EmailTableController;

$result = EmailTableController::act();
$emails = $result[0];
$dates = $result[1];
$filteredDomains = $result[2];
$isFiltered = $result[3];

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Subscribed emails</title>
</head>
<body>
<form id="form" method="post">
    <table id="table" border="1" cellspacing="0">
        <input id="search" name="toFind"/>
        <br/>
        <?php
        foreach ($filteredDomains as $domain): ?>
            <input type="checkbox" id="<?= $domain ?>" name="<?= $domain ?>">
            <label for="<?= $domain ?>"><?= $domain ?></label>
        <?php endforeach; ?>
        <button type="submit"><?= $isFiltered ? 'reset/load' : 'load' ?></button>
        <tr>
            <th>Email
                <input type="radio" id="easc" name="sort" value="easc">
                <label for="easc">asc</label>
                <input type="radio" id="edesc" name="sort" value="edesc">
                <label for="edesc">desc</label>
            </th>
            <th>Date
                <input type="radio" id="dasc" name="sort" value="dasc" checked>
                <label for="dasc">asc</label>
                <input type="radio" id="ddesc" name="sort" value="ddesc">
                <label for="ddesc">desc</label>
            </th>
            <th>Delete</th>
        </tr>
        <?php foreach ($emails as $id => $email): ?>

            <tr>
                <td><?= $email ?></td>
                <td><?= $dates[$id] ?></td>
                <td><input type="checkbox" name="delete" value="<?= $id ?>"</td>
            </tr>

        <?php endforeach; ?>

    </table>
</form>

</body>
</html>
