<?php
$reg_dt = date('Y-m-d H:i:s');
$upt_dt = date('Y-m-d H:i:s');


// manageInfo.php
// Select database from tbl_manageinfo table
if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
    $sql_manageinfo = 'SELECT DISTINCT
    `tbl_manageinfo`.*,
    `tbl_company`.`companyname`
    FROM `tbl_manageinfo` 
    LEFT JOIN `tbl_company` ON `tbl_manageinfo`.`companyid` = `tbl_company`.`companyid`
    ORDER BY `tbl_manageinfo`.`companyid`';
} else {
    $sql_manageinfo = 'SELECT DISTINCT
    `tbl_manageinfo`.*,
    `tbl_company`.`companyname`
    FROM `tbl_manageinfo` 
    LEFT JOIN `tbl_company` ON `tbl_manageinfo`.`companyid` = `tbl_company`.`companyid`
    WHERE `tbl_manageinfo`.`companyid` IN("' . $_SESSION['auth_companyid'] . '")
    ORDER BY `tbl_manageinfo`.`companyid`';
}

$result_mi = $conn->query($sql_manageinfo);
$last_companyID_mi = null;

if ($result_mi->num_rows > 0) {
    while ($row = $result_mi->fetch_assoc()) {
        $last_companyID_mi = $row["companyid"];
    }
}
$new_companyID_mi = $last_companyID_mi + 1;

$result_manageinfo = mysqli_query($conn, $sql_manageinfo);
$manageinfo_list = mysqli_fetch_all($result_manageinfo, MYSQLI_ASSOC);




// Save data to tbl_manageinfo table of database(MAIN_ADMIN)
if (isset($_POST['btnRegMMI'])) {
    $companyid = mysqli_real_escape_string($conn, $_POST['companyid']);
    $companyname = mysqli_real_escape_string($conn, $_POST['companyname']);
    $magamYm = substr($_POST['magamym'], 0, 7);
    $magamym = mysqli_real_escape_string($conn, $magamYm);
    $magamymd = mysqli_real_escape_string($conn, $_POST['magamymd']);
    $kyukatimelimit = mysqli_real_escape_string($conn, $_POST['kyukatimelimit']);

    $sql = "INSERT INTO `tbl_manageinfo` (`companyid`, `magamym`, `magamymd`, `kyukatimelimit`, `reg_dt` , `upt_dt`)
                VALUES ('$companyid', '$magamym', '$magamymd', '$kyukatimelimit', '$reg_dt' , null)
            ON DUPLICATE KEY UPDATE
            `magamym` = '$magamym', `magamymd` = '$magamymd', `kyukatimelimit` = '$kyukatimelimit', `upt_dt` = '$upt_dt'";

    $sql2 = "INSERT INTO `tbl_company` (`companyid`, `companyname`, `reg_dt` , `upt_dt`)
                VALUES ('$companyid', '$companyname', '$reg_dt' , null)
            ON DUPLICATE KEY UPDATE
            `companyname` = '$companyname', `upt_dt` = '$upt_dt'";

    if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
        $_SESSION['save_success'] =  $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update data to tbl_manageinfo table of database(ADMIN)
if (isset($_POST['btnRegMi'])) {
    $magamYm = substr($_POST['magamYm'], 0, 7);
    $companyid = mysqli_real_escape_string($conn, $_POST['companyid']);
    $magamym = mysqli_real_escape_string($conn, $magamYm);
    $magamymd = mysqli_real_escape_string($conn, $_POST['magamYmd']);
    $kyukatimelimit = mysqli_real_escape_string($conn, $_POST['kyukatimelimit']);

    $sql = "UPDATE tbl_manageinfo SET 
                magamym='$magamym',
                magamymd='$magamymd',
                kyukatimelimit='$kyukatimelimit',
                upt_dt='$upt_dt'
            WHERE companyid ='$companyid'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] =  $update_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update data to tbl_manageinfo table of database(MAIN_ADMIN)
if (isset($_POST['btnUpdateMMI'])) {
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $companyname = mysqli_real_escape_string($conn, $_POST['udcompanyname']);
    $magamYm = substr($_POST['udmagamym'], 0, 7);
    $magamym = mysqli_real_escape_string($conn, $magamYm);
    $magamymd = mysqli_real_escape_string($conn, $_POST['udmagamymd']);
    $kyukatimelimit = mysqli_real_escape_string($conn, $_POST['udkyukatimelimit']);

    $sql = "UPDATE tbl_manageinfo SET 
        magamym='$magamym',
        magamymd='$magamymd',
        kyukatimelimit='$kyukatimelimit',
        upt_dt='$upt_dt'
    WHERE companyid ='$companyid'";

    $sql2 = "UPDATE tbl_company SET 
        companyname='$companyname',
        upt_dt='$upt_dt'
    WHERE companyid ='$companyid'";


    if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
        $_SESSION['update_success'] = $update_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// companyList.php
// Select database from tbl_company table
if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
    $sql_company_select = 'SELECT * FROM `tbl_company` ORDER BY `tbl_company`.`companyid`';
} elseif ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) {
    $sql_company_select = 'SELECT * FROM `tbl_company` WHERE `tbl_company`.`companyid` 
                            IN("' . $_SESSION['auth_companyid'] . '") ORDER BY `tbl_company`.`companyid`';
}

$result_cl = $conn->query($sql_company_select);
$last_companyID_cl = null;

if ($result_cl->num_rows > 0) {
    while ($row = $result_cl->fetch_assoc()) {
        $last_companyID_cl = $row["companyid"];
    }
}
$new_companyID_cl = $last_companyID_cl + 1;

$result_company_select = mysqli_query($conn, $sql_company_select);
$company_list_select = mysqli_fetch_all($result_company_select, MYSQLI_ASSOC);

// Search Data tbl_company
if ($_POST['SearchButtonCL'] == NULL) {
    $company_list = $company_list_select;
} elseif (isset($_POST['SearchButtonCL'])) {
    if ($_SESSION['auth_type'] !== constant('MAIN_ADMIN')) {
        echo 'not admin: ' . mysqli_error($conn);
        return;
    }

    if (!empty($company_list_select)) {
        foreach ($company_list_select as $key) {
            $Companyname[] = $key['companyname'];
            $Use_yn[] = $_POST['searchUseyn'];
        }
    }
    $Companyname = array_unique($Companyname);
    $Use_yn = array_unique($Use_yn);
    $sql_company = 'SELECT *
    FROM `tbl_company`  ';
    if ($_POST['searchCompanyname'] !== "") {
        $searchCompanyname = trim($_POST['searchCompanyname']);
        $sql_company .= ' WHERE `tbl_company`.`companyname` LIKE "%' . $searchCompanyname . '%" ';
    }
    if ($_POST['searchUseyn'] == "0") {
        $searchUse_yn = implode('","', $Use_yn);
    } elseif ($_POST['searchUseyn'] == "1" || $_POST['searchUseyn'] == "2") {
        if ($_POST['searchCompanyname'] == "") {
            $sql_company .= ' WHERE ';
        } else {
            $sql_company .= ' AND ';
        }
        $searchResult = '0';
        if ($_POST['searchUseyn'] == "1") {
            $searchResult = '1';
        }
        if ($_POST['searchUseyn'] == "2") {
            $searchResult = '0';
        }
        $sql_company .= '`tbl_company`.`use_yn` = "' . $searchResult . '"';
    }
    $result_company = mysqli_query($conn, $sql_company);
    $company_list = mysqli_fetch_all($result_company, MYSQLI_ASSOC);
}

// Save Data to tbl_company
if (isset($_POST['btnRegCL'])) {
    $companyid = mysqli_real_escape_string($conn, $_POST['companyid']);
    $companycode = mysqli_real_escape_string($conn, $_POST['companycode']);
    $companyname = mysqli_real_escape_string($conn, $_POST['companyname']);
    $staff = mysqli_real_escape_string($conn, $_POST['staff']);
    $telno = mysqli_real_escape_string($conn, $_POST['telno']);
    $strymd = mysqli_real_escape_string($conn, $_POST['strymd']);
    $endymd = mysqli_real_escape_string($conn, $_POST['endymd']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $use_yn = mysqli_real_escape_string($conn, $_POST['use_yn']);
    $joken = mysqli_real_escape_string($conn, $_POST['joken']);
    $bigo = mysqli_real_escape_string($conn, $_POST['bigo']);
    $template = mysqli_real_escape_string($conn, $_POST['use_type']);


    $sql = "INSERT INTO `tbl_company` (`companyid`, `companycode`, `companyname`, `staff`, `telno`,
                `strymd`, `endymd`, `address`, `use_yn`,`template` ,  `joken`, `bigo`, `reg_dt` ,`upt_dt` )
                VALUES ('$companyid', '$companycode', '$companyname', '$staff', '$telno',
                '$strymd', '$endymd', '$address', '$use_yn', '$template' , '$joken', '$bigo', '$reg_dt' , null)
            ON DUPLICATE KEY UPDATE
                `companycode` = '$companycode', `companyname` = '$companyname', `staff` = '$staff',
                `telno` = '$telno', `strymd` = '$strymd', `endymd` = '$endymd', `address` = '$address',
                `use_yn` = '$use_yn', `template`  = '$template'  , `joken` = '$joken', `bigo` = '$bigo', `upt_dt` = '$upt_dt'";


    $sql2 = "INSERT INTO `tbl_manageinfo` (`companyid`, `reg_dt` ,`upt_dt` )
                VALUES ('$companyid', '$reg_dt' , null)
            ON DUPLICATE KEY UPDATE
                `upt_dt` = '$upt_dt'";
    if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
        $_SESSION['save_success'] =  $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update Data to tbl_company
if (isset($_POST['btnUpdateCL'])) {
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $companycode = mysqli_real_escape_string($conn, $_POST['udcompanycode']);
    $companyname = mysqli_real_escape_string($conn, $_POST['udcompanyname']);
    $staff = mysqli_real_escape_string($conn, $_POST['udstaff']);
    $telno = mysqli_real_escape_string($conn, $_POST['udtelno']);
    $strymd = mysqli_real_escape_string($conn, $_POST['udstrymd']);
    $endymd = mysqli_real_escape_string($conn, $_POST['udendymd']);
    $address = mysqli_real_escape_string($conn, $_POST['udaddress']);
    $use_yn = mysqli_real_escape_string($conn, $_POST['uduse_yn']);
    $joken = mysqli_real_escape_string($conn, $_POST['udjoken']);
    $bigo = mysqli_real_escape_string($conn, $_POST['udbigo']);
    $template = mysqli_real_escape_string($conn, $_POST['use_type']);

    $sql = "UPDATE tbl_company SET 
            companyname='$companyname',
            staff='$staff',
            telno='$telno',
            address='$address',
            bigo='$bigo',
            companycode ='$companycode'
        WHERE companyid ='$companyid'";

    // check  main admin
    if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
        $sql = "UPDATE tbl_company SET 
            companyname='$companyname',
            staff='$staff',
            telno='$telno',
            strymd='$strymd',
            endymd='$endymd',
            address='$address',
            use_yn='$use_yn',
            joken='$joken',
            bigo='$bigo',
            companycode ='$companycode',
            template = '$template'

        WHERE companyid ='$companyid'";
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] = $update_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Delete data to tbl_company table of database
if (isset($_POST['DeleteCL'])) {
    // check  main admin
    if ($_SESSION['auth_type'] !== constant('MAIN_ADMIN')) {
        echo 'not admin: ' . mysqli_error($conn);
        return;
    }

    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $companycode = mysqli_real_escape_string($conn, $_POST['udcompanycode']);

    $sql = "DELETE FROM `tbl_company` 
            WHERE companyid ='$companyid' AND companycode ='$companycode'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_success'] = $delete_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// adminList.php
// Select data from tbl_codebase
$sql_codebase = 'SELECT `code`, `name` FROM `tbl_codebase`
WHERE `tbl_codebase`.`typecode` = "' . constant('DEPARTMENT') . '"
AND `tbl_codebase`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
GROUP BY `code`, `name`';
$result_codebase = mysqli_query($conn, $sql_codebase);
$codebase_list = mysqli_fetch_all($result_codebase, MYSQLI_ASSOC);

// Select database from tbl_user table
$sql_admin_select = 'SELECT DISTINCT
    `tbl_user`.*,
    `tbl_company`.`companyname`
    FROM `tbl_user`
    LEFT JOIN `tbl_company` ON `tbl_user`.`companyid` = `tbl_company`.`companyid`
    WHERE `tbl_user`.`type` IN ("' . constant('ADMIN') . '", "' . constant('ADMINISTRATOR') . '")
    AND `tbl_user`.`companyid` IN ("' . $_SESSION['auth_companyid'] . '")';
$result_admin_select = mysqli_query($conn, $sql_admin_select);
$admin_list_select = mysqli_fetch_all($result_admin_select, MYSQLI_ASSOC);

// Search Data tbl_company
if ($_POST['SearchButtonAM'] == NULL) {
    $admin_list = $admin_list_select;
} elseif (isset($_POST['SearchButtonAM'])) {
    if (!empty($admin_list_select)) {
        foreach ($admin_list_select as $key) {
            $AdminName[] = $key['name'];
            $AdminGrade[] = $key['grade'];
        }
    }
    $AdminName = array_unique($AdminName);
    $AdminGrade = array_unique($AdminGrade);

    $searchAdminName = isset($_POST['searchAdminName']) ? trim($_POST['searchAdminName']) : '';
    $searchAdminGrade = isset($_POST['searchAdminGrade']) ? trim($_POST['searchAdminGrade']) : '';

    if (empty($searchAdminName) && empty($searchAdminGrade)) {
        $admin_list = $admin_list_select; // No filtering needed, display all data
    } else {
        $whereClause = array();

        if (!empty($searchAdminName)) {
            $whereClause[] = '`tbl_user`.`name` LIKE "%' . $searchAdminName . '%"';
        }

        if (!empty($searchAdminGrade)) {
            $searchAdminGradeArray = explode(',', $searchAdminGrade);
            $gradeConditions = array();
            foreach ($searchAdminGradeArray as $grade) {
                $gradeConditions[] = '"' . $grade . '"';
            }
            $whereClause[] = '`tbl_user`.`grade` IN (' . implode(',', $gradeConditions) . ')';
        }

        $sql_admin = 'SELECT * FROM `tbl_user` WHERE ' . implode(' AND ', $whereClause);
        $result_admin = mysqli_query($conn, $sql_admin);
        $admin_list = mysqli_fetch_all($result_admin, MYSQLI_ASSOC);
    }
}


// Save Data to tbl_user
if (isset($_POST['btnRegAM'])) {
    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $dept = mysqli_real_escape_string($conn, $_POST['dept']);
    $companyid = mysqli_real_escape_string($conn, $_POST['companyid']);
    $bigo = mysqli_real_escape_string($conn, $_POST['bigo']);
    $inymd = "";
    $outymd = "";
    $type = constant('ADMINISTRATOR');

    $fileExtension = pathinfo($_FILES["signstamp"]["name"], PATHINFO_EXTENSION);
    $newFileName = generateUniqueFileName($IMAGE_UPLOAD_DIR_STAMP, $fileExtension, $uid, $companyid);
    $originalFileName = $_FILES["signstamp"]["name"];
    $uploadFile = $IMAGE_UPLOAD_DIR_STAMP . $newFileName;
    $uploadOk = true;
    global $STAMP_MAXSIZE;

    if ($originalFileName == "") {
        $fileName == '';
    } else {
        // Check file name is exists
        if (file_exists($uploadFile)) {
            error_log("File name is exists -> Delete old file name");
            unlink($uploadFile);
        }
        // check size 
        if (!isFileSizeValid($_FILES["signstamp"], $STAMP_MAXSIZE)) {
            error_log("File is BIG!");
            $uploadOk = false;
        }
        // check valid extention 
        $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
        if (!checkValidExtension($fileExtension)) {

            error_log("Image only(png)." . $fileExtension);
            error_log("FileName" . $originalFileName);
            $uploadOk = false;
        }
    }
    // if not error save
    if ($uploadOk) {
        $fileName = $newFileName;
        // upload to server
        if (move_uploaded_file($_FILES["signstamp"]["tmp_name"], $uploadFile)) {
            deleteNoticeImages($IMAGE_UPLOAD_DIR_STAMP, $uid, $newFileName);
        } else {
            error_log("Upload Error");
        }

        // insert to DB 
        $sql_user_insert = "INSERT INTO `tbl_user` (`uid`, `companyid`, `pwd`, `name`, `grade`, `type`
        , `signstamp`, `email`, `dept`, `bigo`, `inymd`, `outymd`, `reg_dt` , `upt_dt`) 
         VALUES('$uid', '$companyid' ,'$pwd' ,'$name', '$grade', '$type'
        , '$fileName', '$email', '$dept', '$bigo', '$inymd', '$outymd', '$reg_dt' , null )";

        if ($conn->query($sql_user_insert) === TRUE) {
            $_SESSION['save_success'] = $save_success;
            header("Refresh:3");
        } else {
            echo 'query error: ' . mysqli_error($conn);
        }
    }
}

// Update Data to tbl_user
if (isset($_POST['btnUpdateAM'])) {
    $uid = mysqli_real_escape_string($conn, $_POST['uduid']);
    $pwd = mysqli_real_escape_string($conn, $_POST['udpwd']);
    $name = mysqli_real_escape_string($conn, $_POST['udname']);
    $grade = mysqli_real_escape_string($conn, $_POST['udgrade']);
    $email = mysqli_real_escape_string($conn, $_POST['udemail']);
    $dept = mysqli_real_escape_string($conn, $_POST['uddept']);
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $bigo = mysqli_real_escape_string($conn, $_POST['udbigo']);
    $udsignstamp_old = mysqli_real_escape_string($conn, $_POST['udsignstamp_old']);

    $fileExtension = pathinfo($_FILES["udsignstamp_new"]["name"], PATHINFO_EXTENSION);
    $newFileName = generateUniqueFileName($IMAGE_UPLOAD_DIR_STAMP, $fileExtension, $uid, $companyid);
    $originalFileName = $_FILES["udsignstamp_new"]["name"];
    $uploadFile = $IMAGE_UPLOAD_DIR_STAMP . $newFileName;
    $uploadOk = true;
    global $STAMP_MAXSIZE;

    // Check file name is exists
    if (file_exists($uploadFile)) {
        error_log("File name is exists -> Delete old file name");
        unlink($uploadFile);
    }

    // check size 
    if (!isFileSizeValid($_FILES["udsignstamp_new"], $STAMP_MAXSIZE)) {
        error_log("File is BIG!");
        $uploadOk = false;
    }

    // check valid extention 
    if (!empty($originalFileName)) {
        $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
        if (!checkValidExtension($fileExtension)) {
            error_log("Image only(png).");
            $uploadOk = false;
        }
    } else {
        $fileName = $udsignstamp_old;
    }

    // if not error save
    if ($uploadOk) {
        if (move_uploaded_file($_FILES["udsignstamp_new"]["tmp_name"], $uploadFile)) {
            deleteNoticeImages($IMAGE_UPLOAD_DIR_STAMP, $uid, $newFileName);
            $fileName = $newFileName;
        } else {
            error_log("Upload Error");
        }
        $sql = "UPDATE tbl_user SET  
        companyid='$companyid', pwd='$pwd', name='$name', grade='$grade', signstamp='$fileName', 
        email='$email', dept='$dept', bigo='$bigo', upt_dt='$upt_dt' WHERE uid ='$uid'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['update_success'] = $update_success;
            header("Refresh:3");
        } else {
            echo 'query error: ' . mysqli_error($conn);
        }
    }
}

// Delete data to tbl_user table of database
if (isset($_POST['DeleteAM'])) {
    $uid = mysqli_real_escape_string($conn, $_POST['uduid']);
    $filePath = $IMAGE_UPLOAD_DIR_STAMP . $_POST['udsignstamp_old'];

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    $sql = "DELETE FROM `tbl_user` 
            WHERE uid ='$uid'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_success'] = $delete_success;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
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
    global $ALLOWED_TYPES_STAMP;
    $validExtensions = array_map('strtolower', $ALLOWED_TYPES_STAMP);
    $fEx = trim(strtolower($fEx));
    return in_array($fEx, $ALLOWED_TYPES_STAMP);
}

// delete old image and image not in format 
// when 1_97_8372891372819.jpgは $newFileNameなら 1_97_xxxxxxxxxxxxx.jpg を削除する 
function deleteNoticeImages($uploadDir, $uId, $newFileName)
{
    global $LENGTH_RANDOM_UNIQUE_NAME_STAMP;
    $files = scandir($uploadDir);
    foreach ($files as $file) {
        if ($file !== $newFileName && strpos($file, $uId) === 0) {
            $filePath = $uploadDir . $file;
            if (unlink($filePath)) {
                error_log("******Deleted file: " . $file);
            } else {
                error_log("****Failed to delete file:" . $file);
            }
        }
        if ($file !== $newFileName) {
            if (preg_match('/_' . preg_quote($uId, '/') . '_/', $file)) {
                error_log("***------------***FILE: " . $file);
                $filePath = $uploadDir . $file;
                if (unlink($filePath)) {
                    error_log("******Deleted file: " . $file);
                } else {
                    error_log("****Failed to delete file:" . $file);
                }
            }
        }
        if (!preg_match('/_([a-zA-Z0-9]+)_\w{' . $LENGTH_RANDOM_UNIQUE_NAME_STAMP . '}\.\w+/', $file)) {
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
function generateUniqueFileName($uploadDir, $fileEx, $uId, $companyId)
{
    global $LENGTH_RANDOM_UNIQUE_NAME_STAMP;
    $uniqueFileName = generateRandomString($LENGTH_RANDOM_UNIQUE_NAME_STAMP);
    $newFileName = $companyId . "_" . $uId . "_" . $uniqueFileName . '.' . $fileEx;
    while (file_exists($uploadDir . $newFileName)) {
        $uniqueFileName = generateRandomString($LENGTH_RANDOM_UNIQUE_NAME_STAMP);
        $newFileName = $companyId . "_" . $uId . "_" . $uniqueFileName . '.' . $fileEx;
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
// 2023-10-11/1340-006
// upload image add end