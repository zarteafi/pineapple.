<?php
include($_SERVER['DOCUMENT_ROOT'] . '/php/template.php');
getHeader('<link rel="stylesheet" href="styles/subscribed.min.css">');
?>

    <section class="hero">
        <h1 class="hero__heading">Thanks for subscribing!</h1>
        <p class="hero__description">You have successfully subscribed to our email listing. Check your email for the
            discount code.</p>
    </section>
<?php
getFooter();