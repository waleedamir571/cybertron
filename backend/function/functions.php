<?php
//require_once('../PHPMailer/class.phpmailer.php');
//require_once('../PHPMailer/class.smtp.php');
// require_once('../PHPMailer/mail_test.php');
// require '../mailchimp-api-master/src/MailChimp.php';

require __DIR__ . '/../config/dbc.php';

require __DIR__ . '/../vendor/PHPMailer/src/Exception.php';
require __DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../vendor/PHPMailer/src/SMTP.php';
require __DIR__ . '/../vendor/autoload.php';

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
// CLEAN FUNCTION
function clean($string)
{
    $string = str_replace(' ', '-', $string);

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}

// Admin Authentication Functions
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
        header('Location: /admin.php?login=required');
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
    header('Location: /admin.php');
    exit;
}

// Image Upload Function
function uploadJobImage($file)
{
    $uploadDir = __DIR__ . '/../../uploads/jobs/';
    
    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Validate file
    if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'No file uploaded or upload error.'];
    }
    
    // Check file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'message' => 'File size too large. Maximum 5MB allowed.'];
    }
    
    // Check file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, and GIF allowed.'];
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'job_' . uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'path' => 'uploads/jobs/' . $filename];
    } else {
        return ['success' => false, 'message' => 'Failed to move uploaded file.'];
    }
}

// Enhanced Job Position Functions
function addJobPosition($data, $files, $connection)
{
    try {
        // Validate required fields
        if (empty($data['name']) || empty($data['description'])) {
            return ['success' => false, 'message' => 'Job title and description are required.'];
        }
        
        // Handle image upload
        $imagePath = '';
        if (isset($files['image']) && $files['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = uploadJobImage($files['image']);
            if ($uploadResult['success']) {
                $imagePath = $uploadResult['path'];
            } else {
                return ['success' => false, 'message' => $uploadResult['message']];
            }
        }
        
        // Prepare basic data
        $name = trim($data['name']);
        $description = trim($data['description']);
        $image = $imagePath;
        $extras = trim($data['extras'] ?? '');
        $status = $data['status'] ?? 'active';
        
        // Process array fields - DON'T escape these since we're using prepared statements
        $what_you_do = processArrayField($data['what_you_do'] ?? '', $connection);
        $roles = processArrayField($data['roles'] ?? '', $connection);
        $who_you_are = processArrayField($data['who_you_are'] ?? '', $connection);
        $what_we_offer = processArrayField($data['what_we_offer'] ?? '', $connection);
        
        // Insert into database
        $query = "INSERT INTO job_positions 
                 (name, description, image, roles, what_you_do, who_you_are, what_we_offer, extras, status, created_at) 
                 VALUES 
                 (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            return ['success' => false, 'message' => 'Database prepare error: ' . $connection->error];
        }
        
        $stmt->bind_param("sssssssss", $name, $description, $image, $roles, $what_you_do, $who_you_are, $what_we_offer, $extras, $status);
        
        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true, 'message' => 'Job position added successfully!'];
        } else {
            $stmt->close();
            return ['success' => false, 'message' => 'Database execution error: ' . $connection->error];
        }
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}

function updateJobPosition($id, $data, $files, $connection)
{
    try {
        $id = (int)$id;
        
        // Validate required fields
        if (empty($data['name']) || empty($data['description'])) {
            return ['success' => false, 'message' => 'Job title and description are required.'];
        }
        
        // Get current position data
        $currentQuery = "SELECT image FROM job_positions WHERE id = ?";
        $currentStmt = $connection->prepare($currentQuery);
        $currentStmt->bind_param("i", $id);
        $currentStmt->execute();
        $currentResult = $currentStmt->get_result();
        $currentData = $currentResult->fetch_assoc();
        $currentStmt->close();
        
        if (!$currentData) {
            return ['success' => false, 'message' => 'Job position not found.'];
        }
        
        // Handle image upload
        $imagePath = $currentData['image']; // Keep current image by default
        if (isset($files['image']) && $files['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = uploadJobImage($files['image']);
            if ($uploadResult['success']) {
                // Delete old image if exists
                if ($currentData['image'] && file_exists(__DIR__ . '/../../' . $currentData['image'])) {
                    unlink(__DIR__ . '/../../' . $currentData['image']);
                }
                $imagePath = $uploadResult['path'];
            } else {
                return ['success' => false, 'message' => $uploadResult['message']];
            }
        }
        
        // Prepare basic data
        $name = trim($data['name']);
        $description = trim($data['description']);
        $image = $imagePath;
        $extras = trim($data['extras'] ?? '');
        $status = $data['status'] ?? 'active';
        
        // Process array fields - DON'T escape these since we're using prepared statements
        $what_you_do = processArrayField($data['what_you_do'] ?? '', $connection);
        $roles = processArrayField($data['roles'] ?? '', $connection);
        $who_you_are = processArrayField($data['who_you_are'] ?? '', $connection);
        $what_we_offer = processArrayField($data['what_we_offer'] ?? '', $connection);
        
        // Update database
        $query = "UPDATE job_positions SET 
                 name = ?, description = ?, image = ?, roles = ?, what_you_do = ?, 
                 who_you_are = ?, what_we_offer = ?, extras = ?, status = ?, updated_at = NOW() 
                 WHERE id = ?";
        
        $stmt = $connection->prepare($query);
        if (!$stmt) {
            return ['success' => false, 'message' => 'Database prepare error: ' . $connection->error];
        }
        
        $stmt->bind_param("sssssssssi", $name, $description, $image, $roles, $what_you_do, $who_you_are, $what_we_offer, $extras, $status, $id);
        
        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true, 'message' => 'Job position updated successfully!'];
        } else {
            $stmt->close();
            return ['success' => false, 'message' => 'Database execution error: ' . $connection->error];
        }
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}

function deleteJobPosition($id, $connection)
{
    try {
        $id = (int)$id;
        
        // Get image path before deletion
        $imageQuery = "SELECT image FROM job_positions WHERE id = ?";
        $imageStmt = $connection->prepare($imageQuery);
        $imageStmt->bind_param("i", $id);
        $imageStmt->execute();
        $imageResult = $imageStmt->get_result();
        $imageData = $imageResult->fetch_assoc();
        $imageStmt->close();
        
        // Delete from database
        $query = "DELETE FROM job_positions WHERE id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            // Delete image file if exists
            if ($imageData && $imageData['image'] && file_exists(__DIR__ . '/../../' . $imageData['image'])) {
                unlink(__DIR__ . '/../../' . $imageData['image']);
            }
            $stmt->close();
            return ['success' => true, 'message' => 'Job position deleted successfully!'];
        } else {
            $stmt->close();
            return ['success' => false, 'message' => 'Database execution error: ' . $connection->error];
        }
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}

function getAllJobPositions($connection)
{
    $query = "SELECT * FROM job_positions ORDER BY created_at DESC";
    $result = $connection->query($query);
    $positions = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $positions[] = $row;
        }
    }
    
    return $positions;
}

function getJobPositionById($id, $connection)
{
    $id = (int)$id;
    $query = "SELECT * FROM job_positions WHERE id = ? AND status = 'active'";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $position = $result->fetch_assoc();
    $stmt->close();
    
    return $position;
}

function getActiveJobPositions($connection)
{
    $query = "SELECT * FROM job_positions WHERE status = 'active' ORDER BY created_at DESC";
    $result = $connection->query($query);
    $positions = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $positions[] = $row;
        }
    }
    
    return $positions;
}

// Helper function to process array fields
function processArrayField($input, $connection)
{
    if (empty($input)) {
        return json_encode([]);
    }
    
    if (is_array($input)) {
        $array = array_map('trim', $input);
    } else {
        // Clean the input from various line break formats
        $cleanInput = str_replace(['\\r\\n', '\\n', '\r\n'], "\n", $input);
        $array = array_map('trim', explode("\n", $cleanInput));
    }
    
    // Remove empty values and reindex
    $array = array_values(array_filter($array, function($v) { return $v !== ''; }));
    
    return json_encode($array, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

//GET CURRENT REQUEST
function current_url()
{
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $url;
}
