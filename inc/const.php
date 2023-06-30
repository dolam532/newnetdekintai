<?php

// Database data
DEFINE('DB_HOST', '153.127.255.167');
DEFINE('DB_USER', 'ndk');
DEFINE('DB_PASSWORD', 'ganandkadm2019');
DEFINE('DB_NAME', 'newndk');

// Loginout Admin, User, ADMINISTRATOR
DEFINE('ADMIN', '9');
DEFINE('USER', '1');
DEFINE('ADMINISTRATOR', '3');

// GANASYS COMPANY NOTE
DEFINE('GANASYS_COMPANY_ID', '1');
DEFINE('GANASYS_COMPANY_TYPE', '1');

// kintai showyear start
DEFINE('START_SHOW_YEAR_KINMUHYO', '1950');

// request_param_kintai
$TYPE_GET_WORK_YEAR_MONTH_DAY = 'getKintaiymd';
$TYPE_GET_WORK_YEAR_MONTH = 'getKintaiym';
$TYPE_INSERT_MISSING_WORK_YEAR_MONTH_DAY = 'insertWorkYearMonthDay';
$TYPE_INSERT_NEW_WORK_YEAR_MONTH_DAY = 'insertNewWorkYearMonthDay';
$TYPE_REGISTER_DATA_OF_SELETED_DAY = 'registerDataSelectedDay';
$TYPE_DELETE_DATA_OF_SELETED_DAY = 'deleteDataSelectedDay';
$TYPE_REGISTER_DATA_OF_MONTH = 'monthDataRegister';
$TYPE_GET_DATA_KINMUHYO = 'getDataKinmuhyo';
$TYPE_REGISTER_NEW_DATA_OF_MONTH  = 'monthDataNewRegister';
$TYPE_GETCURRENT_USER  = 'getCurrentUser';

// request_param_kinmu
$TYPE_GET_ALL_DATA_KINMU = 'getAllDataKinmu';
$TYPE_UPDATE_DATA_KINMU = 'updateDataKinmu';
$TYPE_INSERT_DATA_KINMU = 'insertDataKinmu';
$TYPE_DELETE_DATA_KINMU = 'deleteDataKinmu';

// name 
$COMPANY_NAME = 'ガナシス株式会社';
$SIGN_TITLE1= '社長';
$SIGN_TITLE2= '担当';
$TIME_KINTAI_DELAY_IN = '0'; //遅刻　：　　業務開始時間（分） －  TIME_KINTAI_DELAY_IN（分）　ー　出社時間（分） < 0
$TIME_KINTAI_EARLY_OUT = '0'; //早退　：　　業務終了時間（分） ＋　TIME_KINTAI_EARLY_OUT（分） － 退社時間 （分） > 0
$DEFAULT_GENBA_ID = '0'; //