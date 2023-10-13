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
DEFINE('DOMAIN_NAME', 'new.netdekintai.com');
DEFINE('URL_NAME', 'http://new.netdekintai.com/index.php');

// GANASYS COMPANY NOTE
DEFINE('GANASYS_COMPANY_ID', '1');
DEFINE('GANASYS_COMPANY_TYPE', '1');

// USE YES OR NO
DEFINE('USE_NO', '0');
DEFINE('USE_YES', '1');



///===== KINTAI CONFIG ===== //// 
$MAX_INPUT_LENGTH_COMMENT = 17;
$MAX_INPUT_LENGTH_BIGO = 8;


///===== CODEMASTER CONFIG ===== //// 
$MAX_LENGTH_CODE = 2;


///===== お知らせ登録===== //// 
$PATH_IMAGE_NOTICE = "../assets/uploads/notice/";
$LENGTH_RANDOM_UNIQUE_NAME = 15;
$NOTICE_IMAGE_MAXSIZE = 2000000; // 2MB = 2000000
$ALLOWED_TYPES = array("jpg", "jpeg", "png", "gif");
$IMAGE_UPLOAD_DIR = '/var/www/html/newnetdekintai/assets/uploads/notice/';



///===== 社員登録　+ 管理者登録===== //// 
$PATH_IMAGE_STAMP = "../assets/uploads/signstamp/";
$ALLOWED_TYPES_STAMP = array("png");
$STAMP_MAXSIZE = 2000000; // 2MB = 2000000
$LENGTH_RANDOM_UNIQUE_NAME_STAMP = 16;
$IMAGE_UPLOAD_DIR_STAMP = '/var/www/html/newnetdekintai/assets/uploads/signstamp/';