<?php
require '../function/functions.php';
require '../config/dbc.php';

if (isset($_POST['type'])) {
    $_POST['page'] = $_SERVER['HTTP_REFERER'];
    date_default_timezone_set("Asia/Karachi");
    switch ($_POST['type']) {
        case 'contactForm':
            contactForm($_POST, $connection);
            break;
        case 'formlongsiteMain':
            nameEmailPhoneBookMessageForm($_POST, $connection);
            break;
        case 'modalForm':
            modalForm($_POST, $connection);
            break;
        case 'modalFormPopUp':
            nameEmailPhoneBookMessageForm($_POST, $connection);
            break;
        case 'formShort':
            nameEmailForm($_POST, $connection);
            break;
        case 'emailForm':
            emailForm($_POST, $connection);
            break;
        case 'servicesForm':
            servicesEmailForm($_POST, $connection);
            break;
        case 'blogFooterForm':
            blogFooterForm($_POST, $connection);
            break;
        case 'bookWritingForm':
            bookWritingServicesForm($_POST, $connection);
            break;
        case 'packageForm':
            packageForm($_POST, $connection);
            break;
        case 'questionnaireForm':
            questionnaireForm($_POST, $connection);
            break;
        case 'questionnaireFormTwo':
            questionnaireFormTwo($_POST, $connection);
            break;
        case 'questionnaireFormThree':
            questionnaireFormThree($_POST, $connection);
            break;
        case 'bookWriting':
            bookWriting($_POST, $connection);
            break;
        case 'publishingJourney':
            publishingJourney($_POST, $connection);
            break;
        // case 'jobPositionForm':
        //     jobPositionForm($_POST, $connection);
        //     break;
    }
    if (!isset($_POST['no_redirect'])) {
        header('Location: /thank-you.php');
    }
}
?>