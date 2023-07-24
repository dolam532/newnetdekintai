<?php
// Select data from tbl_genba
$sql_genba = 'SELECT * FROM `tbl_genba`';
$result_genba = mysqli_query($conn, $sql_genba);
$genbadatas_list = mysqli_fetch_all($result_genba, MYSQLI_ASSOC);

// Save data to tbl_genba table of database
$reg_dt = date('Y-m-d H:i:s');
$upt_dt = date('Y-m-d H:i:s');

if (isset($_POST['SaveKinmu'])) {
    $companyid = 0;
    $strymd = "";
    $endymd = "";
    $genbacompany = "";
    $genbaname = mysqli_real_escape_string($conn, $_POST['genbaname_rmodal']);
    $use_yn = mysqli_real_escape_string($conn, $_POST['use_rmodal']);
    $workstrtime = mysqli_real_escape_string($conn, $_POST['workstr_rmodal']);
    $workendtime = mysqli_real_escape_string($conn, $_POST['workend_rmodal']);
    $offtime1 = mysqli_real_escape_string($conn, $_POST['offtime1_rmodal']);
    $offtime2 = mysqli_real_escape_string($conn, $_POST['offtime2_rmodal']);
    $bigo = mysqli_real_escape_string($conn, $_POST['bigo_rmodal']);

    $sql_genba_insert = mysqli_query($conn, "INSERT INTO `tbl_genba` (`genbaname`, `genbacompany`, `companyid`, `strymd`, `endymd`, `use_yn`, `workstrtime`, `workendtime`, `offtime1`, `offtime2`, `bigo`, `reg_dt`, `upt_dt`)
                VALUES ('$genbaname', '$genbacompany', '$companyid', '$strymd', '$endymd', '$use_yn', '$workstrtime', '$workendtime', '$offtime1', '$offtime2', '$bigo', '$reg_dt', '$upt_dt')");

    if ($sql_genba_insert) {
        $_SESSION['save_success'] =  $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Save data to tbl_genba table of database
if (isset($_POST['UpdateKinmu'])) {
    $genid = mysqli_real_escape_string($conn, $_POST['genid_cmodal']);
    $genbaname = mysqli_real_escape_string($conn, $_POST['genbaname_cmodal']);
    $companyid = mysqli_real_escape_string($conn, $_POST['companyid_cmodal']);
    $strymd = mysqli_real_escape_string($conn, $_POST['strymd_cmodal']);
    $endymd = mysqli_real_escape_string($conn, $_POST['endymd_cmodal']);
    $use_yn = mysqli_real_escape_string($conn, $_POST['use_cmodal']);
    $workstrtime = mysqli_real_escape_string($conn, $_POST['workstr_cmodal']);
    $workendtime = mysqli_real_escape_string($conn, $_POST['workend_cmodal']);
    $offtime1 = mysqli_real_escape_string($conn, $_POST['offtime1_cmodal']);
    $offtime2 = mysqli_real_escape_string($conn, $_POST['offtime2_cmodal']);
    $bigo = mysqli_real_escape_string($conn, $_POST['bigo_cmodal']);
    $sql = "UPDATE tbl_genba SET 
            genbaname='$genbaname',
            companyid='$companyid',
            strymd='$strymd',
            endymd='$endymd',
            workstrtime='$workstrtime',
            workendtime='$workendtime',
            offtime1='$offtime1',
            offtime2='$offtime2',
            bigo='$bigo',
            use_yn='$use_yn',
            upt_dt='$upt_dt'
        WHERE genid ='$genid'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] =  $update_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Delete data to tbl_genba table of database
if (isset($_POST['DeleteKinmu'])) {
    $genid = mysqli_real_escape_string($conn, $_POST['genid_cmodal']);
    $genbaname = mysqli_real_escape_string($conn, $_POST['genbaname_cmodal']);

    $sql = "DELETE FROM `tbl_genba` 
            WHERE genid ='$genid' AND genbaname ='$genbaname'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_success'] =  $delete_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}
