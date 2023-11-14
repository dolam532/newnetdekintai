<?php
$reg_dt = date('Y-m-d H:i:s');
$upt_dt = date('Y-m-d H:i:s');


// (userList.php)
// Select data from tbl_codebase
if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
    $sql_codebase = 'SELECT `code`, `name`, `companyid` FROM `tbl_codebase`
    WHERE `tbl_codebase`.`typecode` = "' . constant('DEPARTMENT') . '"
    GROUP BY `code`, `name`, `companyid`
    ORDER BY `companyid`';
} else {
    $sql_codebase = 'SELECT `code`, `name` FROM `tbl_codebase`
    WHERE `tbl_codebase`.`typecode` = "' . constant('DEPARTMENT') . '"
    AND `tbl_codebase`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
    GROUP BY `code`, `name`';
}
$result_codebase = mysqli_query($conn, $sql_codebase);
$codebase_list = mysqli_fetch_all($result_codebase, MYSQLI_ASSOC);

if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
    $sql_user_select_db = 'SELECT DISTINCT
    `tbl_user`.*,
    `tbl_genba`.`genbaname`,
    `tbl_genba`.`workstrtime`,
    `tbl_genba`.`workendtime`
FROM
    `tbl_user`
LEFT JOIN 
    `tbl_genba` ON `tbl_user`.`genid` = `tbl_genba`.`genid` 
WHERE
    `tbl_user`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
    AND `tbl_user`.`type` IN("' . constant('ADMIN') . '", "' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '")'; // don't select Main ADMIN

    if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
        $sql_user_select_db = 'SELECT DISTINCT
    `tbl_user`.*,
    `tbl_genba`.`genbaname`,
    `tbl_genba`.`workstrtime`,
    `tbl_genba`.`workendtime`,
    `tbl_company`.`companyname` 
FROM
    `tbl_user`
LEFT JOIN 
    `tbl_genba` ON `tbl_user`.`genid` = `tbl_genba`.`genid` 
LEFT JOIN 
    `tbl_company` ON `tbl_user`.`companyid` = `tbl_company`.`companyid`
ORDER BY `tbl_user`.`companyid`';
    }
} elseif ($_SESSION['auth_type'] == constant('USER')) {
    $sql_user_select_db = 'SELECT DISTINCT
    `tbl_user`.*,
    `tbl_genba`.`genbaname`,
    `tbl_genba`.`workstrtime`,
    `tbl_genba`.`workendtime`
FROM
    `tbl_user`
LEFT JOIN 
    `tbl_genba` ON `tbl_user`.`genid` = `tbl_genba`.`genid` 
WHERE
    `tbl_user`.`uid` = "' . $_SESSION['auth_uid'] . '"
    AND `tbl_user`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
    AND `tbl_user`.`type` IN("' . constant('ADMIN') . '", "' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '")'; // don't select Main ADMIN
}

$sql_user_select = mysqli_query($conn, $sql_user_select_db);
$result_user_select = mysqli_fetch_all($sql_user_select, MYSQLI_ASSOC);

if (!empty($result_user_select)) {
    foreach ($result_user_select as $key) {
        $Grade[] = $key['grade'];
        $Name[] = $key['name'];
    }
}
$Grade = array_unique($Grade);
$Name = array_unique($Name);
if ($_POST['SearchButton'] == NULL || isset($_POST['ClearButton'])) {
    $userlist_list = $result_user_select;
} elseif ($_POST['SearchButton'] != NULL) {
    $searchName = isset($_POST['searchName']) ? "%" . trim($_POST['searchName']) . "%" : "";
    $searchGrade = isset($_POST['searchGrade']) ? "%" . trim($_POST['searchGrade']) . "%" : "";

    $sql_user = 'SELECT DISTINCT
    `tbl_user`.*,
    `tbl_genba`.`genbaname`,
    `tbl_genba`.`workstrtime`,
    `tbl_genba`.`workendtime`,
    `tbl_company`.`companyname` 
 FROM
    `tbl_user`
 LEFT JOIN 
 `tbl_genba` ON `tbl_user`.`genid` = `tbl_genba`.`genid` 
 LEFT JOIN 
 `tbl_company` ON `tbl_user`.`companyid` = `tbl_company`.`companyid`';

    if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {

        if ($searchName !== "" && $searchName !== '%%') {
            $sql_user .= ' WHERE `tbl_user`.`name` LIKE "' . $searchName . '"
                            ORDER BY `tbl_user`.`companyid`';
        }
        if ($searchGrade !== "" && $searchGrade !== '%%') {
            $sql_user .= ' WHERE `tbl_user`.`grade` LIKE "' . $searchGrade . '"
                            ORDER BY `tbl_user`.`companyid`';
        }
    } else {
        if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) {
            $sql_user .= ' WHERE 
            `tbl_user`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
               AND `tbl_user`.`type` IN("' . constant('ADMIN') . '", "' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '")'; // don't select Main ADMIN
        } else {
            $sql_user .= ' WHERE 
            `tbl_user`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
               AND `tbl_user`.`uid` IN("' . $_SESSION['auth_uid'] . '")';
        }
        if ($searchName !== "" && $searchName !== '%%') {
            $sql_user .= ' AND `tbl_user`.`name` LIKE "' . $searchName . '"';
        }
        if ($searchGrade !== "" && $searchGrade !== '%%') {
            $sql_user .= ' AND `tbl_user`.`grade` LIKE "' . $searchGrade . '"';
        }
    }
    error_log($sql_user);
    $sql_user_re = mysqli_query($conn, $sql_user);
    $userlist_list = mysqli_fetch_all($sql_user_re, MYSQLI_ASSOC);
}

// Select data from tbl_genba
$sql_genba = 'SELECT * FROM `tbl_genba` WHERE `companyid` IN ("' . $_SESSION['auth_companyid'] . '", 0 ) ';
$result_genba = mysqli_query($conn, $sql_genba);
$genba_list_db = mysqli_fetch_all($result_genba, MYSQLI_ASSOC);

// Save data to tbl_user table of database
if (isset($_POST['SaveUserList'])) {
    $_POST['companyid'] = intval($_POST['companyid']);
    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $companyid = mysqli_real_escape_string($conn, $_POST['companyid']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $type = constant('USER');
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $dept = mysqli_real_escape_string($conn, $_POST['dept']);
    $bigo = mysqli_real_escape_string($conn, $_POST['bigo']);
    $inymd = mysqli_real_escape_string($conn, $_POST['inymd']);
    $outymd = mysqli_real_escape_string($conn, $_POST['outymd']);
    $genba_list = mysqli_real_escape_string($conn, $_POST['genba_list']);
    $genstrymd = mysqli_real_escape_string($conn, $_POST['genstrymd']);
    $genendymd = mysqli_real_escape_string($conn, $_POST['genendymd']);
    $gen_id_dev = explode(",", $genba_list);
    $genid = $gen_id_dev[0];

    $genid = isset($genid) ? $genid : '0';
    if (!isset($genid)) {
        $genid = 0;
    }

    // 2023-10-11/1340-006 change start
    $fileExtension = pathinfo($_FILES["signstamp"]["name"], PATHINFO_EXTENSION);
    $newFileName = generateUniqueFileName($IMAGE_UPLOAD_DIR_STAMP, $fileExtension, $uid, $companyid);
    $originalFileName = $_FILES["signstamp"]["name"];
    if ($originalFileName == "") {
        $fileName == '';
    } else {
        $uploadFile = $IMAGE_UPLOAD_DIR_STAMP . $newFileName;
        $uploadOk = true;
        global $STAMP_MAXSIZE;

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

        // if not error save
        if ($uploadOk) {
            $fileName = $newFileName;
            // upload to server
            if (move_uploaded_file($_FILES["signstamp"]["tmp_name"], $uploadFile)) {
                deleteNoticeImages($IMAGE_UPLOAD_DIR_STAMP, $uid, $newFileName);
            } else {
                error_log("Upload Error");
            }
            if ($fileName == null) {
                $fileName == '';
            }
        }
    }

    // insert to DB 
    $sql_user_insert = "INSERT INTO `tbl_user` (`uid`, `companyid`, `pwd`, `name`, `grade`, `type`
    , `signstamp`, `email`, `dept`, `bigo`, `inymd`, `outymd`, `genid`, `genstrymd`, `genendymd`, `reg_dt` , `upt_dt`) 
     VALUES('$uid', '$companyid' ,'$pwd' ,'$name', '$grade', '$type'
    , '$fileName', '$email', '$dept', '$bigo', '$inymd', '$outymd', '$genid', '$genstrymd', '$genendymd', '$reg_dt' , null)";

    if ($conn->query($sql_user_insert) === TRUE) {
        $_SESSION['save_success'] = $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update data to tbl_user table of database
if (isset($_POST['UpdateUserList'])) {
    $ulcompanyid = intval($_POST['ulcompanyid']);
    $uid = mysqli_real_escape_string($conn, $_POST['uluid']);
    $companyid = mysqli_real_escape_string($conn, $ulcompanyid);
    $pwd = mysqli_real_escape_string($conn, $_POST['ulpwd']);
    $name = mysqli_real_escape_string($conn, $_POST['ulname']);
    $grade = mysqli_real_escape_string($conn, $_POST['ulgrade']);
    $type = mysqli_real_escape_string($conn, $_POST['ultype']);
    $email = mysqli_real_escape_string($conn, $_POST['ulemail']);
    $dept = mysqli_real_escape_string($conn, $_POST['uldept']);
    $bigo = mysqli_real_escape_string($conn, $_POST['ulbigo']);
    $inymd = mysqli_real_escape_string($conn, $_POST['ulinymd']);
    $outymd = mysqli_real_escape_string($conn, $_POST['uloutymd']);
    $genba_list = mysqli_real_escape_string($conn, $_POST['ulgenba_list']);
    $genstrymd = mysqli_real_escape_string($conn, $_POST['ulgenstrymd']);
    $genendymd = mysqli_real_escape_string($conn, $_POST['ulgenendymd']);
    $udsignstamp_old = mysqli_real_escape_string($conn, $_POST['udsignstamp_old']);
    $gen_id_dev = explode(",", $genba_list);
    $genid = $gen_id_dev[0];

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
        companyid='$companyid', pwd='$pwd', name='$name', grade='$grade', signstamp='$fileName'
        , type='$type', email='$email',dept='$dept', bigo='$bigo', genid='$genid', inymd='$inymd'
        , outymd='$outymd', genstrymd='$genstrymd', genendymd='$genendymd', upt_dt='$upt_dt' WHERE uid ='$uid'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['update_success'] = $update_success;
            header("Refresh:3");
        } else {
            echo 'query error: ' . mysqli_error($conn);
        }
    }
}

// Delete data to tbl_user table of database
if (isset($_POST['btnDelUserList'])) {
    $uid = mysqli_real_escape_string($conn, $_POST['uluid']);
    $filePath = $IMAGE_UPLOAD_DIR_STAMP . $_POST['udsignstamp_old'];

    $sql = "DELETE FROM `tbl_user` 
            WHERE uid ='$uid'";
    // error_log("xxxxx****xxxxx DELETE FILE PATH".$filePath);
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


// (genbaList.php)
// Select data from tbl_genba
$sql_genba = 'SELECT * FROM `tbl_genba` WHERE `companyid` IN ("' . $_SESSION['auth_companyid'] . '", 0 ) ORDER BY `tbl_genba`.`genid`';
$companyid = $_SESSION['auth_companyid'];
if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) {

    if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
        $sql_genba = 'SELECT * FROM `tbl_genba` ;';
    }
    $result_genba = mysqli_query($conn, $sql_genba);
    $genbadatas_list = mysqli_fetch_all($result_genba, MYSQLI_ASSOC);
} elseif ($_SESSION['auth_type'] == constant('USER')) {

    $result_genba = mysqli_query($conn, $sql_genba);
    $genbadatas_list = mysqli_fetch_all($result_genba, MYSQLI_ASSOC);
}

//get current template
$sql_current_template = "SELECT `template` FROM `tbl_company` WHERE `companyid` = $companyid LIMIT 1";
$currentTemplate = '';

$result = $conn->query($sql_current_template);

if ($result === false) {
    echo 'Query error: ' . mysqli_error($conn);
} else {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentTemplate = $row['template'];
    }
}

// Save data to tbl_genba table of database
if (isset($_POST['SaveKinmu'])) {
    if ($_SESSION['auth_type'] !== constant('ADMIN') && $_SESSION['auth_type'] !== constant('ADMINISTRATOR') && $_SESSION['auth_type'] !== constant('MAIN_ADMIN')) {
        echo 'not admin ';
        return;
    }

    $companyid = $_SESSION['auth_companyid'];
    $genbaname = mysqli_real_escape_string($conn, $_POST['genbaname']);
    $genbacompany = mysqli_real_escape_string($conn, $_POST['genbacompany']);
    $strymd = mysqli_real_escape_string($conn, $_POST['strymd']);
    $endymd = mysqli_real_escape_string($conn, $_POST['endymd']);
    $use_yn = mysqli_real_escape_string($conn, $_POST['use_yn']);
    $workstrtime = mysqli_real_escape_string($conn, $_POST['workstrtime']);
    $workendtime = mysqli_real_escape_string($conn, $_POST['workendtime']);
    $offtime1 = mysqli_real_escape_string($conn, $_POST['offtime1']);
    $offtime2 = mysqli_real_escape_string($conn, $_POST['offtime2']);
    $bigo = mysqli_real_escape_string($conn, $_POST['bigo']);
    $template = $currentTemplate;
    $uid = $_SESSION['auth_uid'];

    $sql_genba_insert = mysqli_query($conn, "INSERT INTO `tbl_genba` (`genbaname`, `genbacompany`, `companyid`, `strymd`, `endymd`, `use_yn`, `workstrtime`, `workendtime`,  `offtime1`, `offtime2`, `bigo`,  `template` , `uid` ,   `reg_dt` , `upt_dt`)
                VALUES ('$genbaname', '$genbacompany', '$companyid', '$strymd', '$endymd', '$use_yn', '$workstrtime', '$workendtime', '$offtime1', '$offtime2', '$bigo',  '$template' , '$uid'  ,'$reg_dt' , null)");

    if ($sql_genba_insert) {
        $_SESSION['save_success'] = $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update data to tbl_genba table of database
if (isset($_POST['UpdateKinmu'])) {
    if ($_SESSION['auth_type'] !== constant('ADMIN') && $_SESSION['auth_type'] !== constant('ADMINISTRATOR') && $_SESSION['auth_type'] !== constant('MAIN_ADMIN')) {
        echo 'not admin ';
    }
    $genid = mysqli_real_escape_string($conn, $_POST['udgenid']);
    if ($genid == '0' && $_SESSION['auth_type'] !== constant('MAIN_ADMIN')) {
        echo 'not admin of defaut  ';
        return;
    }

    $genbaname = mysqli_real_escape_string($conn, $_POST['udgenbaname']);
    $genbacompany = mysqli_real_escape_string($conn, $_POST['udgenbacompany']);
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $strymd = mysqli_real_escape_string($conn, $_POST['udstrymd']);
    $endymd = mysqli_real_escape_string($conn, $_POST['udendymd']);
    $use_yn = mysqli_real_escape_string($conn, $_POST['uduse_yn']);
    $workstrtime = mysqli_real_escape_string($conn, $_POST['udworkstrtime']);
    $workendtime = mysqli_real_escape_string($conn, $_POST['udworkendtime']);
    $offtime1 = mysqli_real_escape_string($conn, $_POST['udofftime1']);
    $offtime2 = mysqli_real_escape_string($conn, $_POST['udofftime2']);
    $bigo = mysqli_real_escape_string($conn, $_POST['bigo_cmodal']);
    $template = $currentTemplate;
    $uid = $_SESSION['auth_uid'];

    $sql = "UPDATE tbl_genba SET 
            genbaname='$genbaname',
            genbacompany='$genbacompany',
            companyid='$companyid',
            strymd='$strymd',
            endymd='$endymd',
            workstrtime='$workstrtime',
            workendtime='$workendtime',
            offtime1='$offtime1',
            offtime2='$offtime2',
            bigo='$bigo',
            use_yn='$use_yn',
            upt_dt='$upt_dt',
            template='$template',
            uid = '$uid'
        WHERE genid ='$genid'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] = $update_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Delete data to tbl_genba table of database
if (isset($_POST['DeleteKinmu'])) {
    if ($_SESSION['auth_type'] !== constant('ADMIN') && $_SESSION['auth_type'] !== constant('ADMINISTRATOR') && $_SESSION['auth_type'] !== constant('MAIN_ADMIN')) {
        echo 'not admin ';
    }
    $genid = mysqli_real_escape_string($conn, $_POST['udgenid']);
    if ($genid == '0' && $_SESSION['auth_type'] !== constant('MAIN_ADMIN')) {
        echo 'not admin of defaut  ';
        return;
    }
    $genbaname = mysqli_real_escape_string($conn, $_POST['udgenbaname']);
    $sql = "DELETE FROM `tbl_genba` 
            WHERE genid ='$genid' AND genbaname ='$genbaname'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_success'] = $delete_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}


// genbaUserList.php
// Select data from tbl_user
if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
    $sql_user_g = 'SELECT
        `tbl_user`.*,
        `tbl_genba`.`genbaname`
    FROM
    `tbl_user`
    LEFT JOIN `tbl_genba` ON `tbl_user`.`genid` = `tbl_genba`.`genid`';
    $result_user_g = mysqli_query($conn, $sql_user_g);
    $user_list_g = mysqli_fetch_all($result_user_g, MYSQLI_ASSOC);
} elseif ($_SESSION['auth_type'] == constant('USER')) {
    $sql_user_g = 'SELECT
        `tbl_user`.*,
        `tbl_genba`.`genbaname`
    FROM
    `tbl_user`
    LEFT JOIN `tbl_genba` ON `tbl_user`.`genid` = `tbl_genba`.`genid`
    WHERE `tbl_user`.`uid` IN ("' . $_SESSION['auth_uid'] . '")';
    $result_user_g = mysqli_query($conn, $sql_user_g);
    $user_list_g = mysqli_fetch_all($result_user_g, MYSQLI_ASSOC);
}
