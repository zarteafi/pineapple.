<?php
include($_SERVER['DOCUMENT_ROOT'] . '/db/db.php');
$data = getEmails();
$emails = $data['emails'];
$dates = $data['dates'];


if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    deleteEmail($_GET['delete']);
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
    <script defer src="js/email_table.js"></script>
</head>
<body>
<form id="form" method="get">
    <table id="table">
        <input id="search" onkeyup="searchEmail()"/>
        <tr>
            <th>Email
                <button onclick="sortBy(0)">sort</button>
                <?php foreach ($filteredDomains as $domain): ?>
<!--                    <button onclick="return filter('</?= $domain ?>//')"><\?= $domain ?></button>-->

                    <label for="<?= $domain ?>"><?= $domain ?></label>
                    <input type="checkbox" id="<?= $domain ?>" name="<?= $domain ?>">
                <?php endforeach; ?>
            </th>
            <th>Date
                <button onclick="sortBy(1)">sort</button>
            </th>
            <th>Delete</th>
        </tr>
        <?php foreach ($emails as $id => $email): ?>

            <tr>
                <td><?= $email ?></td>
                <td><?= $dates[$id] ?></td>
                <td><input type="checkbox" name="delete" value="<?= $id ?>"
                           onchange="submit()"/></td>
            </tr>

        <?php endforeach; ?>

    </table>
</form>

</body>
</html>
