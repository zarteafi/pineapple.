<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php');

use App\View\Template as Template;

Template::getHeader('<link rel="stylesheet" href="../src/styles/subscribed.min.css">');
?>

    <section class="hero">
        <h1 class="hero__heading">Thanks for subscribing!</h1>
        <p class="hero__description">You have successfully subscribed to our email listing. Check your email for the
            discount code.</p>
    </section>
<?php
Template::getFooter();