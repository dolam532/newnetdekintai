<?php
$reg_dt = date('Y-m-d H:i:s');

// userloginList.php
$year = isset($_POST["selyy"]) ? $_POST["selyy"] : date('Y');
$month = isset($_POST["selmm"]) ? $_POST["selmm"] : date('m');
$day = isset($_POST["seldd"]) ? $_POST["seldd"] : date('d');

// Select database from tbl_userlogin table
if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) {
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
// ----------2023-10-03/1340-003--------- change start// 

/// noticeList.php
// Select database from tbl_notice table
$sql_notice_select = 'SELECT DISTINCT
`tbl_notice`.*,
`tbl_user`.`name`
FROM `tbl_notice`
LEFT JOIN `tbl_user` ON `tbl_notice`.`uid` = `tbl_user`.`uid`';
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
    `tbl_user`.`name`
    FROM `tbl_notice`
    LEFT JOIN `tbl_user` ON `tbl_notice`.`uid` = `tbl_user`.`uid`
    WHERE (`tbl_notice`.`title` LIKE "' . $searchTitle . '" OR `tbl_notice`.`content` LIKE "' . $searchContent . '")';

    $result_notice = mysqli_query($conn, $sql_notice);
    $notice_list = mysqli_fetch_all($result_notice, MYSQLI_ASSOC);
} else {
    $notice_list = $notice_list_select;
}
// ----------2023-10-03/1340-003--------- change end// 

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

    if ($_FILES['imagefile']["name"] == "") {
        $sql = "INSERT INTO `tbl_notice` (`title`, `content`, `reader`, `viewcnt`, `uid`, `reg_dt`)
        VALUES ('$title', '$content', '$reader', '$viewcnt', '$uid', '$reg_dt')";
    } else {
        $uploadDirectory = "../assets/uploads/";
        $fileName = $_FILES["imagefile"]["name"];
        $fileTmpName = $_FILES["imagefile"]["tmp_name"];
        $fileType = $_FILES["imagefile"]["type"];
        $allowedTypes = ["image/jpeg", "image/png", "image/jpg"];

        if (in_array($fileType, $allowedTypes)) {
            $targetPath = $uploadDirectory . $fileName;
            if (file_exists($targetPath)) {
                echo "A file with the same name already exists. Please rename the file.";
            } else {
                if (move_uploaded_file($fileTmpName, $targetPath)) {
                    $sql = "INSERT INTO `tbl_notice` (`title`, `content`, `imagefile`, `reader`, `viewcnt`, `uid`, `reg_dt`)
                    VALUES ('$title', '$content', '$fileName', '$reader', '$viewcnt', '$uid', '$reg_dt')";
                } else {
                    echo $image_upload_error;
                }
            }
        } else {
            echo $image_type_error;
        }
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['save_success'] =  $save_success;
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
    $uid = mysqli_real_escape_string($conn, $_POST['uduid']);
    $title = mysqli_real_escape_string($conn, $_POST['udtitle']);
    $content = mysqli_real_escape_string($conn, $udcontent_f);
    $reader = mysqli_real_escape_string($conn, $_POST['udreader']);
    $viewcnt = mysqli_real_escape_string($conn, $_POST['udviewcnt']);
    $reg_dt = mysqli_real_escape_string($conn, $_POST['udreg_dt']);

    if ($_FILES['udimagefile_new']["name"] == "") {
        $sql = "UPDATE tbl_notice SET 
                title='$title',
                content='$content',
                reader='$reader',
                viewcnt='$viewcnt',
                reg_dt='$reg_dt'
            WHERE bid ='$bid'
            AND uid ='$uid'";
    } else {
        $filePath = "../assets/uploads/" . $_POST['udimagefile_old'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $uploadDirectory = "../assets/uploads/";
        $fileName = $_FILES["udimagefile_new"]["name"];
        $fileTmpName = $_FILES["udimagefile_new"]["tmp_name"];
        $fileType = $_FILES["udimagefile_new"]["type"];
        $allowedTypes = ["image/jpeg", "image/png", "image/jpg"];

        if (in_array($fileType, $allowedTypes)) {
            $targetPath = $uploadDirectory . $fileName;
            if (move_uploaded_file($fileTmpName, $targetPath)) {
                $sql = "UPDATE tbl_notice SET 
                title='$title',
                content='$content',
                reader='$reader',
                imagefile='$fileName',
                viewcnt='$viewcnt',
                reg_dt='$reg_dt'
                WHERE bid ='$bid'
                AND uid ='$uid'";
            } else {
                echo $image_upload_error;
            }
        } else {
            echo $image_type_error;
        }
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] =  $update_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Delete Data to tbl_notice DB 
if (isset($_POST['btnDelNL'])) {
    $bid = mysqli_real_escape_string($conn, $_POST['udbid']);
    $uid = mysqli_real_escape_string($conn, $_POST['uduid']);

    $sql = "DELETE FROM `tbl_notice` 
    WHERE bid ='$bid' AND uid ='$uid'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_success'] =  $delete_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// codemasterList.php
// Select database from tbl_codetype table
$sql_codetype = 'SELECT * FROM `tbl_codetype`';
$result_codetype = mysqli_query($conn, $sql_codetype);
$codetype_list = mysqli_fetch_all($result_codetype, MYSQLI_ASSOC);
$codes;
// Select database from tbl_codebase table
if ($_POST['typecode'] == NULL) {
    $sql_codebase = 'SELECT * FROM `tbl_codebase`
        WHERE `tbl_codebase`.`companyid` IN ("' . constant('GANASYS_COMPANY_ID') . '")';
    $result_codebase = mysqli_query($conn, $sql_codebase);
    $codebase_list = mysqli_fetch_all($result_codebase, MYSQLI_ASSOC);
    $codes = array_column($codebase_list, 'code');
   
} elseif (isset($_POST['typecode'])) {
    $sql_codebase = 'SELECT * FROM `tbl_codebase`
        WHERE `tbl_codebase`.`companyid` IN ("' . constant('GANASYS_COMPANY_ID') . '")
        AND `tbl_codebase`.`typecode` IN ("' . $_POST['typecode'] . '")';
    $result_codebase = mysqli_query($conn, $sql_codebase);
    $codebase_list = mysqli_fetch_all($result_codebase, MYSQLI_ASSOC);
    $codes = array_column($codebase_list, 'code');
}

// Save Data to tbl_codebase DB 
if (isset($_POST['btnRegCL'])) {
    $companyid = constant('GANASYS_COMPANY_ID');
    $typecode = $_POST['typecode'];
    $uid = $_SESSION['auth_uid'];
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $remark = mysqli_real_escape_string($conn, $_POST['remark']);

    $sql = "INSERT INTO `tbl_codebase` (`companyid`, `typecode`, `code`, `name`, `remark`, `uid`, `reg_dt`)
                VALUES ('$companyid', '$typecode', '$code', '$name', '$remark', '$uid', '$reg_dt')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['save_success'] =  $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update Data to tbl_codebase DB 
if (isset($_POST['btnUpdateCL'])) {
    $id = mysqli_real_escape_string($conn, $_POST['udid']);
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $uid = mysqli_real_escape_string($conn, $_POST['uduid']);
    $typecode = mysqli_real_escape_string($conn, $_POST['udtypecode']);
    $code = mysqli_real_escape_string($conn, $_POST['udcode']);
    $name = mysqli_real_escape_string($conn, $_POST['udname']);
    $remark = mysqli_real_escape_string($conn, $_POST['udremark']);

    $sql = "UPDATE tbl_codebase SET 
                name='$name',
                remark='$remark'
            WHERE id ='$id'
            AND companyid ='$companyid'
            AND uid ='$uid'
            AND typecode ='$typecode'
            AND code ='$code'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] =  $update_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Delete Data to tbl_codebase DB 
if (isset($_POST['btnDelCL'])) {
    $id = mysqli_real_escape_string($conn, $_POST['udid']);
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $uid = mysqli_real_escape_string($conn, $_POST['uduid']);
    $typecode = mysqli_real_escape_string($conn, $_POST['udtypecode']);
    $code = mysqli_real_escape_string($conn, $_POST['udcode']);

    $sql = "DELETE FROM `tbl_codebase` 
    WHERE id ='$id' AND companyid ='$companyid' AND uid ='$uid' AND typecode ='$typecode' AND code ='$code'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_success'] =  $delete_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}
