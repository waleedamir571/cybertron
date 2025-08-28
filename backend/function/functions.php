<?php
//require_once('../PHPMailer/class.phpmailer.php');
//require_once('../PHPMailer/class.smtp.php');
// require_once('../PHPMailer/mail_test.php');
// require '../mailchimp-api-master/src/MailChimp.php';

require '../config/dbc.php';

require '../vendor/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/src/SMTP.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function contactForm($data, $connection)
{
    // Required fields
    $name = isset($data['full_name']) ? $connection->real_escape_string($data['full_name']) : '';
    $email = isset($data['email']) ? $connection->real_escape_string($data['email']) : '';
    $phone = isset($data['phone_no']) ? $connection->real_escape_string($data['phone_no']) : '';
    $project_details = isset($data['project_details']) ? $connection->real_escape_string($data['project_details']) : '';

    // Additional fields for contact form
    $region = isset($data['region']) ? $connection->real_escape_string($data['region']) : '';
    $company_name = isset($data['company_name']) ? $connection->real_escape_string($data['company_name']) : '';
    $budget = isset($data['budget']) ? $connection->real_escape_string($data['budget']) : '';
    $page = isset($data['page']) ? $connection->real_escape_string($data['page']) : '';
    $created_at = date("Y-m-d H:i:s");

    // Services handling
    $services = isset($data['services']) ? implode(", ", array_map([$connection, 'real_escape_string'], $data['services'])) : '';

    // File upload handling
    $document_path = '';
    $video_path = '';

    // Handle document upload
    if (isset($_FILES['project_document']) && $_FILES['project_document']['error'] == 0) {
        $document_path = handleFileUpload($_FILES['project_document'], 'documents');
    }

    // Handle video upload
    if (isset($_FILES['project_video']) && $_FILES['project_video']['error'] == 0) {
        $video_path = handleFileUpload($_FILES['project_video'], 'videos');
    }

    // Store into DB
    $query = "INSERT INTO contact_form (full_name, email, phone_no, project_details, services, region, company_name, budget, document_path, video_path, created_at)
              VALUES ('$name', '$email', '$phone', '$project_details', '$services', '$region', '$company_name', '$budget', '$document_path', '$video_path', '$created_at')";
    $connection->query($query);

    // Email Content for Admin only
    $adminSubject = "New Contact Form Submission from $name";
    $adminBody = "
    <h2>New Contact Form Submission</h2>
    <p><strong>Page:</strong> $page</p>
    <p><strong>Name:</strong> $name</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Phone:</strong> $phone</p>
    <p><strong>Company:</strong> $company_name</p>
    <p><strong>Region:</strong> $region</p>
    <p><strong>Budget:</strong> $budget</p>
    <p><strong>Services Required:</strong> $services</p>
    <p><strong>Project Details:</strong><br>$project_details</p>";

    if ($document_path) {
        $adminBody .= "<p><strong>Document:</strong> <a href='https://cybertronlabs.com/uploads/$document_path'>View Document</a></p>";
    }

    if ($video_path) {
        $adminBody .= "<p><strong>Video:</strong> <a href='https://cybertronlabs.com/uploads/$video_path'>View Video</a></p>";
    }

    // Send only admin email
    sendAdminEmail($adminSubject, $adminBody);
}

function handleFileUpload($file, $folder)
{
    $uploadDir = '../uploads/' . $folder . '/';

    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Get file extension
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Generate unique filename
    $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
    $uploadPath = $uploadDir . $fileName;

    // Define allowed extensions
    $allowedExtensions = [
        'documents' => ['pdf', 'doc', 'docx'],
        'videos' => ['mp4', 'mov', 'avi', 'wmv']
    ];

    // Check if file extension is allowed
    if (!in_array($fileExtension, $allowedExtensions[$folder])) {
        return false;
    }

    // Check file size (max 10MB for documents, 50MB for videos)
    $maxSize = ($folder == 'documents') ? 10 * 1024 * 1024 : 50 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        return false;
    }

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return $folder . '/' . $fileName;
    }

    return false;
}

function nameEmailPhoneMessageForm($data, $connection)
{
    // Existing fields
    $name = isset($data['full_name']) ? $connection->real_escape_string($data['full_name']) : '';
    $email = isset($data['email']) ? $connection->real_escape_string($data['email']) : '';
    $phone = isset($data['phone_no']) ? $connection->real_escape_string($data['phone_no']) : '';
    $message = isset($data['message']) ? $connection->real_escape_string($data['message']) : '';
    $plan_name = isset($data['plan_name']) ? $connection->real_escape_string($data['plan_name']) : '';
    $plan_amount = isset($data['plan_amount']) ? $connection->real_escape_string($data['plan_amount']) : '';
    $page = isset($data['page']) ? $connection->real_escape_string($data['page']) : '';
    $created_at = date("Y-m-d H:i:s");

    // ✅ New fields
    $services = isset($data['services']) ? implode(", ", array_map([$connection, 'real_escape_string'], $data['services'])) : '';
    $manuscript = isset($data['manuscript']) ? $connection->real_escape_string($data['manuscript']) : '';
    $genre = isset($data['genre']) ? $connection->real_escape_string($data['genre']) : '';

    // Store into DB (add new columns if your table supports them)
    $query = "INSERT INTO form (full_name, email, phone_no, message, plan_name, plan_amount, services, manuscript, genre, created_at)
              VALUES ('$name', '$email', '$phone', '$message', '$plan_name','$plan_amount', '$services', '$manuscript', '$genre', '$created_at')";
    $connection->query($query);

    // Email Content for Admin (added new fields)
    $adminSubject = "New Form Submission from $name";
    $adminBody = "
    <p><strong>Page:</strong> $page</p>
    <p><strong>Name:</strong> $name</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Phone:</strong> $phone</p>
    <p><strong>Message:</strong><br>$message</p>
    <p><strong>Plan Name:</strong><br>$plan_name</p>
    <p><strong>Plan Amount:</strong><br>$plan_amount</p>
    <p><strong>Services:</strong> $services</p>
    <p><strong>Manuscript:</strong> $manuscript</p>
    <p><strong>Genre:</strong> $genre</p>
    ";

    $userSubject = 'Donaldsbookpublisher has Received Your Message!';
    $userBody = "
        <p>Dear <b>{$name},</b></p>
        <p>Thank you for reaching out to Donaldsbookpublisher! Our team has received your query and is working on it. Expect to hear back from us within 24 hours. For quick answers, you might find our <a href='https://donaldsbookpublisher.com/faq'>FAQ</a> page helpful.</p>
        <p>Thanks for your patience and interest in our work!</p>
        <br><br>
        <p><b>Warm regards,</b></p>
        <p>The Donaldsbookpublisher Team.</p>
        <img src='https://donaldsbookpublisher.com/assets/img/logo.png' alt='Donaldsbookpublisher' style='max-width: 200px;'>
        <p>Email: <a href='mailto:no-reply@donaldsbookpublishing.com'>no-reply@donaldsbookpublishing.com</a><br>
        Phone: <a href='tel:(415) 520-1098'>(415) 520-1098</a><br>
        Address: 895 Dove Street. Newport Beach, CA 92660 United States</p>
    ";

    sendEmails($name, $email, $adminSubject, $adminBody, $userSubject, $userBody);

    $slackContent = json_encode([
        "text" => "Hi Team,\nWe have received a new lead.\n\nPage: $page\nName: $name\nEmail: $email\nPhone: $phone\nMessage: $message\nPlan Name: $plan_name\nAmount: $plan_amount\nServices: $services\nManuscript: $manuscript\nGenre: $genre"
    ]);
    sendSlack($slackContent);
}


function sendEmails($name, $email, $adminSubject, $adminBody, $userSubject, $userBody)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com'; // Replace with your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'no-reply@donaldsbookpublishing.com';
        $mail->Password = 'Cybertron@2025';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('no-reply@donaldsbookpublishing.com', 'donaldsbookpublishing');

        // Admin email
        $mail->addAddress('no-reply@donaldsbookpublishing.com', 'Admin');
        $mail->isHTML(true);
        $mail->Subject = $adminSubject;
        $mail->Body = $adminBody;
        $mail->send();

        // Auto-reply to user
        $mail->clearAddresses();
        $mail->addAddress($email, $name);
        $mail->Subject = $userSubject;
        $mail->Body = $userBody;
        $mail->send();

        // echo 'Emails sent successfully.';

    } catch (Exception $e) {
        echo "Email error: {$mail->ErrorInfo}";
    }
}

function sendAdminEmail($adminSubject, $adminBody)
{
    $mail = new PHPMailer(true);
    try {
        // Enable verbose debug output
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';

        $mail->isSMTP();
        $mail->Host = 'smtp.cybertronlabs.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'contact@cybertronlabs.com';
        $mail->Password = 'Cybertron@2025';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('contact@cybertronlabs.com', 'CybertronLabs');

        // Admin email only
        $mail->addAddress('contact@cybertronlabs.com', 'Admin');
        $mail->isHTML(true);
        $mail->Subject = $adminSubject;
        $mail->Body = $adminBody;

        $result = $mail->send();

        if ($result) {
            echo "Email sent successfully!";
        } else {
            echo "Email failed to send.";
        }

    } catch (Exception $e) {
        echo "Email error: " . $mail->ErrorInfo;
        echo "<br>Exception: " . $e->getMessage();
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        error_log("Exception: " . $e->getMessage());
    }
}

function bpsPage($payload, $con)
{
    if (empty($payload['name']) || empty($payload['phone']) || empty($payload['email']) || empty($payload['message'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");

            $name = mysqli_real_escape_string($con, $payload['name']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            $phone = mysqli_real_escape_string($con, $payload['phone']);
            $message = mysqli_real_escape_string($con, clean($payload['message']));
            mysqli_query($con, "INSERT INTO leads(page,date,name,email,phone,message) VALUES('$page','$date','$name','$email','$phone','$message')") or die(mysqli_error($con));


            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Name : ' . $payload['name'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            $emailContent .= '<p>Phone : ' . $payload['phone'] . '</p>';
            $emailContent .= '<p>Message : ' . $message . '</p>';

            sendEmail($emailContent);

            // SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n Name : " . $payload['name'] . " \n Email : " . $payload['email'] . " \n Phone : " . $payload['phone'] . " \n Message : " . $message));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

function bookps($payload, $con)
{
    if (empty($payload['name']) || empty($payload['phone']) || empty($payload['email']) || empty($payload['message'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");

            $name = mysqli_real_escape_string($con, $payload['name']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            $phone = mysqli_real_escape_string($con, $payload['phone']);
            $message = mysqli_real_escape_string($con, clean($payload['message']));
            mysqli_query($con, "INSERT INTO leads(page,date,name,email,phone,message) VALUES('$page','$date','$name','$email','$phone','$message')") or die(mysqli_error($con));


            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Name : ' . $payload['name'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            $emailContent .= '<p>Phone : ' . $payload['phone'] . '</p>';
            $emailContent .= '<p>Message : ' . $message . '</p>';

            sendEmail($emailContent);

            // SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n Name : " . $payload['name'] . " \n Email : " . $payload['email'] . " \n Phone : " . $payload['phone'] . " \n Message : " . $message));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}


//Name Email Phone Message Form modal form
function modalForm($payload, $con)
{

    if (empty($payload['name']) || empty($payload['phone']) || empty($payload['email']) || empty($payload['message'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");

            $name = mysqli_real_escape_string($con, $payload['name']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            $phone = mysqli_real_escape_string($con, $payload['phone']);
            $message = mysqli_real_escape_string($con, clean($payload['message']));
            mysqli_query($con, "INSERT INTO leads(page,date,name,email,phone,message) VALUES('$page','$date','$name','$email','$phone','$message')") or die(mysqli_error($con));

            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Name : ' . $payload['name'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            $emailContent .= '<p>Phone : ' . $payload['phone'] . '</p>';
            $emailContent .= '<p>Message : ' . $message . '</p>';

            sendEmail($emailContent);

            // SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n Name : " . $payload['name'] . " \n Email : " . $payload['email'] . " \n Phone : " . $payload['phone'] . " \n Message : " . $message));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

//Name Email Phone Book Title Message Form modalpopup form
function modalFormPopUp($payload, $con)
{

    if (empty($payload['name']) || empty($payload['phone']) || empty($payload['email']) || empty($payload['message'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");

            $name = mysqli_real_escape_string($con, $payload['name']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            $phone = mysqli_real_escape_string($con, $payload['phone']);
            $book_title = mysqli_real_escape_string($con, $payload['book_title']);
            $message = mysqli_real_escape_string($con, clean($payload['message']));
            mysqli_query($con, "INSERT INTO leads(page,date,name,email,phone,message) VALUES('$page','$date','$name','$email','$phone','$book_title','$message')") or die(mysqli_error($con));

            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Name : ' . $payload['name'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            $emailContent .= '<p>Phone : ' . $payload['phone'] . '</p>';
            $emailContent .= '<p>Book Title : ' . $payload['book_title'] . '</p>';
            $emailContent .= '<p>Message : ' . $message . '</p>';

            sendEmail($emailContent);

            // SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n Name : " . $payload['name'] . " \n Email : " . $payload['email'] . " \n Phone : " . $payload['phone'] . " \n Book Title : " . $payload['book_title'] . " \n Message : " . $message));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

//Name Email Phone Message Book Title Form
function nameEmailPhoneBookMessageForm($payload, $con)
{

    if (empty($payload['name']) || empty($payload['phone']) || empty($payload['email']) || empty($payload['message'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");

            $name = mysqli_real_escape_string($con, $payload['name']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            $phone = mysqli_real_escape_string($con, $payload['phone']);
            $book_title = mysqli_real_escape_string($con, $payload['book_title']);
            $message = mysqli_real_escape_string($con, clean($payload['message']));
            mysqli_query($con, "INSERT INTO leads(page,date,name,email,phone,book_title,message) VALUES('$page','$date','$name','$email','$phone','$book_title','$message')") or die(mysqli_error($con));

            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Name : ' . $payload['name'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            $emailContent .= '<p>Phone : ' . $payload['phone'] . '</p>';
            $emailContent .= '<p>Book Title : ' . $payload['book_title'] . '</p>';
            $emailContent .= '<p>Message : ' . $message . '</p>';

            sendEmail($emailContent);

            // SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n Name : " . $payload['name'] . " \n Email : " . $payload['email'] . " \n Phone : " . $payload['phone'] . " \n Book Title : " . $payload['book_title'] . " \n Message : " . $message));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

function packageForm($payload, $con)
{
    if (empty($payload['name']) || empty($payload['phone']) || empty($payload['email']) || empty($payload['message'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");

            $name = mysqli_real_escape_string($con, $payload['name']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            $phone = mysqli_real_escape_string($con, $payload['phone']);
            $message = mysqli_real_escape_string($con, clean($payload['message']));

            // Check if package_type and price are empty and set default value
            $defaultText = "Popup Form Submission";
            $package_type = !empty($payload['package_type']) && $payload['package_type'] !== 'none' ? $payload['package_type'] : $defaultText;
            $price = !empty($payload['price']) && $payload['price'] !== 'none' ? $payload['price'] : $defaultText;

            $package_type = mysqli_real_escape_string($con, $package_type);
            $price = mysqli_real_escape_string($con, $price);

            mysqli_query($con, "INSERT INTO leads(page,date,name,email,phone,message,package_type,price) VALUES('$page','$date','$name','$email','$phone','$message','$package_type','$price')") or die(mysqli_error($con));

            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Name : ' . $payload['name'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            $emailContent .= '<p>Phone : ' . $payload['phone'] . '</p>';
            $emailContent .= '<p>Message : ' . $message . '</p>';
            $emailContent .= '<p>Package-type : ' . $package_type . '</p>';
            $emailContent .= '<p>Package-price : ' . $price . '</p>';
            sendEmail($emailContent);

            // SLACK NOTIFICATION
            $slackContent = json_encode(array(
                "text" => "Hi Team , \n\t We have received a new lead. Please check the following details. \n \n Page : " . $payload['page'] . " \n Name : " . $payload['name'] . " \n Email : " . $payload['email'] . " \n Phone : " . $payload['phone'] . " \n Message : " . $message . " \n Package-type : " . $package_type . " \n Package-price : " . $price
            ));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

//Name Email Phone
function nameEmailPhoneForm($payload, $con)
{
    if (empty($payload['name']) || empty($payload['phone']) || empty($payload['email'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");
            $name = mysqli_real_escape_string($con, $payload['name']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            $phone = mysqli_real_escape_string($con, $payload['phone']);
            mysqli_query($con, "INSERT INTO leads(page,date,name,email,phone) VALUES('$page','$date','$name','$email','$phone')") or die(mysqli_error($con));

            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Name : ' . $payload['name'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            $emailContent .= '<p>Phone : ' . $payload['phone'] . '</p>';
            sendEmail($emailContent);

            //SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n Name : " . $payload['name'] . " \n Email : " . $payload['email'] . " \n Phone : " . $payload['phone']));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

//Name Email
function nameEmailForm($payload, $con)
{
    if (empty($payload['name']) || empty($payload['email'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");
            $name = mysqli_real_escape_string($con, $payload['name']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            mysqli_query($con, "INSERT INTO leads(page,date,name,email) VALUES('$page','$date','$name','$email')") or die(mysqli_error($con));


            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Name : ' . $payload['name'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            sendEmail($emailContent);

            //SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n Name : " . $payload['name'] . " \n Email : " . $payload['email']));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

// Email
function emailForm($payload, $con)
{
    if (empty($payload['email'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");
            $email = mysqli_real_escape_string($con, $payload['email']);
            mysqli_query($con, "INSERT INTO leads(page,date,email) VALUES('$page','$date','$email')") or die(mysqli_error($con));

            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            sendEmail($emailContent);

            //SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n Email : " . $payload['email']));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

//ServicesCategory Email Form
function servicesEmailForm($payload, $con)
{
    if (empty($payload['services_category']) || empty($payload['email'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");
            $services_category = mysqli_real_escape_string($con, $payload['services_category']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            mysqli_query($con, "INSERT INTO leads(page,date,services_category,email) VALUES('$page','$date','$services_category','$email')") or die(mysqli_error($con));

            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent = '<p>services-category : ' . $payload['services_category'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            sendEmail($emailContent);

            //SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n services-category : " . $payload['services_category'] . " \n Email : " . $payload['email']));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}


//Blog Footer Form
function blogFooterForm($payload, $con)
{
    if (empty($payload['name']) || empty($payload['lastname']) || empty($payload['phone']) || empty($payload['email'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");
            $name = mysqli_real_escape_string($con, $payload['name']);
            $lastname = mysqli_real_escape_string($con, $payload['lastname']);
            $phone = mysqli_real_escape_string($con, $payload['phone']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            mysqli_query($con, "INSERT INTO leads(page,date,name,lastname,email,phone) VALUES('$page','$date','$name','$lastname','$email','$phone')") or die(mysqli_error($con));

            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Name : ' . $payload['name'] . '</p>';
            $emailContent .= '<p>LastName : ' . $payload['lastname'] . '</p>';
            $emailContent .= '<p>Phone : ' . $payload['phone'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            sendEmail($emailContent);

            //SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n Name : " . $payload['name'] . " \n LastName : " . $payload['lastname'] . " \n Phone : " . $payload['phone'] . " \n Email : " . $payload['email']));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}


//Book-writing Form 
function bookWritingServicesForm($payload, $con)
{
    if (empty($payload['name']) || empty($payload['email']) || empty($payload['phone'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");
            $name = mysqli_real_escape_string($con, $payload['name']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            $phone = mysqli_real_escape_string($con, $payload['phone']);
            $bookWriting = mysqli_real_escape_string($con, $payload['book_writing']);
            $message = mysqli_real_escape_string($con, clean($payload['message']));
            mysqli_query($con, "INSERT INTO leads(page,date,name,email,phone,book_writing,message) VALUES('$page','$date','$name','$email','$phone','$bookWriting','$message')") or die(mysqli_error($con));

            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Name : ' . $payload['name'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            $emailContent .= '<p>Phone : ' . $payload['phone'] . '</p>';
            $emailContent .= '<p>Book-writing : ' . $payload['book_writing'] . '</p>';
            $emailContent .= '<p>Message : ' . $message . '</p>';
            sendEmail($emailContent);

            //SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n Name : " . $payload['name'] . " \n Email : " . $payload['email'] . " \n Phone : " . $payload['phone'] . " \n Book-writing : " . $payload['book_writing'] . " \n Message : " . $message));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

function questionnaireForm($payload, $con)
{

    try {
        // DB FIRST
        $title = mysqli_real_escape_string($con, $payload['title']);
        $first_name = mysqli_real_escape_string($con, $payload['first_name']);
        $last_name = mysqli_real_escape_string($con, $payload['last_name']);
        $company_name = mysqli_real_escape_string($con, $payload['company_name']);
        $email = mysqli_real_escape_string($con, $payload['email']);
        $message = mysqli_real_escape_string($con, $payload['message']);
        $selected_goals = isset($payload['important_goals']) ? $payload['important_goals'] : [];
        $other_goals = isset($payload['other_goals']) ? trim($payload['other_goals']) : '';

        if (!empty($other_goals)) {
            $selected_goals[] = $other_goals; // Add "Other" field to goals
        }
        $important_goals = implode(", ", $selected_goals);

        $trade_show_experience = [];
        if (isset($payload['trade_show_experience'])) {
            foreach ($payload['trade_show_experience'] as $category => $levels) {
                $trade_show_experience[] = "$category: " . implode("/", $levels);
            }
        }
        $trade_show_experience_str = implode(", ", $trade_show_experience);

        $event_type = isset($payload['event_type']) ? $payload['event_type'] : '';

        // Process Design Flexibility (Yes / No)
        $design_flexibility = isset($payload['design_flexibility']) ? $payload['design_flexibility'] : '';
        $sql = "INSERT INTO form (title, first_name, last_name, company_name, email, message, important_goals, trade_show_experience, event_type, design_flexibility) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssssssss", $title, $first_name, $last_name, $company_name, $email, $message, $important_goals, $trade_show_experience_str, $event_type, $design_flexibility);

        // Execute the query
        if ($stmt->execute()) {
            $stmt->close();
            return true; // Success
        } else {
            $stmt->close();
            return false; // Failure
        }

        // //EMAIL NOTIFICATION
        // $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
        // $emailContent .= '<p>What is the nature of this job? : ' . $jobNatures . '</p>';
        // $emailContent .= '<p>Type of Your Book / Genre* : ' . $bookTypes . '</p>';
        // $emailContent .= '<p>Who is your target audience? : ' . $targetAudiences . '</p>';
        // $emailContent .= '<p>What sort of tone do you want your book to be in? : ' . $bookTones . '</p>';
        // $emailContent .= '<p>Please specify the narrative you would prefer the book to be written in. : ' . $bookNaratives . '</p>';
        // $emailContent .= '<p>Writing Style : ' . $writingStyles . '</p>';
        // $emailContent .= '<p>Title of your book and subtitle, if any : ' . $booktitlesubtitle . '</p>';
        // $emailContent .= '<p>Briefly describe your project. What is it about? : ' . $projectabout . '</p>';
        // $emailContent .= '<p>Why are you writing this book? : ' . $writingreason . '</p>';
        // $emailContent .= '<p>Do you have any specific instructions for the writer that you would like to convey? : ' . $instructions . '</p>';
        // $emailContent .= '<p>If you would be writing this book, how you would start? : ' . $howstart . '</p>';
        // $emailContent .= '<p>Are there any particular books, research sources, memoirs, and scripts that you want the writer to consider while developing your book? : ' . $researchSources . '</p>';
        // $emailContent .= '<p>Deadline: : ' . $deadline . '</p>';
        // $emailContent .= '<p>Book Size : ' . $booksize . '</p>';
        // sendEmail($emailContent);

        // //SLACK NOTIFICATION
        // $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $page . " \n What is the nature of this job? : " . $jobNatures . " \n Type of Your Book / Genre* : " . $bookTypes . " \n Who is your target audience? : " . $targetAudiences . " \n What sort of tone do you want your book to be in? : " . $bookTones . " \n Please specify the narrative you would prefer the book to be written in. : " . $bookNaratives . " \n Writing Style : " . $writingStyles . " \n Title of your book and subtitle, if any : " . $booktitlesubtitle . " \n Briefly describe your project. What is it about? : " . $projectabout . " \n Why are you writing this book? : " . $writingreason . " \n Do you have any specific instructions for the writer that you would like to convey? : " . $instructions . " \n If you would be writing this book, how you would start? : " . $howstart . " \n Are there any particular books, research sources, memoirs, and scripts that you want the writer to consider while developing your book? : " . $researchSources . " \n Deadline: : " . $deadline . " \n Book Size : " . $booksize));
        // sendSlack($slackContent);
    } catch (Exception $e) {
        die($e->getMessage());
    }
}
function questionnaireFormTwo($payload, $con)
{
    if (empty($payload['name']) || empty($payload['phone']) || empty($payload['email']) || empty($payload['message'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");
            $name = mysqli_real_escape_string($con, $payload['name']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            $phone = mysqli_real_escape_string($con, $payload['phone']);
            $message = mysqli_real_escape_string($con, clean($payload['message']));
            mysqli_query($con, "INSERT INTO leads(page,date,name,email,phone,message) VALUES('$page','$date','$name','$email','$phone','$message')") or die(mysqli_error($con));

            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Name : ' . $payload['name'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            $emailContent .= '<p>Phone : ' . $payload['phone'] . '</p>';
            $emailContent .= '<p>Message : ' . $message . '</p>';

            // sendEmail($emailContent);

            // SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n Name : " . $payload['name'] . " \n Email : " . $payload['email'] . " \n Phone : " . $payload['phone'] . " \n Message : " . $message));
            // sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

function questionnaireFormThree($payload, $con)
{
    if (empty($payload['name']) || empty($payload['email'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");
            $name = mysqli_real_escape_string($con, $payload['name']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            mysqli_query($con, "INSERT INTO leads(page,date,name,email) VALUES('$page','$date','$name','$email')") or die(mysqli_error($con));


            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Name : ' . $payload['name'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            sendEmail($emailContent);

            //SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n Name : " . $payload['name'] . " \n Email : " . $payload['email']));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

// Illustration Form
function illustrativeForm($payload, $con)
{

    if (empty($payload['name']) || empty($payload['phone']) || empty($payload['email']) || empty($payload['message'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");
            $name = mysqli_real_escape_string($con, $payload['name']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            $phone = mysqli_real_escape_string($con, $payload['phone']);
            $message = mysqli_real_escape_string($con, clean($payload['message']));
            mysqli_query($con, "INSERT INTO leads(page,date,name,email,phone,message) VALUES('$page','$date','$name','$email','$phone','$message')") or die(mysqli_error($con));

            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Name : ' . $payload['name'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            $emailContent .= '<p>Phone : ' . $payload['phone'] . '</p>';
            $emailContent .= '<p>Message : ' . $message . '</p>';
            sendEmail($emailContent);

            // SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n Name : " . $payload['name'] . " \n Email : " . $payload['email'] . " \n Phone : " . $payload['phone'] . " \n Message : " . $message));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

// BOOK WRITING FORM
function bookWriting($payload, $con)
{
    if (empty($payload['name']) || empty($payload['email']) || empty($payload['phone'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");
            $name = mysqli_real_escape_string($con, $payload['name']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            $phone = mysqli_real_escape_string($con, $payload['phone']);
            $servicesCategory = mysqli_real_escape_string($con, $payload['services_category']);
            $message = mysqli_real_escape_string($con, clean($payload['message']));
            mysqli_query($con, "INSERT INTO leads(page,date,name,email,phone,services_category,message) VALUES('$page','$date','$name','$email','$phone','$servicesCategory','$message')") or die(mysqli_error($con));

            //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $payload['page'] . '</p>';
            $emailContent .= '<p>Name : ' . $payload['name'] . '</p>';
            $emailContent .= '<p>Email : ' . $payload['email'] . '</p>';
            $emailContent .= '<p>Phone : ' . $payload['phone'] . '</p>';
            $emailContent .= '<p>Services : ' . $payload['services_category'] . '</p>';
            $emailContent .= '<p>Message : ' . $message . '</p>';
            sendEmail($emailContent);

            //SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n\t We have received a new lead . Please check the following details.  \n \n Page : " . $payload['page'] . " \n Name : " . $payload['name'] . " \n Email : " . $payload['email'] . " \n Phone : " . $payload['phone'] . " \n Services : " . $payload['services_category'] . " \n Message : " . $message));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}



function publishingJourney($payload, $con)
{

    if (empty($payload['name']) || empty($payload['phone'])) {
        echo 'Empty Request';
    } else {
        try {
            //DB FIRST
            $page = $_POST['page'];
            $date = date("Y-m-d H:i:s");
            $name = mysqli_real_escape_string($con, $payload['name']);
            $email = mysqli_real_escape_string($con, $payload['email']);
            $phone = mysqli_real_escape_string($con, $payload['phone']);
            $manuscript_ready = mysqli_real_escape_string($con, $payload['manuscript-ready']);
            $published_before = mysqli_real_escape_string($con, $payload['published-before']);
            $plan_on_publishing = mysqli_real_escape_string($con, $payload['plan-on-publishing']);
            $services_checkbox = mysqli_real_escape_string($con, implode(",", $payload['services']));

            $cc = mysqli_query($con, "INSERT INTO leads(page,date,name,email,phone,manuscript_ready,published_before,plan_on_publishing,services_checkbox) VALUES('$page','$date','$name','$email','$phone','$manuscript_ready','$published_before','$plan_on_publishing','$services_checkbox')") or die(mysqli_error($con));

            // //EMAIL NOTIFICATION
            $emailContent = '<p>Page : ' . $page . '</p>';
            $emailContent .= '<p>Date : ' . $date . '</p>';
            $emailContent .= '<p>Do you have a manuscript ready? : ' . $manuscript_ready . '</p>';
            $emailContent .= '<p>Have you published before? : ' . $published_before . '</p>';
            $emailContent .= '<p>What type of book do you plan on publishing? : ' . $plan_on_publishing . '</p>';
            $emailContent .= '<p>What services are you looking for? : ' . $services_checkbox . '</p>';
            $emailContent .= '<p>Name : ' . $name . '</p>';
            $emailContent .= '<p>Email : ' . $email . '</p>';
            $emailContent .= '<p>Phone : ' . $phone . '</p>';
            sendEmail($emailContent);

            //SLACK NOTIFICATION
            $slackContent = json_encode(array("text" => "Hi Team , \n We have received a new lead . Please check the following details.  \n \n Page : " . $page . " \n Do you have a manuscript ready? : " . $manuscript_ready . " \n Have you published before? : " . $published_before . " \n What type of book do you plan on publishing? : " . $plan_on_publishing . " \n What services are you looking for? : " . $services_checkbox . " \n Name : " . $name . " \n Email : " . $email . " \n Phone : " . $phone));
            sendSlack($slackContent);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

function sendEmail($message, $subject = 'Lead from no-reply@hancockpublishers.com', $to = 'no-reply@donaldsbookpublishing.com', $fromName = 'Hancock Publishers')
{
    // Set up additional headers
    $headers = "From: {$fromName} <no-reply@hancockpublishers.com>\r\n";
    $headers .= "Reply-To: no-reply@hancockpublishers.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

    try {
        // Attempt to send the main email
        if (mail($to, $subject, $message, $headers)) {
            // Send auto-reply email
            $autoReplySubject = 'Hancockpublishers has Received Your Message!';
            $autoReplyMessage = "
                <p>Dear <b>{$_POST['full_name']},</b></p>
                <p>Thank you for reaching out to Hancockpublishers! Our Hancockpublishers have received your query and are working on it as of now. Expect to hear back from us within 24 hours. For quick answers, you might find our <a href='https://hancockpublishers.com/faq'>FAQ</a> page helpful.</p>
                <p>Thanks for your patience and interest in our work!</p>
                <br><br>
                <p><b>Warm regards,</b></p>
                <p>The Hancockpublishers Team.</p>
                <br>
                <img src='https://hancockpublishers.com/assets/img/logo.png' alt='Hancockpublishers' style='max-width: 200px;'>
                <p>Email: <a href='mailto:info@hancockpublishers.com'>info@hancockpublishers.com</a>
                <br>Phone number: <a href='tel:(415) 520-1098'>(415) 520-1098</a>
                <br>Address: 895 Dove Street. Newport Beach, CA 92660 United States</p>
            ";

            // Send auto-reply email
            if (mail($_POST["email"], $autoReplySubject, $autoReplyMessage, $headers)) {
                echo 'Email sent successfully!';
            } else {
                echo 'Failed to send auto-reply email.';
            }
        } else {
            echo 'Failed to send email.';
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Error: {$e->getMessage()}";
    }
}


//------------  --------------------------HELPERS------------------------------------------
//SEND Email For Godaddy to Gmail
// function sendEmail($message, $subject = 'Lead from noreply@hancockpublishers.com', $to = 'leads@hancockpublishers.com', $fromName = 'Hancock Publishers')
// {
//     $mail = new PHPMailer(true);
//     try {
//         // Server settings
//         $mail->SMTPDebug = SMTP::DEBUG_SERVER;
//         $mail->isSMTP();
//         $mail->Host = 'hancockpublishers.com';
//         $mail->SMTPAuth = true;
//         $mail->Username = 'noreply@hancockpublishers.com';
//         $mail->Password = 'mPio+n)45OTs';
//         $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
//         $mail->Port = 465;

//         // Recipients for the main email
//         $mail->setFrom('noreply@hancockpublishers.com');
//         $mail->addAddress($to);

//         // Content for the main email
//         $mail->isHTML(true);
//         $mail->Subject = $subject;
//         $mail->Body = $message;
//         $mail->send();

//         // Auto-reply email
//         // $autoReplySubject = 'Thank you for contacting Hancockpublishers';
//         // $autoReplyMessage = "Thank you for getting in touch with Hancockpublishers! Your message has been received, and we appreciate your interest. Our team is currently reviewing your inquiry and will get back to you as soon as possible.";

//         $autoReplySubject = 'Hancockpublishers has Received Your Message!';
//         $autoReplyMessage = "
//             <p><b>Dear {$_POST['name']},</b></p>
//             <p>Thank you for reaching out to Hancockpublishers! Our Hancockpublishers have received your query and are working on it as of now. Expect to hear back from us within 24 hours. For quick answers, you might find our <a href='#0'>FAQ</a> page helpful.</p>
//             <p>Thanks for your patience and interest in our work!</p>
//             <p><b>Warm regards,</b></p>
//             <p>The Hancockpublishers Team.</p>
//             <br>
//             <img src='https://hancockpublishers.com/assets/img/logo.png' alt='Hancockpublishers' style='max-width: 200px;'>
//             <p>Email: <a href='mailto:info@hancockpublishers.com'>info@hancockpublishers.com</a>
//             <br>Phone number: <a href='tel:(415) 520-1098'>(415) 520-1098</a>
//             <br>Address: 895 Dove Street. Newport Beach, CA 92660 United States</p>
//         ";

//         $autoReply = new PHPMailer(true);
//         $autoReply->isSMTP();
//         $autoReply->Host = 'hancockpublishers.com';
//         $autoReply->SMTPAuth = true;
//         $autoReply->Username = 'noreply@hancockpublishers.com';
//         $autoReply->Password = 'mPio+n)45OTs';
//         $autoReply->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
//         $autoReply->Port = 587;

//         $autoReply->setFrom('noreply@hancockpublishers.com');
//         $autoReply->addAddress($_POST["email"]);  // Send auto-reply to the same person who submitted the form

//         $autoReply->isHTML(true);
//         $autoReply->Subject = $autoReplySubject;
//         $autoReply->Body = $autoReplyMessage;
//         $autoReply->send();


//         // echo 'Message has been sent';
//     } catch (Exception $e) {
//         echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//     }
// }


//SEND EMAIL
/*function sendEmail($message,$subject='Lead from hancockpublishers.com',$to=' info@hancockpublishers.com', $fromName = 'Hancock Writers') {
    $mail = new PHPMailer;                                  
    $mail->IsSMTP();
    $mail->Host = 'smtp.gmail.com';            
    $mail->Port = 587;                                   
    $mail->SMTPAuth = true;
    $mail->Username = 'noreply@hancockpublishers.com'; 
    $mail->Password = 'utmzsadrtrllnkfd';                  
    $mail->isHTML(true);
    $mail->From = 'noreply@hancockpublishers.com';
    $mail->FromName = $fromName;          
    $mail->AddAddress($to);
    $mail->Subject = $subject;
    $mail->Body    = $message;
    if(!$mail->send()) {
        // echo 'Message could not be sent.';
        // echo 'Mailer Error: ' . $mail->ErrorInfo;
        // exit;
    }
}*/

/*function sendEmailIllustration($message, $subject = 'Lead from hancockpublishers.com', $to = 'info@hancockpublishers.com', $fromName = 'Hancock Publishers')
{
    $mail = new PHPMailer;
    $mail->IsSMTP();
    $mail->Host = 'smtp.gmail.com';            
    $mail->Port = 587;                                   
    $mail->SMTPAuth = true;
    $mail->Username = 'noreply@hancockpublishers.com'; 
    $mail->Password = 'utmzsadrtrllnkfd';                 
    $mail->isHTML(true);
    $mail->From = 'noreply@hancockpublishers.com';
    $mail->FromName = $fromName;
    $mail->AddAddress($to);
    // $mail->AddCC($cc1);
    $mail->Subject = $subject;
    $mail->Body = $message;
    if ( !$mail->send() )
    {
        // echo 'Message could not be sent.';
        // echo 'Mailer Error: ' . $mail->ErrorInfo;
        // exit;
    }
}*/


// SEND SLACK
function sendSlack($data)
{
    $connection2 = new mysqli("localhost", "u523339939_donald", "Cybertron@2026", "u523339939_donald");
    $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, 'https://hooks.slack.com/services/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');
    curl_setopt($ch, CURLOPT_URL, 'https://hooks.slack.com/services/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');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['payload' => $data]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close($ch);
    mysqli_query($connection2, "INSERT INTO slack(error,slack_payload) VALUES('$server_output','$data')") or die(mysqli_error($connection2));
    return ($server_output);
}

// CLEAN FUNCTION
function clean($string)
{
    $string = str_replace(' ', '-', $string);

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}

// Admin Authentication Functions (using environment variables)
function adminLogin($email, $password)
{
    global $admin_email, $admin_password;
    
    if ($email === $admin_email && $password === $admin_password) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['admin_id'] = 1;
        $_SESSION['admin_email'] = $admin_email;
        $_SESSION['admin_name'] = 'Admin User';
        return true;
    }
    return false;
}

function isAdminLoggedIn()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['admin_id']);
}

function requireAdminAuth()
{
    if (!isAdminLoggedIn()) {
        header('Location: /admin?login=required');
        exit;
    }
}

function adminLogout()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_unset();
    session_destroy();
    header('Location: /admin');
    exit;
}

// Admin CRUD Functions for Positions
function addPosition($data, $connection)
{
    $name = $connection->real_escape_string($data['name'] ?? '');
    $description = $connection->real_escape_string($data['description'] ?? '');
    $image = $connection->real_escape_string($data['image'] ?? '');
    $what_you_do = $connection->real_escape_string($data['what_you_do'] ?? '');
    $who_you_are = $connection->real_escape_string($data['who_you_are'] ?? '');
    $what_we_offer = $connection->real_escape_string($data['what_we_offer'] ?? '');
    $extras = $connection->real_escape_string($data['extras'] ?? '');
    $status = $connection->real_escape_string($data['status'] ?? 'active');

    // Roles: accept textarea (one per line), CSV, or array
    $rolesInput = $data['roles'] ?? '';
    if (is_array($rolesInput)) {
        $rolesArray = array_map('trim', $rolesInput);
    } else {
        $rolesArray = preg_split('/\r\n|\r|\n|,/', $rolesInput);
        $rolesArray = array_map('trim', $rolesArray);
    }
    $rolesArray = array_values(array_filter($rolesArray, fn($v) => $v !== ''));
    $rolesJson = $connection->real_escape_string(json_encode($rolesArray));

    $query = "INSERT INTO positions 
        (name, description, image, roles, what_you_do, who_you_are, what_we_offer, extras, status, created_at) 
        VALUES 
        ('$name', '$description', '$image', '$rolesJson', '$what_you_do', '$who_you_are', '$what_we_offer', '$extras', '$status', NOW())";

    return $connection->query($query);
}

function updatePosition($id, $data, $connection)
{
    $id = (int)$id;
    $name = $connection->real_escape_string($data['name'] ?? '');
    $description = $connection->real_escape_string($data['description'] ?? '');
    $image = $connection->real_escape_string($data['image'] ?? '');
    $what_you_do = $connection->real_escape_string($data['what_you_do'] ?? '');
    $who_you_are = $connection->real_escape_string($data['who_you_are'] ?? '');
    $what_we_offer = $connection->real_escape_string($data['what_we_offer'] ?? '');
    $extras = $connection->real_escape_string($data['extras'] ?? '');
    $status = $connection->real_escape_string($data['status'] ?? 'active');

    $rolesInput = $data['roles'] ?? '';
    if (is_array($rolesInput)) {
        $rolesArray = array_map('trim', $rolesInput);
    } else {
        $rolesArray = preg_split('/\r\n|\r|\n|,/', $rolesInput);
        $rolesArray = array_map('trim', $rolesArray);
    }
    $rolesArray = array_values(array_filter($rolesArray, fn($v) => $v !== ''));
    $rolesJson = $connection->real_escape_string(json_encode($rolesArray));
    
    $query = "UPDATE positions SET 
        name = '$name', 
        description = '$description', 
        image = '$image', 
        roles = '$rolesJson', 
        what_you_do = '$what_you_do', 
        who_you_are = '$who_you_are', 
        what_we_offer = '$what_we_offer', 
        extras = '$extras',
        status = '$status',
        updated_at = NOW()
        WHERE id = $id";

    return $connection->query($query);
}

function deletePosition($id, $connection)
{
    $id = (int)$id;
    $query = "DELETE FROM positions WHERE id = $id";
    return $connection->query($query);
}

function getAllPositionsAdmin($connection)
{
    $query = "SELECT * FROM positions ORDER BY created_at DESC";
    $result = $connection->query($query);
    $positions = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['roles'] = json_decode($row['roles'], true) ?: [];
            $positions[] = $row;
        }
    }
    
    return $positions;
}

//GET CURRENT REQUEST
function current_url()
{
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $url;
}
