<?php
$reg_dt = date('Y-m-d H:i:s');
$upt_dt = date('Y-m-d H:i:s');
$magamym = date('Y/m');
$magamymd = date('Y/m/d');


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

// Select data from tbl_genba
$sql_genba = 'SELECT * FROM `tbl_genba` WHERE `companyid` IN ("' . $_SESSION['auth_companyid'] . '", "' . constant('MAIN_COMPANY_ID') . '" )';
$result_genba = mysqli_query($conn, $sql_genba);
$genba_list_db = mysqli_fetch_all($result_genba, MYSQLI_ASSOC);


// Save data to tbl_manageinfo table of database(MAIN_ADMIN)
if (isset($_POST['btnRegMMI'])) {
    $companyid = mysqli_real_escape_string($conn, $_POST['companyid']);
    $companyname = mysqli_real_escape_string($conn, $_POST['companyname']);
    $magamYm = substr($_POST['magamym'], 0, 7);
    $magamym = mysqli_real_escape_string($conn, $magamYm);
    $magamymd = mysqli_real_escape_string($conn, $_POST['magamymd']);

    $sql = "INSERT INTO `tbl_manageinfo` (`companyid`, `magamym`, `magamymd`, `reg_dt`, `upt_dt`)
                VALUES ('$companyid', '$magamym', '$magamymd', '$reg_dt', null)
            ON DUPLICATE KEY UPDATE
            `magamym` = '$magamym', `magamymd` = '$magamymd', `upt_dt` = '$upt_dt'";

    $sql2 = "INSERT INTO `tbl_company` (`companyid`, `companyname`, `reg_dt` , `upt_dt`)
                VALUES ('$companyid', '$companyname', '$reg_dt' , null)
            ON DUPLICATE KEY UPDATE
            `companyname` = '$companyname', `upt_dt` = '$upt_dt'";

    if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
        $_SESSION['save_success'] = $save_success;
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

    $sql = "UPDATE tbl_manageinfo SET 
                magamym='$magamym',
                magamymd='$magamymd',
                upt_dt='$upt_dt'
            WHERE companyid ='$companyid'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] = $update_success;
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

    $sql = "UPDATE tbl_manageinfo SET 
        magamym='$magamym',
        magamymd='$magamymd',
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
    $template = mysqli_real_escape_string($conn, $_POST['use_type']);
    $bigo = mysqli_real_escape_string($conn, $_POST['bigo']);

    $sql = "INSERT INTO `tbl_company` (`companyid`, `companycode`, `companyname`, `staff`, `telno`,
                `strymd`, `endymd`, `address`, `use_yn`, `template`, `joken`, `bigo`, `reg_dt` ,`upt_dt` )
                VALUES ('$companyid', '$companycode', '$companyname', '$staff', '$telno',
                '$strymd', '$endymd', '$address', '$use_yn', '$template', '$joken', '$bigo', '$reg_dt' , null)
            ON DUPLICATE KEY UPDATE
                `companycode` = '$companycode', `companyname` = '$companyname', `staff` = '$staff',
                `telno` = '$telno', `strymd` = '$strymd', `endymd` = '$endymd', `address` = '$address',
                `use_yn` = '$use_yn', `template`  = '$template', `joken` = '$joken', `bigo` = '$bigo', `upt_dt` = '$upt_dt'";


    $sql2 = "INSERT INTO `tbl_manageinfo` (`companyid`, `magamym`, `magamymd`, `reg_dt` ,`upt_dt` )
                VALUES ('$companyid', '$magamym', '$magamym', '$reg_dt', null)
            ON DUPLICATE KEY UPDATE
                `upt_dt` = '$upt_dt'";
    if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
        $_SESSION['save_success'] = $save_success;
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
    $template = mysqli_real_escape_string($conn, $_POST['uduse_type']);

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
    `tbl_company`.`companyname`,
    `tbl_genba`.`genbaname`
    FROM `tbl_user`
    LEFT JOIN `tbl_company` ON `tbl_user`.`companyid` = `tbl_company`.`companyid`
    LEFT JOIN 
    `tbl_genba` ON `tbl_user`.`genid` = `tbl_genba`.`genid`
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

    $companyid = $_SESSION['auth_companyid'];
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
            $preparedConditions = array_map(function ($grade) {
                return str_replace('"', '', $grade);
            }, $gradeConditions);

            $whereClause[] = "`tbl_user`.`grade` LIKE '%" . implode("%' OR `tbl_user`.`grade` LIKE '%", $preparedConditions) . "%'";
        }
        $whereClause[] = '`tbl_user`.`companyid` = ' . $companyid;
        $whereClause[] = '`tbl_user`.`type` IN ("' . constant('ADMIN') . '", "' . constant('ADMINISTRATOR') . '")';
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
    $companyid = $_SESSION['auth_companyid'];
    $bigo = mysqli_real_escape_string($conn, $_POST['bigo']);
    $inymd = "";
    $outymd = "";
    $type = mysqli_real_escape_string($conn, $_POST['user_type']);
    $genba_list = mysqli_real_escape_string($conn, $_POST['udgenba_list']);
    $gen_id_dev = explode(",", $genba_list);
    $genid = $gen_id_dev[0];

    if ($_SESSION['auth_type'] !== constant('ADMIN') && $_SESSION['auth_type'] !== constant('ADMINISTRATOR') && $_SESSION['auth_type'] !== constant('MAIN_ADMIN')) {
        $_SESSION['$user_type_undefined'] = $user_type_undefined;
        return;
    }

    $genid = isset($genid) ? $genid : '0';
    if (!isset($genid)) {
        $genid = 0;
    }

    // check duplicate mail  $email
    $sql_check_email = "SELECT COUNT(*) AS count FROM tbl_user WHERE email = '$email'";
    $result = $conn->query($sql_check_email);
    $row = $result->fetch_assoc();
    $emailExists = $row['count'] > 0;
    if ($emailExists) {
        $_SESSION['email_is_dupplicate'] = $email_is_dupplicate;
        return;
    }

    // generate random id 
    $RandomUid = '';
    do {
        $RandomUid = generateRandomString($MAX_LENGTH_UID_USER);
        $sql_check_uid = "SELECT COUNT(*) AS count FROM tbl_user WHERE uid = '$RandomUid'";
        $result = $conn->query($sql_check_uid);
        $row = $result->fetch_assoc();
        $uidExists = $row['count'] > 0;
    } while ($uidExists);


    $fileExtension = pathinfo($_FILES["signstamp"]["name"], PATHINFO_EXTENSION);
    $newFileName = generateUniqueFileName($IMAGE_UPLOAD_DIR_STAMP, $fileExtension, $RandomUid, $companyid);
    $originalFileName = $_FILES["signstamp"]["name"];
    $uploadFile = $IMAGE_UPLOAD_DIR_STAMP . $newFileName;
    $uploadOk = true;
    global $STAMP_MAXSIZE;

    if ($originalFileName == "") {
        $fileName == '';
    } else {
        // Check file name is exists
        if (file_exists($uploadFile)) {
            unlink($uploadFile);
        }
        // check size 
        if (!isFileSizeValid($_FILES["signstamp"], $STAMP_MAXSIZE)) {
            $uploadOk = false;
        }
        // check valid extention 
        $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
        if (!checkValidExtension($fileExtension)) {
            $uploadOk = false;
        }
    }
    // if not error save
    if ($uploadOk) {
        $fileName = $newFileName;
        // upload to server
        if (move_uploaded_file($_FILES["signstamp"]["tmp_name"], $uploadFile)) {
            deleteNoticeImages($IMAGE_UPLOAD_DIR_STAMP, $RandomUid, $newFileName);
        } else {
            error_log("Upload Error");
        }

        // insert to DB 
        $sql_user_insert = "INSERT INTO `tbl_user` (`uid`, `companyid`, `pwd`, `name`, `grade`, `type`
        , `signstamp`, `email`, `dept`, `bigo`,  `genid`   ,`inymd`, `outymd`, `reg_dt` , `upt_dt`) 
         VALUES('$RandomUid', '$companyid' ,'$pwd' ,'$name', '$grade', '$type'
        , '$fileName', '$email', '$dept', '$bigo',   '$genid' ,'$inymd', '$outymd', '$reg_dt' , null )";

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
    $type = mysqli_real_escape_string($conn, $_POST['uluser_type']);
    $dept = mysqli_real_escape_string($conn, $_POST['uddept']);
    $companyid = $_SESSION['auth_companyid'];
    $bigo = mysqli_real_escape_string($conn, $_POST['udbigo']);
    $udsignstamp_old = mysqli_real_escape_string($conn, $_POST['udsignstamp_old']);
    $genba_list = mysqli_real_escape_string($conn, $_POST['udgenba_list']);
    $gen_id_dev = explode(",", $genba_list);
    $genid = $gen_id_dev[0];
    $currentEmail = mysqli_real_escape_string($conn, $_POST['currentEmail']);
    $isEmailChanged = false;
    if ($currentEmail !== $email) {
        $isEmailChanged = true;
    }
    if ($_SESSION['auth_type'] !== constant('ADMIN') && $_SESSION['auth_type'] !== constant('ADMINISTRATOR') && $_SESSION['auth_type'] !== constant('MAIN_ADMIN')) {
        $_SESSION['$user_type_undefined'] = $user_type_undefined;
        return;
    }
    $genid = isset($genid) ? $genid : '0';
    if (!isset($genid)) {
        $genid = 0;
    }
    // check duplicate mail  $email
    $fileExtension = pathinfo($_FILES["udsignstamp_new"]["name"], PATHINFO_EXTENSION);
    $newFileName = generateUniqueFileName($IMAGE_UPLOAD_DIR_STAMP, $fileExtension, $uid, $companyid);
    $originalFileName = $_FILES["udsignstamp_new"]["name"];
    $uploadFile = $IMAGE_UPLOAD_DIR_STAMP . $newFileName;
    $uploadOk = true;
    global $STAMP_MAXSIZE;

    // Check file name is exists
    if (file_exists($uploadFile)) {
        unlink($uploadFile);
    }

    // check size 
    if (!isFileSizeValid($_FILES["udsignstamp_new"], $STAMP_MAXSIZE)) {
        $uploadOk = false;
    }

    // check valid extention 
    if (!empty($originalFileName)) {
        $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
        if (!checkValidExtension($fileExtension)) {
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
        if ($isEmailChanged) {
            // check admin 
            // check duplicate mail  $email
            $sql_check_email = "SELECT COUNT(*) AS count FROM tbl_user WHERE email = '$email'";
            $result = $conn->query($sql_check_email);
            $row = $result->fetch_assoc();
            $emailExists = $row['count'] > 0;
            if ($emailExists) {
                $_SESSION['email_is_dupplicate'] = $email_is_dupplicate;
                return;
            }
            $sql = "UPDATE tbl_user SET   pwd='$pwd', name='$name',  email ='$email' , grade='$grade', signstamp='$fileName', type='$type',dept='$dept', bigo='$bigo', genid='$genid', inymd='$inymd',
                  outymd='$outymd', genstrymd='$genstrymd', genendymd='$genendymd', upt_dt='$upt_dt' WHERE `uid` ='$uid'";
        } else {
            $sql = "UPDATE tbl_user SET  pwd='$pwd', name='$name', grade='$grade', signstamp='$fileName'
            , dept='$dept', bigo='$bigo', type='$type' ,  genid='$genid' , upt_dt='$upt_dt' WHERE email ='$email'";
        }

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
    $email = mysqli_real_escape_string($conn, $_POST['udemail']);
    $filePath = $IMAGE_UPLOAD_DIR_STAMP . $_POST['udsignstamp_old'];
    if ($_SESSION['auth_type'] !== constant('ADMIN') && $_SESSION['auth_type'] !== constant('ADMINISTRATOR') && $_SESSION['auth_type'] !== constant('MAIN_ADMIN')) {
        $_SESSION['$user_type_undefined'] = $user_type_undefined;
        return;
    }

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    $sql = "DELETE FROM `tbl_user` 
            WHERE email ='$email'";

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

// companywList.php
// Select database from tbl_companyworktime table
if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
    $sql_companyworktime_select = 'SELECT DISTINCT
    `tbl_companyworktime`.*,
    `tbl_company`.`companyname`
    FROM `tbl_companyworktime` 
    LEFT JOIN `tbl_company` ON `tbl_companyworktime`.`companyid` = `tbl_company`.`companyid`
    ORDER BY `tbl_companyworktime`.`companyid`';
} elseif ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) {
    $sql_companyworktime_select = 'SELECT DISTINCT
    `tbl_companyworktime`.*,
    `tbl_company`.`companyname`
    FROM `tbl_companyworktime` 
    LEFT JOIN `tbl_company` ON `tbl_companyworktime`.`companyid` = `tbl_company`.`companyid`
    WHERE `tbl_companyworktime`.`companyid` IN("' . $_SESSION['auth_companyid'] . '")';
}
$result_companyworktime_select = mysqli_query($conn, $sql_companyworktime_select);
$companyworktime_list = mysqli_fetch_all($result_companyworktime_select, MYSQLI_ASSOC);

// Save Data to tbl_companyworktime table
if (isset($_POST['btnRegCWL'])) {
    $companyid = mysqli_real_escape_string($conn, $_POST['companyid']);
    $kyukatype = mysqli_real_escape_string($conn, $_POST['kyukatype']);
    $starttime = mysqli_real_escape_string($conn, $_POST['starttime']);
    $endtime = mysqli_real_escape_string($conn, $_POST['endtime']);
    $worktime = mysqli_real_escape_string($conn, $_POST['worktime']);
    $breakstarttime = mysqli_real_escape_string($conn, $_POST['breakstarttime']);
    $breakendtime = mysqli_real_escape_string($conn, $_POST['breakendtime']);
    $breaktime = mysqli_real_escape_string($conn, $_POST['breaktime']);
    $bigo = mysqli_real_escape_string($conn, $_POST['bigo']);

    $sql = "INSERT INTO `tbl_companyworktime` (`companyid`, `kyukatype`, `starttime`, `endtime`,
            `breakstarttime`, `breakendtime`, `worktime`, `breaktime`, `bigo`, `reg_dt`, `upt_dt`)
            VALUES ('$companyid', '$kyukatype', '$starttime', '$endtime', '$breakstarttime', '$breakendtime',
            '$worktime', '$breaktime', '$bigo', '$reg_dt', null)
        ON DUPLICATE KEY UPDATE
            `kyukatype` = '$kyukatype', `starttime` = '$starttime', `endtime` = '$endtime',
            `breakstarttime` = '$breakstarttime', `breakendtime` = '$breakendtime', `worktime` = '$worktime', 
            `breaktime` = '$breaktime', `bigo` = '$bigo', `upt_dt` = '$upt_dt'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['save_success'] = $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update Data to tbl_companyworktime table
if (isset($_POST['btnUpdateCWL'])) {
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $kyukatype = mysqli_real_escape_string($conn, $_POST['udkyukatype']);
    $starttime = mysqli_real_escape_string($conn, $_POST['udstarttime']);
    $endtime = mysqli_real_escape_string($conn, $_POST['udendtime']);
    $worktime = mysqli_real_escape_string($conn, $_POST['udworktime']);
    $breakstarttime = mysqli_real_escape_string($conn, $_POST['udbreakstarttime']);
    $breakendtime = mysqli_real_escape_string($conn, $_POST['udbreakendtime']);
    $breaktime = mysqli_real_escape_string($conn, $_POST['udbreaktime']);
    $bigo = mysqli_real_escape_string($conn, $_POST['udbigo']);

    $sql = "UPDATE tbl_companyworktime SET 
            kyukatype='$kyukatype',
            starttime='$starttime',
            endtime='$endtime',
            worktime='$worktime',
            worktime='$worktime',
            breakstarttime='$breakstarttime',
            breakendtime='$breakendtime',
            breaktime='$breaktime',
            bigo='$bigo',
            upt_dt = '$upt_dt'
        WHERE companyid ='$companyid'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] = $update_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}


// kyukaNotice.php  
$companyid = $_SESSION['auth_companyid'];
$sqlFindKyukaNotice  =  "SELECT * FROM tbl_kyuka_notice WHERE `companyid` = $companyid";
$resultKyukaNotice = mysqli_query($conn, $sqlFindKyukaNotice);
$noDataKyuka = false;
if (mysqli_num_rows($resultKyukaNotice) <= 0) {
    $noDataKyuka = true;
} 

$sqlFindKyukaInfo = "SELECT * FROM `tbl_kyukainfo` WHERE `companyid` = $companyid";
$resultKyukaInfo = mysqli_query($conn, $sqlFindKyukaInfo);
$noDataKyukaInfo = false;

if (mysqli_num_rows($resultKyukaInfo) <= 0) {
    $noDataKyukaInfo = true;
} 

$kiukaNoticeList = mysqli_fetch_all($resultKyukaNotice, MYSQLI_ASSOC);
$kiukaInfoList = mysqli_fetch_all($resultKyukaInfo, MYSQLI_ASSOC);

// when no data  Kyuka Notice -> get default data
if($noDataKyuka) {
    $sqlFindKyukaNoticeDefault  =  "SELECT * FROM tbl_kyuka_notice WHERE companyid = 0 ";
    $resultKyukaNoticeDefault = mysqli_query($conn, $sqlFindKyukaNoticeDefault);
    $kiukaNoticeList = mysqli_fetch_all($resultKyukaNoticeDefault, MYSQLI_ASSOC);
}

// when no data  Kyuka Info -> get default data
if($noDataKyukaInfo) {
    $sqlFindKyukaInfoDefault  =  "SELECT * FROM tbl_kyukainfo WHERE companyid = 0 ";
    $resultKyukaInfoDefault = mysqli_query($conn, $sqlFindKyukaInfoDefault);
    $kiukaInfoList = mysqli_fetch_all($resultKyukaInfoDefault, MYSQLI_ASSOC);
}

$kiukaInfoListDatas = $kiukaInfoList[0];
$kiukaInfoListDatasShow = array();

for ($i = $MIN_KYUKA_INFO_COUNT ; $i <= $MAX_KYUKA_INFO_COUNT; $i++) {
    $key = "ttop" . $i;
    $keybottom = "tbottom" . $i;
    if (!isset($kiukaInfoListDatas[$key]) || trim($kiukaInfoListDatas[$key]) == '') {
        continue;
    }
    if (!isset($kiukaInfoListDatas[$keybottom]) || trim($kiukaInfoListDatas[$keybottom]) == '') {
        continue;
    }
    $value = intval($kiukaInfoListDatas[$key]);
    if ($value < 12) {
        $kiukaInfoListDatasShow[$key] = $value . 'ヵ月';
    } else {
        $years = floor($value / 12);
        $months = $value % 12;
        if ($months == 0) {
            $kiukaInfoListDatasShow[$key] = $years . '年';
        } else {
            $kiukaInfoListDatasShow[$key] = $years . '年' . $months . 'ヵ月';
        }
    }
   
    if ($i == $MIN_KYUKA_INFO_COUNT) {
        $kiukaInfoListDatasShow['ttop0'] = $kiukaInfoListDatasShow[$key] . '以内';
    }
    if($i == $MAX_KYUKA_INFO_COUNT) {
        $kiukaInfoListDatasShow[$key] .= '以上';
    }
    $kiukaInfoListDatasShow[$keybottom] = $kiukaInfoListDatas[$keybottom] . '日';

}

// register kyukanotice.php 
if (isset($_POST['kyukanoticeRegister'])) {  
    $title_value = mysqli_real_escape_string($conn, $_POST['title_value']);
    $message_value = mysqli_real_escape_string($conn, $_POST['message_value']);
    $subTitle_value = mysqli_real_escape_string($conn, $_POST['subTitle_value']);

    // get current notice -> when null -> create new 
    $sqlRegister = "UPDATE tbl_kyuka_notice SET  `title`='$title_value', `message`='$message_value', `subtitle`='$subTitle_value', 
      upt_dt='$upt_dt' 
     WHERE companyid = $companyid";
    if ($noDataKyuka) {
        $sqlRegister = "INSERT INTO tbl_kyuka_notice SET  `title`='$title_value', `message`='$message_value'
        , `subtitle`='$subTitle_value', upt_dt='$upt_dt' , 
        companyid = $companyid";
    } 

    if($noDataKyukaInfo) {
         // return error -> Need register KyukaInfo First ***
         $_SESSION['kyuka_info_not_existing'] = $kyuka_info_not_existing;
         return;
    }

    if ($conn->query($sqlRegister) === TRUE) {
    $_SESSION['update_success'] = $update_success;
    header("Refresh:3");
    } else {
    echo 'query error: ' . mysqli_error($conn);
    }
}

