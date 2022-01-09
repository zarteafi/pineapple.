<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Model/DBCommunication.php');

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
    $data = DBCommunication::getFilteredEmails($toFind, $sortBy, $sortOrder, $filters);
    $isFiltered = true;
} else {
    $data = DBCommunication::getEmails();
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
        <?php foreach ($filteredDomains as $domain): ?>
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
