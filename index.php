<?php
ob_start();

require($_SERVER['DOCUMENT_ROOT'] . '/View/template.php');
getHeader('<script defer src="../src/js/index.js"></script>');
require($_SERVER['DOCUMENT_ROOT'] . '/Controller/ServerSideValidation.php');

UserFormValidation::validate()
?>
    <section class="hero">
        <h1 class="hero__heading">Subscribe to newsletter</h1>
        <p class="hero__description">Subscribe to our newsletter and get 10% discount on pineapple glasses.
        </p>
        <form class="hero__form" method="post">

            <div class="form__field">
                <input class="form__input" type="text" name="email" placeholder="Type your email address here…">

                <button class="form__submit" type="submit">
                    <svg width="24" height="14" viewBox="0 0 24 14" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3"
                              d="M17.7071 0.2929C17.3166 -0.0976334 16.6834 -0.0976334 16.2929 0.2929C15.9023 0.683403 15.9023 1.31658 16.2929 1.70708L20.5858 5.99999H1C0.447693 5.99999 0 6.44772 0 6.99999C0 7.55227 0.447693 7.99999 1 7.99999H20.5858L16.2929 12.2929C15.9023 12.6834 15.9023 13.3166 16.2929 13.7071C16.6834 14.0976 17.3166 14.0976 17.7071 13.7071L23.7071 7.70708C24.0977 7.31658 24.0977 6.6834 23.7071 6.2929L17.7071 0.2929Z"
                              fill="#131821"/>
                    </svg>
                </button>
            </div>
            <p class="input__message"> <?= UserFormValidation::getEmailError() ?> </p>

            <input class="form__checkbox" type="checkbox" id="agreement" name="agreement">
            <label class="form__label" for="agreement">I agree to </label>
            <a class="agreement__link" href="">terms of service</a>
            <p class="agreement__message"> <?= UserFormValidation::getAgreementError() ?> </p>
        </form>
    </section>
<?php
getFooter();

