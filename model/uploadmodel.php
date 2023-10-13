<?php
include('../inc/const.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = '/var/www/html/newnetdekintai/assets/uploads/notice/'; // Thư mục lưu trữ ảnh
    $noticeId = $_POST['noticeId']; // Lấy noticeId từ POST data
    $fileExtension = pathinfo($_FILES["udimagefile_new"]["name"], PATHINFO_EXTENSION); // Lấy định dạng của tệp tin gốc
    $newFileName = generateUniqueFileName($uploadDir, $fileExtension, $noticeId);
    $originalFileName = $_FILES["udimagefile_new"]["name"]; // Tên tệp tin gốc từ client
    $uploadFile = $uploadDir . $newFileName; // Đường dẫn tới tệp tin lưu trữ
    $uploadOk = true;

    // Check file name is exists
    if (file_exists($uploadFile)) {
        echo "File name is exists -> Delete old file name";
        unlink($uploadFile);
    }
    // Check Size  (vd: Not over 2MB)
    if ($_FILES["file"]["size"] > 2000000) {
        echo "File is BIG!";
        $uploadOk = false;
    }
    // check file extention (image only)
    $allowedTypes = array("jpg", "jpeg", "png", "gif");
    $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    if (!in_array($fileType, $allowedTypes)) {
        echo "Image only(jpg, jpeg, png, gif).";
        $uploadOk = false;
    }

    // if not error save
    if ($uploadOk) {
        if (move_uploaded_file($_FILES["udimagefile_new"]["tmp_name"], $uploadFile)) {
       
            // Remove old files based on noticeId and newFileName
            deleteNoticeImages($uploadDir, $noticeId, $newFileName);
            echo $newFileName;
        } else {
            echo "Upload Error";
        }
    }
}

// delete old image and not format image
function deleteNoticeImages($uploadDir, $noticeId, $newFileName)
{
    global $LENGTH_RANDOM_UNIQUE_NAME;
    $files = scandir($uploadDir);
    foreach ($files as $file) {
        if ($file !== $newFileName && strpos($file, $noticeId) === 0) {
            $filePath = $uploadDir . $file;
            if (unlink($filePath)) {
                echo "Deleted file: " . $file . "<br>";
            } else {
                echo "Failed to delete file: " . $file . "<br>";
            }
        }
        if (!preg_match('/_notice_\w{' . $LENGTH_RANDOM_UNIQUE_NAME . '}\.\w+/', $file)) {
            $filePath = $uploadDir . $file;
            unlink($filePath);
        }
    }
}

function generateUniqueFileName($uploadDir, $fileExtension, $noticeId)
{
    global $LENGTH_RANDOM_UNIQUE_NAME;
    $uniqueFileName = generateRandomString($LENGTH_RANDOM_UNIQUE_NAME);
    $newFileName = $noticeId . '_notice_' . $uniqueFileName . '.' . $fileExtension;
    while (file_exists($uploadDir . $newFileName)) {
        $uniqueFileName = generateRandomString($LENGTH_RANDOM_UNIQUE_NAME);
        $newFileName = $noticeId . '_notice_' . $uniqueFileName . '.' . $fileExtension;
    }
    return $newFileName;
}
function generateRandomString($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
