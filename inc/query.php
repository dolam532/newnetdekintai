<?php
// Query for kintai Register <1>  
// get user by uid
$QUERY_SELECT_USER_BY_ID = "SELECT * FROM `tbl_user` WHERE `uid` = ?";

//　get work time of month workmd 
$QUERY_SELECT_WORKMD = "SELECT uid, genid, date_format(workymd,'%Y/%m/%d') as workymd, 
    SUBSTR('日月火水木金土', DAYOFWEEK(workymd), 1) as weekday, 
    (select count(holiday) from tbl_holiday where holiday = workymd and companyid = (select companyid from tbl_user where uid = ?)) as holiday, 
    daystarthh, daystartmm, dayendhh, dayendmm, jobstarthh, jobstartmm, jobendhh, jobendmm, offtimehh, offtimemm, workhh, workmm, janhh, janmm, comment, bigo, REG_DT 
    FROM tbl_worktime 
    WHERE uid = ? and DATE_FORMAT(workymd, '%Y%m') = DATE_FORMAT(?, '%Y%m') 
    ORDER BY workymd";

//　get work time of month
$QUERY_SELECT_WORKYM = "SELECT a.uid,b.name as username,a.genid,a.workym,a.jobhour, a.jobminute,a.workdays,a.jobdays,a.jobhour2, a.jobminute2,a.workdays2,a.jobdays2,a.offdays
,a.delaydays,a.earlydays ,a.janhour, a.janminute,a.janhour2, a.janminute2,a.bigo FROM tbl_workmonth a 
left join tbl_user b on a.uid = b.uid WHERE a.uid like (case when ? = '' then '%' else ? end) and a.workym = ? "; // a.workym = '202305' 


//　insert month to db when date missing
$QUERY_INSERT_MANY_WORK_OF_MONTH = "INSERT INTO tbl_worktime (`uid`, genid, workymd, daystarthh, daystartmm, dayendhh, dayendmm, jobstarthh, jobstartmm,  
jobendhh, jobendmm, offtimehh, offtimemm, workhh, workmm, comment, bigo, REG_DT)
VALUES ( ?, ?, ?, null, null, null, null, null, null, null, null, null, null,  null,  null, null, null, now()) ;";


//　insert new month
$QUERY_INSERT_NEW_WORK_OF_MONTH = "INSERT INTO tbl_worktime (`uid`, genid, workymd, daystarthh, daystartmm, dayendhh, dayendmm, jobstarthh, jobstartmm,  
jobendhh, jobendmm, offtimehh, offtimemm, workhh, workmm, comment, bigo, REG_DT)
VALUES ";

//　update data of month
$QUERY_UPDATE_DATA_WORK_OF_YMD = "UPDATE tbl_worktime SET daystarthh = ?, daystartmm = ?, 
  dayendhh = ?, dayendmm = ?, jobstarthh = ?, jobstartmm = ?, jobendhh = ?, jobendmm = ?, 
  offtimehh = ?, offtimemm = ?, workhh = ?, workmm = ?,  comment = ?, bigo = ?, REG_DT = NOW() 
  WHERE uid = ? AND workymd = ?";

//　Select count data of Monthly -> Table workmonth
$QUERY_SELECT_WORK_OF_YM = "SELECT * FROM tbl_workmonth WHERE workym = ? 
                            AND uid = ? ;";

//　insert data of monthly -> Table workmonth
$QUERY_INSERT_NEW_WORK_OF_YM = "INSERT INTO tbl_workmonth 
  (workym, uid, genid, jobhour, jobminute, jobhour2, jobminute2, 
   workdays, workdays2, 
  jobdays, jobdays2, offdays, delaydays, earlydays, bigo, REG_DT)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW());";

// update data of Monthly-> Table workmonth
$QUERY_UPDATE_DATA_WORK_OF_YM = "UPDATE tbl_workmonth SET genid = ?, jobhour = ?, 
  jobminute = ?, jobhour2 = ?, 
  jobminute2 = ?, workdays = ?, workdays2 = ?, jobdays = ?, jobdays2 = ? , offdays = ? , 
  delaydays = ?, earlydays = ? , bigo = ?, REG_DT = NOW() 
  WHERE uid = ? AND workym = ?";

// delete
$QUERY_DELETE_DATA_WORK_OF_YMD = "UPDATE tbl_worktime SET daystarthh = null, daystartmm = null, 
dayendhh = null, dayendmm = null, jobstarthh = null, jobstartmm = null, jobendhh = null, jobendmm = null, 
offtimehh = null, offtimemm = null, workhh = null, workmm = null, comment = null, bigo = null, janhh=null , janmm = null, REG_DT = NOW() 
  WHERE uid = ? AND workymd = ?";

// Query kintaimanager  <2>
$QUERY_GET_ALL_KINTAI = "SELECT * FROM tbl_genba ORDER BY genid ;" ;

$QUERY_INSERT_KINMU = "INSERT INTO tbl_genba
                     (genbaname ,workstrtime , workendtime ,offtime1 ,offtime2 , bigo  , use_yn , REG_DT ) 
                      VALUES (? , ? , ? , ? , ? , ? , ? , NOW()) ";

$QUERY_UPDATE_KINMU = "UPDATE tbl_genba SET genbaname = ?, workstrtime = ?, 
workendtime = ?, offtime1 = ?, offtime2 = ?, bigo = ?, use_yn = ?, REG_DT = NOW() 
WHERE genid = ?" ;

$QUERY_DELETE_KINMU = "DELETE FROM tbl_genba WHERE genid = ?" ;
