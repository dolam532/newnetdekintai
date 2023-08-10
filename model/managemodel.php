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
