<?php
// Select data from tbl_user
$sql_user = 'SELECT * FROM `tbl_user`';
$result_user = mysqli_query($conn, $sql_user);
$user_list = mysqli_fetch_all($result_user, MYSQLI_ASSOC);

// Select data from tbl_codebase
$sql_codebase = 'SELECT `code`, `name` FROM `tbl_codebase`
WHERE `tbl_codebase`.`typecode` = 02 GROUP BY `code`, `name`';
$result_codebase = mysqli_query($conn, $sql_codebase);
$codebase_list = mysqli_fetch_all($result_codebase, MYSQLI_ASSOC);

// Select data from tbl_genba
$sql_genba = 'SELECT * FROM `tbl_genba`';
$result_genba = mysqli_query($conn, $sql_genba);
$genba_list = mysqli_fetch_all($result_genba, MYSQLI_ASSOC);
