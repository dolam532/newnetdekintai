<?php
$reg_dt = date('Y-m-d H:i:s');
$upt_dt = date('Y-m-d H:i:s');

// userloginList.php
$year = isset($_POST["selyy"]) ? $_POST["selyy"] : date('Y');
$month = isset($_POST["selmm"]) ? $_POST["selmm"] : date('m');
$day = isset($_POST["seldd"]) ? $_POST["seldd"] : date('d');

// Select database from tbl_userlogin table
if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
    $sql_userlogin = 'SELECT * FROM `tbl_userlogin` 
    WHERE YEAR(`tbl_userlogin`.`workymd`) IN("' . $year . '")
    AND MONTH(`tbl_userlogin`.`workymd`) IN("' . $month . '")
    AND DAY(`tbl_userlogin`.`workymd`) IN("' . $day . '")
    AND `tbl_userlogin`.`logtype` IN("' . constant('USER') . '", "' . constant('ADMIN') . '")';
    $result_userlogin = mysqli_query($conn, $sql_userlogin);
    $userlogin_list = mysqli_fetch_all($result_userlogin, MYSQLI_ASSOC);
} elseif ($_SESSION['auth_type'] == constant('USER')) {
    $sql_userlogin = 'SELECT * FROM `tbl_userlogin` 
    WHERE YEAR(`tbl_userlogin`.`workymd`) IN("' . $year . '")
    AND MONTH(`tbl_userlogin`.`workymd`) IN("' . $month . '")
    AND DAY(`tbl_userlogin`.`workymd`) IN("' . $day . '")
    AND `tbl_userlogin`.`uid` IN("' . $_SESSION['auth_uid'] . '")
    AND `tbl_userlogin`.`logtype` IN("' . constant('USER') . '", "' . constant('ADMIN') . '")';
    $result_userlogin = mysqli_query($conn, $sql_userlogin);
    $userlogin_list = mysqli_fetch_all($result_userlogin, MYSQLI_ASSOC);

    
}

// 2023-10-09/1340-004 add start
// get company id from loginned user id 
$uid = $_SESSION['auth_uid'];
$stmt = $conn->prepare("SELECT companyid FROM tbl_user WHERE uid = ?");
$stmt->bind_param("s", $uid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$companyId = $row['companyid'];
$stmt->close();
if ($companyId == "" || $companyId == null) {
    $companyId = "x_xCompanyErrorx_xUid:" . $uid . "x_x";
}
// 2023-10-09/1340-004-add end

// 2023-10-09/1340-003 change start
// noticeList.php
// Select database from tbl_notice table
global $IMAGE_UPLOAD_DIR;
$sql_notice_select = 'SELECT DISTINCT
`tbl_notice`.*,
`tbl_user`.`name`,
`tbl_user`.`companyid`
FROM `tbl_notice`
LEFT JOIN `tbl_user` ON `tbl_notice`.`uid` = `tbl_user`.`uid`
ORDER BY `tbl_notice`.`bid`';
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
    $sql_notice = 'SELECT DISTINCT
    `tbl_notice`.*,
    `tbl_user`.`name`,
    `tbl_user`.`companyid`
    FROM `tbl_notice`
    LEFT JOIN `tbl_user` ON `tbl_notice`.`uid` = `tbl_user`.`uid`
    WHERE (`tbl_notice`.`title` LIKE "' . $searchTitle . '" OR `tbl_notice`.`content` LIKE "' . $searchContent . '")';

    $result_notice = mysqli_query($conn, $sql_notice);
    $notice_list_ = mysqli_fetch_all($result_notice, MYSQLI_ASSOC);
} else {
    $notice_list_ = $notice_list_select;
}
$notice_list = array();
foreach ($notice_list_ as $k => $v) {
    if ($v['companyid'] == $_SESSION['auth_companyid']) {
        $notice_list[] = $v;
    }
}

// 2023-10-09/1340-003 change end
// Save Data to tbl_notice DB 
if (isset($_POST['btnRegNL'])) {
    $content_d = $_POST['content'];
    $content_f = str_replace(array("\n", "\r"), '', $content_d);
    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $content_f);
    $reader = mysqli_real_escape_string($conn, $_POST['reader']);
    $viewcnt = mysqli_real_escape_string($conn, $_POST['viewcnt']);
    $reg_dt = mysqli_real_escape_string($conn, $_POST['reg_dt']);

    // 2023-10-09/1340-004
    // upload image chg start
    $noticeId = $bid;
    $fileExtension_add = pathinfo($_FILES["imagefile"]["name"], PATHINFO_EXTENSION);
    $newFileName = generateUniqueFileName($IMAGE_UPLOAD_DIR, $fileExtension_add, $noticeId, $companyId);
    $originalFileName = $_FILES["imagefile"]["name"];

    $uploadOk = true;
    global $NOTICE_IMAGE_MAXSIZE;

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

    // if not error change name 
    if ($uploadOk) {
        $fileName = $newFileName;
        $sql = "INSERT INTO `tbl_notice` (`title`, `content`, `imagefile`, `reader`, `viewcnt`, `uid`, `reg_dt`)
             VALUES ('$title', '$content', '$fileName', '$reader', '$viewcnt', '$uid', '$reg_dt')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['save_success'] = $save_success;

            // set id to image 
            $insertedId = mysqli_insert_id($conn);
            $fileName = str_replace('__', '_' . $insertedId . '_', $fileName);
            $updateSql = "UPDATE tbl_notice SET `imagefile` = '$fileName' WHERE `bid` = $insertedId";
            if ($conn->query($updateSql) === TRUE) {
                $_SESSION['save_success'] = $save_success;
                header("Refresh:3");
            } else {
                error_log('query error: ' . mysqli_error($conn));
            }

            // upload file 
            if ($uploadOk) {
                $uploadFile = $IMAGE_UPLOAD_DIR . $fileName;
                if (move_uploaded_file($_FILES["imagefile"]["tmp_name"], $uploadFile)) {
                    deleteNoticeImages($IMAGE_UPLOAD_DIR, $noticeId, $fileName);
                } else {
                    error_log("Upload Error");
                }
            }
        } else {
            error_log('query error: ' . mysqli_error($conn));
        }
    }
}
// 2023-10-09/1340-004
// insert image chg end

// Update Data to tbl_notice DB 
if (isset($_POST['btnUpdateNL'])) {
    $udcontent_d = $_POST['udcontent'];
    $udcontent_f = str_replace(array("\n", "\r"), '', $udcontent_d);
    $bid = mysqli_real_escape_string($conn, $_POST['udbid']);
    $uid = mysqli_real_escape_string($conn, $_POST['uduid']);
    $title = mysqli_real_escape_string($conn, $_POST['udtitle']);
    $content = mysqli_real_escape_string($conn, $udcontent_f);
    $reader = mysqli_real_escape_string($conn, $_POST['udreader']);
    $viewcnt = mysqli_real_escape_string($conn, $_POST['udviewcnt']);
    $udimagefile_old = mysqli_real_escape_string($conn, $_POST['udimagefile_old']);

    // 2023-10-09/1340-004
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

        $sql = "UPDATE tbl_notice SET  title='$title', content='$content', reader='$reader', imagefile='$fileName'
        , viewcnt='$viewcnt', upt_dt='$reg_dt' WHERE bid ='$bid' AND uid ='$uid'";

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
// 2023-10-09/1340-004
// upload image add end

// 023-10-09/1340-004
// delete notice change start
// Delete Data to tbl_notice DB 
if (isset($_POST['btnDelNL'])) {
    $bid = mysqli_real_escape_string($conn, $_POST['udbid']);
    $uid = mysqli_real_escape_string($conn, $_POST['uduid']);
    $fileImgName = mysqli_real_escape_string($conn, $_POST['udimagefile_name']);
    $removeDir = $IMAGE_UPLOAD_DIR;
    $sql = "DELETE FROM `tbl_notice` 
    WHERE bid ='$bid' AND uid ='$uid'";
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
$sql_codetype = 'SELECT * FROM `tbl_codetype`';
$result_codetype = mysqli_query($conn, $sql_codetype);
$codetype_list = mysqli_fetch_all($result_codetype, MYSQLI_ASSOC);
$typecodes = array_column($codetype_list, 'typecode');

$codetype_list_a = array();
foreach ($codetype_list as $k => $v) {
    $codetype_list_a[] = $v['typecode'];
}
$codetype_list_a = array_unique($codetype_list_a);
$codetype_list_a_string = "'" . implode("','", $codetype_list_a) . "'";

// Select database from tbl_codebase table
$codes;
if ($_POST['typecode'] == NULL) {
    $_POST['typecode'] = $_SESSION['typecode'];
    if ($_SESSION['typecode'] == '01') {
        $sql_codebase = "SELECT * FROM `tbl_codebase`
        WHERE `tbl_codebase`.`companyid` = '{$_SESSION['auth_companyid']}'
        AND `tbl_codebase`.`typecode` IN ('01')";
    } elseif ($_SESSION['typecode'] == '02') {
        $sql_codebase = "SELECT * FROM `tbl_codebase`
        WHERE `tbl_codebase`.`companyid` = '{$_SESSION['auth_companyid']}'
        AND `tbl_codebase`.`typecode` IN ('02')";
    } else {
        $sql_codebase = "SELECT * FROM `tbl_codebase`
        WHERE `tbl_codebase`.`companyid` = '{$_SESSION['auth_companyid']}'
        AND `tbl_codebase`.`typecode` IN ({$codetype_list_a_string})";
    }
    $result_codebase = mysqli_query($conn, $sql_codebase);
    $codebase_list = mysqli_fetch_all($result_codebase, MYSQLI_ASSOC);
    $codes = array_column($codebase_list, 'code');
} elseif (isset($_POST['typecode'])) {
    $sql_codebase = 'SELECT * FROM `tbl_codebase`
        WHERE `tbl_codebase`.`companyid` IN ("' . $_SESSION['auth_companyid'] . '")
        AND `tbl_codebase`.`typecode` IN ("' . $_POST['typecode'] . '")';
    $result_codebase = mysqli_query($conn, $sql_codebase);
    $codebase_list = mysqli_fetch_all($result_codebase, MYSQLI_ASSOC);
    $codes = array_column($codebase_list, 'code');
}

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
// Save Data to tbl_codebase DB 
if (isset($_POST['btnRegCTL'])) {
    $typecode = mysqli_real_escape_string($conn, $_POST['typecode']);
    $typename = mysqli_real_escape_string($conn, $_POST['typename']);
    $typeremark = mysqli_real_escape_string($conn, $_POST['typeremark']);
    $sql = "INSERT INTO `tbl_codetype` (`typecode`, `typename`, `typeremark`, `reg_dt`, `upt_dt`)
                VALUES ('$typecode', '$typename', '$typeremark', '$reg_dt', '$upt_dt')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['save_success'] = $save_success;
        header("Refresh:3");
    } else {
        error_log('query error: ' . mysqli_error($conn));
    }
}