<?php

// Database data
DEFINE('DB_HOST', '153.127.255.167');
DEFINE('DB_USER', 'ndk');
DEFINE('DB_PASSWORD', 'ganandkadm2019');
DEFINE('DB_NAME', 'newndk');

// Loginout Admin, User, ADMINISTRATOR
DEFINE('MAIN_ADMIN', '9');
DEFINE('MAIN_COMPANY_ID', '0');
DEFINE('ADMIN', '6');
DEFINE('USER', '1');
DEFINE('ADMINISTRATOR', '3');
DEFINE('DOMAIN_NAME', 'new.netdekintai.com');
DEFINE('URL_NAME', 'http://new.netdekintai.com/index.php');

// USE YES OR NO
DEFINE('USE_NO', '0');
DEFINE('USE_YES', '1');

// MAIN_ADMIN CODE TYPE
DEFINE('DEPARTMENT', '01');
DEFINE('VACATION_TYPE', '02');

// KINTAI CONFIG 
$MAX_INPUT_LENGTH_COMMENT = 17;
$MAX_INPUT_LENGTH_BIGO = 8;

// holy_decide 
$HOLY_DECIDE = array(
    0 => '通常',
    1 => '祝日',
    2 => '休暇',
    3 => '代休',
    9 => '欠勤'
);

// CODEMASTER CONFIG 
$MAX_LENGTH_CODE = 2;

// お知らせ登録
$PATH_IMAGE_NOTICE = "../assets/uploads/notice/";
$LENGTH_RANDOM_UNIQUE_NAME = 15;
$NOTICE_IMAGE_MAXSIZE = 2000000;  // 2MB = 2000000
$ALLOWED_TYPES = array("jpg", "jpeg", "png", "gif");
$IMAGE_UPLOAD_DIR = '../assets/uploads/notice/';

// 社員登録 + 管理者登録
$PATH_IMAGE_STAMP = "../assets/uploads/signstamp/";
$ALLOWED_TYPES_STAMP = array("png");
$STAMP_MAXSIZE = 2000000;  // 2MB = 2000000
$LENGTH_RANDOM_UNIQUE_NAME_STAMP = 16;
$IMAGE_UPLOAD_DIR_STAMP = '../assets/uploads/signstamp/';

// WORK_MONTH - STATUS 
$SUBMISSTION_STATUS = array(
    0 => '編集中',
    1 => '提出済み',
    2 => '担当者承認済み',
    3 => '責任者承認済み',
);

// ALLOW TYPE
DEFINE('SEARCH_ALLOW', '9');



// MIN LENGTH 社員登録のID&email
$MAX_LENGTH_UID_USER = 10;
