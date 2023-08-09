<?php
$year = isset($_POST["selyy"]) ? $_POST["selyy"] : date('Y');
$month = isset($_POST["selmm"]) ? $_POST["selmm"] : date('m');
$day = isset($_POST["seldd"]) ? $_POST["seldd"] : date('d');

// Select database from tbl_userlogin table
$sql_userlogin = 'SELECT * FROM `tbl_userlogin` 
    WHERE YEAR(`tbl_userlogin`.`workymd`) IN("' . $year . '")
    AND MONTH(`tbl_userlogin`.`workymd`) IN("' . $month . '")
    AND DAY(`tbl_userlogin`.`workymd`) IN("' . $day . '")
    AND `tbl_userlogin`.`logtype` IN("' . constant('USER') . '", "' . constant('ADMIN') . '")';
$result_userlogin = mysqli_query($conn, $sql_userlogin);
$userlogin_list = mysqli_fetch_all($result_userlogin, MYSQLI_ASSOC);

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

// noticeList.php
// Select database from tbl_notice table
$sql_notice_select = 'SELECT DISTINCT
        `tbl_notice`.*,
        `tbl_user`.`name`
        FROM `tbl_notice`
        LEFT JOIN `tbl_user` ON `tbl_notice`.`uid` = `tbl_user`.`uid`';
$result_notice_select = mysqli_query($conn, $sql_notice_select);
$notice_list_select = mysqli_fetch_all($result_notice_select, MYSQLI_ASSOC);

// Search Data tbl_notice DB & tbl_user
if ($_POST['SearchButtonNL'] == NULL) {
    $notice_list = $notice_list_select;
} elseif (isset($_POST['SearchButtonNL'])) {
    if (!empty($notice_list_select)) {
        foreach ($notice_list_select as $key) {
            $Title[] = $key['title'];
            $Content[] = $key['content'];
        }
    }
    $Title = array_unique($Title);
    $Content = array_unique($Content);

    if ($_POST['searchKeywordTC'] == "") {
        $searchTitle = implode('","', $Title);
        $searchContent = implode('","', $Content);
    } else {
        if ($_POST['rdoSearch'] == "1") {
            $searchTitle = implode('","', $Title);
            $searchContent = trim($_POST['searchKeywordTC']);
        } else {
            $searchTitle = trim($_POST['searchKeywordTC']);
            $searchContent = implode('","', $Content);
        }
    }
    $sql_notice = 'SELECT DISTINCT
        `tbl_notice`.*,
        `tbl_user`.`name`
        FROM `tbl_notice`
        LEFT JOIN `tbl_user` ON `tbl_notice`.`uid` = `tbl_user`.`uid`
        WHERE `tbl_notice`.`title` IN ("' . $searchTitle . '")
        AND `tbl_notice`.`content` IN ("' . $searchContent . '")';
    $result_notice = mysqli_query($conn, $sql_notice);
    $notice_list = mysqli_fetch_all($result_notice, MYSQLI_ASSOC);
}

// Save Data to tbl_notice DB 
if (isset($_POST['btnRegNL'])) {
    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $reader = mysqli_real_escape_string($conn, $_POST['reader']);
    $viewcnt = mysqli_real_escape_string($conn, $_POST['viewcnt']);
    $reg_dt = mysqli_real_escape_string($conn, $_POST['reg_dt']);

    $sql = "INSERT INTO `tbl_notice` (`title`, `content`, `reader`, `viewcnt`, `uid`, `reg_dt`)
                VALUES ('$title', '$content', '$reader', '$viewcnt', '$uid', '$reg_dt')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['save_success'] =  $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update Data to tbl_notice DB 
if (isset($_POST['btnUpdateNL'])) {
    $bid = mysqli_real_escape_string($conn, $_POST['udbid']);
    $uid = mysqli_real_escape_string($conn, $_POST['uduid']);
    $title = mysqli_real_escape_string($conn, $_POST['udtitle']);
    $content = mysqli_real_escape_string($conn, $_POST['udcontent']);
    $reader = mysqli_real_escape_string($conn, $_POST['udreader']);
    $viewcnt = mysqli_real_escape_string($conn, $_POST['udviewcnt']);
    $reg_dt = mysqli_real_escape_string($conn, $_POST['udreg_dt']);

    $sql = "UPDATE tbl_notice SET 
                title='$title',
                content='$content',
                reader='$reader',
                viewcnt='$viewcnt',
                reg_dt='$reg_dt'
            WHERE bid ='$bid'
            AND uid ='$uid'";

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
