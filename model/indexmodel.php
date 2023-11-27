<?php
// Select data from tbl_notice
// (index.php)
if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('USER')) {
    $sql_notice = 'SELECT
        `tbl_notice`.*
        FROM
        `tbl_notice`
        JOIN `tbl_user` ON `tbl_notice`.`email` = `tbl_user`.`email`
        WHERE
        `tbl_notice`.`email` = `tbl_user`.`email`
        ORDER BY
        `tbl_notice`.`bid`';
} else {
    $sql_notice = 'SELECT * FROM `tbl_notice` ORDER BY `tbl_notice`.`bid`';
}
$result_notice = mysqli_query($conn, $sql_notice);
$notice_list = mysqli_fetch_all($result_notice, MYSQLI_ASSOC);

if (isset($_POST['bid_']) && isset($_POST['viewcnt_'])) {
    $bid = intval($_POST["bid_"]);
    $viewcnt = intval($_POST["viewcnt_"]) + 1;
    $bid_ = mysqli_real_escape_string($conn, $bid);
    $viewcnt_ = mysqli_real_escape_string($conn, $viewcnt);
    $sql = "UPDATE tbl_notice SET 
            viewcnt='$viewcnt_'
        WHERE bid ='$bid_'";

    if ($conn->query($sql) === TRUE) {
        header("Refresh: 0.0001;");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}
