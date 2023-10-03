<?php
$reg_dt = date('Y-m-d H:i:s');

// manageInfo.php
// Select database from tbl_manageinfo table
$sql_manageinfo = 'SELECT DISTINCT
    `tbl_manageinfo`.*,
    `tbl_company`.`companyname`
    FROM `tbl_manageinfo` 
    LEFT JOIN `tbl_company` ON `tbl_manageinfo`.`companyid` = `tbl_company`.`companyid`
    WHERE `tbl_manageinfo`.`companyid` IN("' . constant('GANASYS_COMPANY_ID') . '")';
$result_manageinfo = mysqli_query($conn, $sql_manageinfo);
$manageinfo_list = mysqli_fetch_all($result_manageinfo, MYSQLI_ASSOC);

// Update data to tbl_manageinfo table of database
if (isset($_POST['btnRegMi'])) {
    $magamYm = substr($_POST['magamYm'], 0, 7);
    $companyid = mysqli_real_escape_string($conn, $_POST['companyid']);
    $magamym = mysqli_real_escape_string($conn, $magamYm);
    $magamymd = mysqli_real_escape_string($conn, $_POST['magamYmd']);
    $kyukatimelimit = mysqli_real_escape_string($conn, $_POST['kyukatimelimit']);

    $sql = "UPDATE tbl_manageinfo SET 
                magamym='$magamym',
                magamymd='$magamymd',
                kyukatimelimit='$kyukatimelimit'
            WHERE companyid ='$companyid'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_mi_success'] =  $update_mi_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// companyList.php
// Select database from tbl_company table
$sql_company_select = 'SELECT * FROM `tbl_company`';
$result_company_select = mysqli_query($conn, $sql_company_select);
$company_list_select = mysqli_fetch_all($result_company_select, MYSQLI_ASSOC);

// Search Data tbl_company
if ($_POST['SearchButtonCL'] == NULL) {
    $company_list = $company_list_select;
} elseif (isset($_POST['SearchButtonCL'])) {
    if (!empty($company_list_select)) {
        foreach ($company_list_select as $key) {
            $Companyname[] = $key['companyname'];
            $Use_yn[] = $key['use_yn'];
        }
    }
    $Companyname = array_unique($Companyname);
    $Use_yn = array_unique($Use_yn);

    if ($_POST['searchCompanyname'] != "") {
        $searchCompanyname = trim($_POST['searchCompanyname']);
    } else {
        $searchCompanyname = implode('","', $Companyname);
    }

    if ($_POST['searchUseyn'] == "0") {
        $searchUse_yn = implode('","', $Use_yn);
    } elseif ($_POST['searchUseyn'] == "1") {
        $searchUse_yn = $_POST['searchUseyn'];
    } elseif ($_POST['searchUseyn'] == "2") {
        $searchUse_yn = "0";
    }

    $sql_company = 'SELECT *
        FROM `tbl_company`
        WHERE `tbl_company`.`companyname` IN ("' . $searchCompanyname . '")
        AND `tbl_company`.`use_yn` IN ("' . $searchUse_yn . '")';
    $result_company = mysqli_query($conn, $sql_company);
    $company_list = mysqli_fetch_all($result_company, MYSQLI_ASSOC);
}

// Save Data to tbl_company
if (isset($_POST['btnRegCL'])) {
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

    $sql = "INSERT INTO `tbl_company` (`companycode`, `companyname`, `staff`, `telno`,
                `strymd`, `endymd`, `address`, `use_yn`, `joken`, `bigo`, `reg_dt`)
                VALUES ('$companycode', '$companyname', '$staff', '$telno',
                '$strymd', '$endymd', '$address', '$use_yn', '$joken', '$bigo', '$reg_dt')";

    if ($conn->query($sql) === TRUE) {
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

    $sql = "UPDATE tbl_company SET 
            companyname='$companyname',
            staff='$staff',
            telno='$telno',
            strymd='$strymd',
            endymd='$endymd',
            address='$address',
            use_yn='$use_yn',
            joken='$joken',
            bigo='$bigo'
        WHERE companyid ='$companyid'
        AND companycode ='$companycode'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] =  $update_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Delete data to tbl_company table of database
if (isset($_POST['DeleteCL'])) {
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $companycode = mysqli_real_escape_string($conn, $_POST['udcompanycode']);

    $sql = "DELETE FROM `tbl_company` 
            WHERE companyid ='$companyid' AND companycode ='$companycode'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_success'] =  $delete_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// AdminList.php
// Select database from tbl_user table
$sql_admin_select = 'SELECT DISTINCT
    `tbl_user`.*,
    `tbl_company`.`companyname`
    FROM `tbl_user`
    LEFT JOIN `tbl_company` ON `tbl_user`.`companyid` = `tbl_company`.`companyid`
    WHERE `tbl_user`.`type` IN ("' . constant('ADMIN') . '", "' . constant('ADMINISTRATOR') . '")';
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

    if ($_POST['searchAdminName'] != "") {
        $searchAdminName = trim($_POST['searchAdminName']);
    } else {
        $searchAdminName = implode('","', $AdminName);
    }

    if ($_POST['searchAdminGrade'] != "") {
        $searchAdminGrade = trim($_POST['searchAdminGrade']);
    } else {
        $searchAdminGrade = implode('","', $AdminGrade);
    }

    $sql_admin = 'SELECT *
        FROM `tbl_user`
        WHERE `tbl_user`.`name` IN ("' . $searchAdminName . '")
        AND `tbl_user`.`grade` IN ("' . $searchAdminGrade . '")';
    $result_admin = mysqli_query($conn, $sql_admin);
    $admin_list = mysqli_fetch_all($result_admin, MYSQLI_ASSOC);
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

    if ($_FILES['signstamp']["name"] == "") {
        $sql = "INSERT INTO `tbl_user` (`uid`, `companyid`, `name`, `pwd`, `dept`, 
                `email`, `grade`, `type`, `bigo`, `inymd`, `outymd`, `reg_dt`)
                VALUES ('$uid', '$companyid', '$name', '$pwd', '$dept',
                '$email', '$grade', '$type', '$bigo', '$inymd', '$outymd', '$reg_dt')";
    } else {
        $uploadDirectory = "../assets/uploads/";
        $fileName = $_FILES["signstamp"]["name"];
        $fileTmpName = $_FILES["signstamp"]["tmp_name"];
        $fileType = $_FILES["signstamp"]["type"];
        $allowedTypes = ["image/jpeg", "image/png", "image/jpg"];

        if (in_array($fileType, $allowedTypes)) {
            $targetPath = $uploadDirectory . $fileName;
            if (move_uploaded_file($fileTmpName, $targetPath)) {
                $sql = "INSERT INTO `tbl_user` (`uid`, `companyid`, `name`, `pwd`, `dept`, 
                    `email`, `grade`, `type`, `signstamp`, `bigo`, `inymd`, `outymd`, `reg_dt`)
                    VALUES ('$uid', '$companyid', '$name', '$pwd', '$dept', '$email', 
                    '$grade', '$type', '$fileName', '$bigo', '$inymd', '$outymd', '$reg_dt')";
            } else {
                echo $manage_image_upload;
            }
        } else {
            echo $manage_image_type;
        }
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['save_success'] =  $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
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

    if ($_FILES['udsignstamp_new']["name"] == "") {
        $sql = "UPDATE tbl_user SET 
            pwd='$pwd',
            name='$name',
            grade='$grade',
            email='$email',
            dept='$dept',
            companyid='$companyid',
            bigo='$bigo'
        WHERE uid ='$uid'";
    } else {
        $filePath = "../assets/uploads/" . $_POST['udsignstamp_old'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $uploadDirectory = "../assets/uploads/";
        $fileName = $_FILES["udsignstamp_new"]["name"];
        $fileTmpName = $_FILES["udsignstamp_new"]["tmp_name"];
        $fileType = $_FILES["udsignstamp_new"]["type"];
        $allowedTypes = ["image/jpeg", "image/png", "image/jpg"];

        if (in_array($fileType, $allowedTypes)) {
            $targetPath = $uploadDirectory . $fileName;
            if (move_uploaded_file($fileTmpName, $targetPath)) {
                $sql = "UPDATE tbl_user SET 
                    pwd='$pwd',
                    name='$name',
                    grade='$grade',
                    email='$email',
                    dept='$dept',
                    companyid='$companyid',
                    signstamp='$fileName',
                    bigo='$bigo'
            WHERE uid ='$uid'";
            } else {
                echo $manage_image_upload;
            }
        } else {
            echo $manage_image_type;
        }
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] =  $update_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Delete data to tbl_user table of database
if (isset($_POST['DeleteAM'])) {
    $uid = mysqli_real_escape_string($conn, $_POST['uduid']);
    $filePath = "../assets/uploads/" . $_POST['udsignstamp_old'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    
    $sql = "DELETE FROM `tbl_user` 
            WHERE uid ='$uid'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_success'] =  $delete_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}
