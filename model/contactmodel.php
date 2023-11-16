<?php
$reg_dt = date('Y-m-d H:i:s');
$upt_dt = date('Y-m-d H:i:s');


// userloginList.php
$year = isset($_POST["selyy"]) ? $_POST["selyy"] : date('Y');
$month = isset($_POST["selmm"]) ? $_POST["selmm"] : date('m');
$day = isset($_POST["seldd"]) ? $_POST["seldd"] : date('d');

$sql_userlogin = 'SELECT `tbl_userlogin`.*, `tbl_company`.`companyname`
FROM `tbl_userlogin`
LEFT JOIN `tbl_company` ON `tbl_userlogin`.`companyid` = `tbl_company`.`companyid`
WHERE YEAR(`tbl_userlogin`.`workymd`) IN("' . $year . '")
AND MONTH(`tbl_userlogin`.`workymd`) IN("' . $month . '")
AND DAY(`tbl_userlogin`.`workymd`) IN("' . $day . '") ';

// Select database from tbl_userlogin table
if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
    $sql_userlogin .= 'ORDER BY `tbl_userlogin`.`companyid`, `tbl_userlogin`.`logcnt`';
} else if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) {
    $sql_userlogin .= 'AND `tbl_userlogin`.`companyid` IN ("' . $_SESSION['auth_companyid'] . '")
    ORDER BY `tbl_userlogin`.`logcnt`';
} elseif ($_SESSION['auth_type'] == constant('USER')) {
    $sql_userlogin .= 'AND `tbl_userlogin`.`uid` IN("' . $_SESSION['auth_uid'] . '")
    AND `tbl_userlogin`.`logtype` IN("' . constant('USER') . '")
    AND `tbl_userlogin`.`companyid` IN ("' . $_SESSION['auth_companyid'] . '")
    ORDER BY `tbl_userlogin`.`logcnt`';
} else {
    error_log('user type error: ' . mysqli_error($conn));
}

$result_userlogin = mysqli_query($conn, $sql_userlogin);
$userlogin_list = mysqli_fetch_all($result_userlogin, MYSQLI_ASSOC);

// get company id from loginned user id 
$uid = $_SESSION['auth_uid'];
$stmt = $conn->prepare("SELECT companyid FROM tbl_user WHERE uid = ?");
$stmt->bind_param("s", $uid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$companyId = $row['companyid'];
$stmt->close();


// noticeList.php
// Select database from tbl_notice table
global $IMAGE_UPLOAD_DIR;
$sql_notice_select = '';
$currentCompanyID = $_SESSION['auth_companyid'];
if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
    $sql_notice_select = "SELECT
    `tbl_notice`.*,
    `tbl_user`.`companyid` AS COMPANYID,
    `tbl_user`.`name`,
    `tbl_company`.`companyname` 
    FROM `tbl_notice`
    LEFT JOIN `tbl_user` ON `tbl_notice`.`email` = `tbl_user`.`email`
    LEFT JOIN `tbl_company` ON `tbl_user`.`companyid` = `tbl_company`.`companyid`
    ORDER BY `tbl_user`.`companyid`, `tbl_notice`.`bid`";
} else {
    $sql_notice_select = "SELECT
    `tbl_notice`.*,
    `tbl_user`.`companyid` AS COMPANYID,
    `tbl_user`.`name`,
    `tbl_company`.`companyname` 
    FROM `tbl_notice`
    LEFT JOIN `tbl_user` ON `tbl_notice`.`email` = `tbl_user`.`email`
    LEFT JOIN `tbl_company` ON `tbl_user`.`companyid` = `tbl_company`.`companyid`
    WHERE `tbl_user`.`companyid` = '$currentCompanyID'
    ORDER BY `tbl_notice`.`bid`";
}
$sql_notice_select_ = "SELECT * FROM `tbl_notice` ORDER BY `tbl_notice`.`bid`";
$result_nl = $conn->query($sql_notice_select_);
$last_BID_nl = null;
if ($result_nl->num_rows > 0) {
    while ($row = $result_nl->fetch_assoc()) {
        $last_BID_nl = $row["bid"];
    }
}
$new_BID_nl = $last_BID_nl + 1;

$result_notice_select = mysqli_query($conn, $sql_notice_select);
$notice_list_select = mysqli_fetch_all($result_notice_select, MYSQLI_ASSOC);

// Default value for search
$searchTitle = '';
$searchContent = '';

if (isset($_POST['SearchButtonNL'])) {
    $searchKeyword = trim($_POST['searchKeywordTC']);
    $searchOption = $_POST['rdoSearch'];

    if ($searchOption == "1") {
        $searchContent = "%" . $searchKeyword . "%";
    } else {
        $searchTitle = "%" . $searchKeyword . "%";
    }

    $sql_notice = '';
    if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
        $sql_notice = 'SELECT DISTINCT
        `tbl_notice`.*,
        `tbl_user`.`name`,
        `tbl_user`.`companyid`,
        `tbl_company`.`companyname` 
        FROM `tbl_notice`
        LEFT JOIN `tbl_user` ON `tbl_notice`.`email` = `tbl_user`.`email`
        LEFT JOIN `tbl_company` ON `tbl_user`.`companyid` = `tbl_company`.`companyid`
        WHERE (`tbl_notice`.`title` LIKE "' . $searchTitle . '" OR `tbl_notice`.`content` LIKE "' . $searchContent . '")
        ORDER BY `tbl_user`.`companyid`';
    } else {
        $sql_notice = 'SELECT
        `tbl_notice`.*,
        `tbl_user`.`name`,
        `tbl_user`.`companyid`
        FROM `tbl_notice`
        LEFT JOIN `tbl_user` ON `tbl_notice`.`email` = `tbl_user`.`email`
        LEFT JOIN `tbl_company` ON `tbl_user`.`companyid` = `tbl_company`.`companyid`
        WHERE `tbl_user`.`companyid` = ' . $currentCompanyID . '  
        AND `tbl_notice`.`title` LIKE "' . $searchTitle . '" OR `tbl_notice`.`content` LIKE "' . $searchContent . '"';
    }
    error_log($sql_notice);

    $result_notice = mysqli_query($conn, $sql_notice);
    $notice_list = mysqli_fetch_all($result_notice, MYSQLI_ASSOC);
} else {
    $notice_list = $notice_list_select;
}

// Save Data to tbl_notice DB 
if (isset($_POST['btnRegNL'])) {
    $content_d = $_POST['content'];
    $content_f = str_replace(array("\n", "\r"), '', $content_d);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $content_f);
    $reader = mysqli_real_escape_string($conn, $_POST['reader']);
    $viewcnt = mysqli_real_escape_string($conn, $_POST['viewcnt']);
    $reg_dt = mysqli_real_escape_string($conn, $_POST['reg_dt']);

    // upload image chg start
    $noticeId = $bid;
    $fileExtension_add = pathinfo($_FILES["imagefile"]["name"], PATHINFO_EXTENSION);
    $newFileName = generateUniqueFileName($IMAGE_UPLOAD_DIR, $fileExtension_add, $noticeId, $companyId);
    $originalFileName = $_FILES["imagefile"]["name"];
    if ($originalFileName == "") {
        $fileName == '';
    } else {
        $uploadOk = true;
        global $NOTICE_IMAGE_MAXSIZE;
        $uploadFile = $IMAGE_UPLOAD_DIR . $newFileName;

        // Check file name is exists
        if (file_exists($uploadFile)) {
            error_log("File name is exists -> Delete old file name");
            unlink($uploadFile);
        }
        // check size 
        if (!isFileSizeValid($originalFileName, $NOTICE_IMAGE_MAXSIZE)) {
            error_log("File is BIG!");
            $uploadOk = false;
        }
        // check valid extention 
        $fileExtension_add = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
        if (!checkValidExtension($fileExtension_add)) {
            error_log("Image only(jpg, jpeg, png, gif):");
            $uploadOk = false;
        }

        // if not error save
        if ($uploadOk) {
            $fileName = $newFileName;
            $fileName = str_replace('__', '_' . $new_BID_nl . '_', $fileName);

            // upload to server
            $uploadFile = $IMAGE_UPLOAD_DIR . $fileName;
            if (move_uploaded_file($_FILES["imagefile"]["tmp_name"], $uploadFile)) {
                deleteNoticeImages($IMAGE_UPLOAD_DIR, $noticeId, $fileName);
            } else {
                error_log("Upload Error");
            }
        }
    }

    // insert to DB 
    $sql = "INSERT INTO `tbl_notice` (`bid`, `title`, `content`, `imagefile`, `reader`, `viewcnt`, `email`, `reg_dt` , `upt_dt`)
             VALUES ('$new_BID_nl', '$title', '$content', '$fileName', '$reader', '$viewcnt', '$email', '$reg_dt' , null)";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['save_success'] = $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update Data to tbl_notice DB 
if (isset($_POST['btnUpdateNL'])) {
    $udcontent_d = $_POST['udcontent'];
    $udcontent_f = str_replace(array("\n", "\r"), '', $udcontent_d);
    $bid = mysqli_real_escape_string($conn, $_POST['udbid']);
    $email = mysqli_real_escape_string($conn, $_POST['udemail']);
    $title = mysqli_real_escape_string($conn, $_POST['udtitle']);
    $content = mysqli_real_escape_string($conn, $udcontent_f);
    $reader = mysqli_real_escape_string($conn, $_POST['udreader']);
    $viewcnt = mysqli_real_escape_string($conn, $_POST['udviewcnt']);
    $udimagefile_old = mysqli_real_escape_string($conn, $_POST['udimagefile_old']);

    // upload image add start
    $noticeId = $bid;
    $fileExtension = pathinfo($_FILES["udimagefile_new"]["name"], PATHINFO_EXTENSION);
    $newFileName = generateUniqueFileName($IMAGE_UPLOAD_DIR, $fileExtension, $noticeId, $companyId);
    $originalFileName = $_FILES["udimagefile_new"]["name"];
    $uploadFile = $IMAGE_UPLOAD_DIR . $newFileName;
    $uploadOk = true;
    global $NOTICE_IMAGE_MAXSIZE;

    // Check file name is exists
    if (file_exists($uploadFile)) {
        error_log("File name is exists -> Delete old file name");
        unlink($uploadFile);
    }

    // check size 
    if (!isFileSizeValid($_FILES["udimagefile_new"], $NOTICE_IMAGE_MAXSIZE)) {
        error_log("File is BIG!");
        $uploadOk = false;
    }

    // check valid extention
    if (!empty($originalFileName)) {
        $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
        if (!checkValidExtension($fileExtension)) {
            error_log("Image only(jpg, jpeg, png, gif).");
            $uploadOk = false;
        }
    } else {
        $fileName = $udimagefile_old;
    }

    // if not error save
    if ($uploadOk) {
        if (move_uploaded_file($_FILES["udimagefile_new"]["tmp_name"], $uploadFile)) {
            deleteNoticeImages($IMAGE_UPLOAD_DIR, $noticeId, $newFileName);
            $fileName = $newFileName;
        } else {
            error_log("Upload Error");
        }

        $sql = "UPDATE tbl_notice SET  title='$title', content='$content', reader='$reader', imagefile='$fileName', 
        viewcnt='$viewcnt', upt_dt='$reg_dt' WHERE bid ='$bid' AND email ='$email'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['update_success'] = $update_success;
            header("Refresh:3");
        } else {
            error_log('query error: ' . mysqli_error($conn));
        }
    }
}

// check valid size
function isFileSizeValid($file, $maxSize)
{
    return $file["size"] <= $maxSize;
}
// check valid extention
function checkValidExtension($fEx)
{
    global $ALLOWED_TYPES;
    $validExtensions = array_map('strtolower', $ALLOWED_TYPES);
    $fEx = trim(strtolower($fEx));
    return in_array($fEx, $ALLOWED_TYPES);
}

// delete old image and image not in format 
// $newFileName is exception 
function deleteNoticeImages($uploadDir, $noticeId, $newFileName)
{
    global $LENGTH_RANDOM_UNIQUE_NAME;
    $files = scandir($uploadDir);
    foreach ($files as $file) {
        if ($file !== $newFileName && strpos($file, $noticeId) === 0) {
            $filePath = $uploadDir . $file;
            if (unlink($filePath)) {
                error_log("******Deleted file: " . $file);
            } else {
                error_log("****Failed to delete file:" . $file);
            }
        }
        if ($file !== $newFileName) {
            if (preg_match('/_' . preg_quote($noticeId, '/') . '_/', $file)) {
                $filePath = $uploadDir . $file;
                if (unlink($filePath)) {
                    error_log("******Deleted file: " . $file);
                } else {
                    error_log("****Failed to delete file:" . $file);
                }
            }
        }

        if (!preg_match('/_([a-zA-Z0-9]+)_\w{' . $LENGTH_RANDOM_UNIQUE_NAME . '}\.\w+/', $file)) {
            $filePath = $uploadDir . $file;
            unlink($filePath);
        }

        if (!ctype_alnum($file[0])) { // when start not number or word
            $filePath = $uploadDir . $file;
            if (unlink($filePath)) {
                error_log("******Deleted file: " . $file);
            } else {
                error_log("****Failed to delete file:" . $file);
            }
        }
    }
}

// generate file name 
function generateUniqueFileName($uploadDir, $fileEx, $noticeId, $companyId)
{
    global $LENGTH_RANDOM_UNIQUE_NAME;
    $uniqueFileName = generateRandomString($LENGTH_RANDOM_UNIQUE_NAME);
    $newFileName = $companyId . "_" . $noticeId . "_" . $uniqueFileName . '.' . $fileEx;
    while (file_exists($uploadDir . $newFileName)) {
        $uniqueFileName = generateRandomString($LENGTH_RANDOM_UNIQUE_NAME);
        $newFileName = $companyId . "_" . $noticeId . "_" . $uniqueFileName . '.' . $fileEx;
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

// Delete Data to tbl_notice DB 
if (isset($_POST['btnDelNL'])) {
    $bid = mysqli_real_escape_string($conn, $_POST['udbid']);
    $email = mysqli_real_escape_string($conn, $_POST['udemail']);
    $fileImgName = mysqli_real_escape_string($conn, $_POST['udimagefile_name']);
    $removeDir = $IMAGE_UPLOAD_DIR;
    $sql = "DELETE FROM `tbl_notice` 
    WHERE bid ='$bid' AND email ='$email'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_success'] = $delete_success;

        // when success   -> delete img 
        if (unlink($removeDir . $fileImgName)) {
            error_log("******Deleted file: " . $fileImgName);
        } else {
            error_log("****Failed to delete file:" . $fileImgName);
        }
        header("Refresh:3");
    } else {
        error_log('query error: ' . mysqli_error($conn));
    }
}


// codemasterList.php
// Select database from tbl_codetype table
$sql_codetype = 'SELECT * FROM `tbl_codetype` ORDER BY `tbl_codetype`.`typecode`';
$result_codetype = mysqli_query($conn, $sql_codetype);
$codetype_list = mysqli_fetch_all($result_codetype, MYSQLI_ASSOC);
$typecodes_all = array_column($codetype_list, 'typecode');

$codetype_list_a = array();
foreach ($codetype_list as $k => $v) {
    $codetype_list_a[] = $v['typecode'];
}
$codetype_list_a = array_unique($codetype_list_a);
$codetype_list_a_string = implode('", "', $codetype_list_a);

// Select database from tbl_codebase table
if ($_POST['typecode'] == NULL) {
    $_POST['typecode'] = $_SESSION['typecode'];
    $codetype_result = isset($_POST['typecode']) ? $_POST['typecode'] : $codetype_list_a_string;

    if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
        $sql_codebase = 'SELECT 
        `tbl_codebase`.*,
        `tbl_company`.`companyname`
        FROM `tbl_codebase`
        LEFT JOIN `tbl_company` ON `tbl_codebase`.`companyid` = `tbl_company`.`companyid`
        ORDER BY `tbl_codebase`.`companyid`';
    } else {
        $sql_codebase = 'SELECT * FROM `tbl_codebase`
        WHERE `tbl_codebase`.`companyid` IN ("' . constant('MAIN_COMPANY_ID') . '", "' . $_SESSION['auth_companyid'] . '")
        AND `tbl_codebase`.`typecode` IN ("' . $codetype_result . '")';
    }
} elseif (isset($_POST['typecode'])) {
    if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
        $sql_codebase = 'SELECT 
        `tbl_codebase`.*,
        `tbl_company`.`companyname`
        FROM `tbl_codebase`
        LEFT JOIN `tbl_company` ON `tbl_codebase`.`companyid` = `tbl_company`.`companyid`
        WHERE `tbl_codebase`.`typecode` IN ("' . $_POST['typecode'] . '")
        ORDER BY `tbl_codebase`.`companyid`';
    } else {
        $sql_codebase = 'SELECT * FROM `tbl_codebase`
        WHERE `tbl_codebase`.`companyid` IN ("' . $_SESSION['auth_companyid'] . '")
        AND `tbl_codebase`.`typecode` IN ("' . $_POST['typecode'] . '")';
    }
}
$result_codebase = mysqli_query($conn, $sql_codebase);
$codebase_list = mysqli_fetch_all($result_codebase, MYSQLI_ASSOC);
$sql_codebase_all = 'SELECT * FROM `tbl_codebase`';
$result_codebase_all = mysqli_query($conn, $sql_codebase_all);
$codebase_list_all = mysqli_fetch_all($result_codebase_all, MYSQLI_ASSOC);
$codes = array_column($codebase_list_all, 'code');

// Save Data to tbl_codebase DB 
if (isset($_POST['btnRegCML'])) {
    $companyid = $_SESSION['auth_companyid'];
    $typecode = $_SESSION['typecode'] = $_POST['typecode'];
    $uid = $_SESSION['auth_uid'];
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $remark = mysqli_real_escape_string($conn, $_POST['remark']);

    $sql = "INSERT INTO `tbl_codebase` (`companyid`, `typecode`, `code`, `name`, `remark`, `uid`, `reg_dt`)
                VALUES ('$companyid', '$typecode', '$code', '$name', '$remark', '$uid', '$reg_dt')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['save_success'] = $save_success;
        header("Refresh:3");
    } else {
        error_log('query error: ' . mysqli_error($conn));
    }
}

// Update Data to tbl_codebase DB 
if (isset($_POST['btnUpdateCML'])) {
    $_SESSION['typecode'] = $_POST['udtypecode'];
    $id = mysqli_real_escape_string($conn, $_POST['udid']);
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $uid = mysqli_real_escape_string($conn, $_POST['uduid']);
    $typecode = mysqli_real_escape_string($conn, $_POST['udtypecode']);
    $code = mysqli_real_escape_string($conn, $_POST['udcode']);
    $name = mysqli_real_escape_string($conn, $_POST['udname']);
    $remark = mysqli_real_escape_string($conn, $_POST['udremark']);

    $sql = "UPDATE tbl_codebase SET 
                name='$name',
                remark='$remark',
                upt_dt = '$reg_dt' 
            WHERE id ='$id'
            AND companyid ='$companyid'
            AND uid ='$uid'
            AND typecode ='$typecode'
            AND code ='$code'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] = $update_success;
        header("Refresh:3");
    } else {
        error_log('query error: ' . mysqli_error($conn));
    }
}

// Delete Data to tbl_codebase DB 
if (isset($_POST['btnDelCML'])) {
    $_SESSION['typecode'] = $_POST['udtypecode'];
    $id = mysqli_real_escape_string($conn, $_POST['udid']);
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $uid = mysqli_real_escape_string($conn, $_POST['uduid']);
    $typecode = mysqli_real_escape_string($conn, $_POST['udtypecode']);
    $code = mysqli_real_escape_string($conn, $_POST['udcode']);

    $sql = "DELETE FROM `tbl_codebase` 
    WHERE id ='$id' AND companyid ='$companyid' AND uid ='$uid' AND typecode ='$typecode' AND code ='$code'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_success'] = $delete_success;
        header("Refresh:3");
    } else {
        error_log('query error: ' . mysqli_error($conn));
    }
}


// codetypeList.php
// Save Data to tbl_codetype DB 
if (isset($_POST['btnRegCTL'])) {
    $typecode = mysqli_real_escape_string($conn, $_POST['typecode']);
    $typename = mysqli_real_escape_string($conn, $_POST['typename']);
    $typeremark = mysqli_real_escape_string($conn, $_POST['typeremark']);
    $sql = "INSERT INTO `tbl_codetype` (`typecode`, `typename`, `typeremark`, `reg_dt` , `upt_dt`)
                VALUES ('$typecode', '$typename', '$typeremark', '$reg_dt' , null)";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['save_success'] = $save_success;
        unset($_SESSION['typecode']);
        header("Refresh:3");
    } else {
        error_log('query error: ' . mysqli_error($conn));
    }
}

// Update Data to tbl_codetype DB 
if (isset($_POST['btnUpdateCTL'])) {
    $typecode = mysqli_real_escape_string($conn, $_POST['udtypecode']);
    $typename = mysqli_real_escape_string($conn, $_POST['udtypename']);
    $typeremark = mysqli_real_escape_string($conn, $_POST['udtyperemark']);

    $sql = "UPDATE tbl_codetype SET 
                typename='$typename',
                typeremark='$typeremark',
                upt_dt = '$upt_dt' 
            WHERE
                typecode ='$typecode'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] = $update_success;
        unset($_SESSION['typecode']);
        header("Refresh:3");
    } else {
        error_log('query error: ' . mysqli_error($conn));
    }
}

// Delete Data to tbl_codetype DB 
if (isset($_POST['btnDelCTL'])) {
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $typecode = mysqli_real_escape_string($conn, $_POST['udtypecode']);

    $sql = "DELETE FROM `tbl_codetype` 
            WHERE 
                companyid ='$companyid'
            AND
                typecode ='$typecode'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_success'] = $delete_success;
        unset($_SESSION['typecode']);
        header("Refresh:3");
    } else {
        error_log('query error: ' . mysqli_error($conn));
    }
}
