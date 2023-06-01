<?php


//========================================================//
// <!-- Query for kintai Register <1>  --> 
//========================================================//

 // get user by uid
$QUERY_SELECT_USER_BY_ID = "SELECT * FROM `tbl_user` WHERE `uid` = ?"; 

// get work time of month workmd
$QUERY_SELECT_WORKMD_BYID_YM = "SELECT uid, genid, date_format(workymd,'%Y/%m/%d') as workymd, 
    SUBSTR('日月火水木金土', DAYOFWEEK(workymd), 1) as weekday, 
    (select count(holiday) from tbl_holiday where holiday = workymd and companyid = (select companyid from tbl_user where uid = ?)) as holiday, 
    daystarthh, daystartmm, dayendhh, dayendmm, jobstarthh, jobstartmm, jobendhh, jobendmm, offtimehh, offtimemm, workhh, workmm, janhh, janmm, comment, bigo, REG_DT 
    FROM tbl_worktime 
    WHERE uid = ? and DATE_FORMAT(workymd, '%Y%m') = DATE_FORMAT(?, '%Y%m') 
    ORDER BY workymd";







?>