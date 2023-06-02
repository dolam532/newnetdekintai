<?php


//========================================================//
// <!-- Query for kintai Register <1>  --> 
//========================================================//

//==//                  <!-- get user by uid  --> 
$QUERY_SELECT_USER_BY_ID = "SELECT * FROM `tbl_user` WHERE `uid` = ?"; 


//==//  <!--  get work time of month workmd  --> 
$QUERY_SELECT_WORKMD= "SELECT uid, genid, date_format(workymd,'%Y/%m/%d') as workymd, 
    SUBSTR('日月火水木金土', DAYOFWEEK(workymd), 1) as weekday, 
    (select count(holiday) from tbl_holiday where holiday = workymd and companyid = (select companyid from tbl_user where uid = ?)) as holiday, 
    daystarthh, daystartmm, dayendhh, dayendmm, jobstarthh, jobstartmm, jobendhh, jobendmm, offtimehh, offtimemm, workhh, workmm, janhh, janmm, comment, bigo, REG_DT 
    FROM tbl_worktime 
    WHERE uid = ? and DATE_FORMAT(workymd, '%Y%m') = DATE_FORMAT(?, '%Y%m') 
    ORDER BY workymd";

//==//                  <!-- get work time of month  --> 
$QUERY_SELECT_WORKYM = "SELECT a.uid,b.name as username,a.genid,a.workym,a.jobhour, a.jobminute,a.workdays,a.jobdays,a.jobhour2, a.jobminute2,a.workdays2,a.jobdays2,a.offdays
,a.delaydays,a.earlydays ,a.janhour, a.janminute,a.janhour2, a.janminute2,a.bigo FROM tbl_workmonth a 
left join tbl_user b on a.uid = b.uid WHERE a.uid like (case when ? = '' then '%' else ? end) and a.workym = ? "   // a.workym = '202305' 

?>