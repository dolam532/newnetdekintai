<!DOCTYPE html>
<html>

<head>
    <style>
        /* modal position(center)*/
        .modal {
            text-align: center;
        }

        @media screen and (min-width: 768px) {
            .modal:before {
                display: inline-block;
                vertical-align: middle;
                content: " ";
                height: 100%;
            }
        }

        /* modal popup lock */
        .modal-dialog {
            display: inline-block;
            text-align: left;
            vertical-align: middle;
        }
    </style>
</head>
<div id="tile_header">
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../index.php">NET DE KINTAI</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="../index.php">Home</a></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="../#" aria-expanded="false">勤怠 <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="menu-level1"><a href="../kintai/kintaiReg.php">勤務表（本社用）</a></li>
                            <li class="menu-level1"><a href="../kintai/kintaiMonthly.php">月間勤怠</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="../#" aria-expanded="false">休暇 <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="menu-level1"><a href="../kyuka/kyukaReg.php">休暇届</a></li>
                            <li class="menu-level3"><a href="../kyuka/kyukaMonthly.php">休暇使用現状</a></li>
                        </ul>
                    </li>
                    <li class="dropdown" id="manager">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="../#" aria-expanded="false">基本情報
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="menu-level3"><a href="../contact/codemasterList.php">基礎コード登録</a></li>
                            <li class="menu-level3"><a href="../info/workdayList.php">勤務日登録</a></li>
                            <li class="menu-level3"><a href="../info/holidayReg.php">祝日登録</a></li>
                            <li class="menu-level3"><a href="../user/userList.php">社員登録</a></li>
                            <li class="menu-level3"><a href="../info/uservacationList.php">年次休暇登録</a></li>
                            <li class="menu-level3"><a href="../user/genbaList.php">勤務管理表</a></li>
                            <li class="menu-level3"><a href="../user/genbaUserList.php">現場別勤務社員</a></li>
                            <li class="menu-level3"><a href="../contact/userloginList.php">社員ログイン内訳</a></li>
                            <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) : ?>
                                <li class="menu-level3"><a href="../manage/manageInfo.php">管理情報登録</a></li>
                            <?php endif; ?>
                            <li class="menu-level3"><a href="../contact/noticeList.php">お知らせ登録</a></li>
                        </ul>
                    </li>
                    <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) : ?>
                        <li class="dropdown" id="gana">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="../#" aria-expanded="true">GANASYS<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../manage/companyList.php">使用者登録</a></li>
                                <li><a href="../manage/companyList.php">管理者登録</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php if (isset($_SESSION['auth'])) : ?>
                        <li><a href=""><span class="glyphicon glyphicon-user" id="username">
                                    <?= $_SESSION['auth_name'] ?></span></a></li>
                        <li class="logout"><a href="../loginout/loginout.php"><span class="glyphicon glyphicon-log-out" id="logout"> Logout</span></a></li>
                    <?php else : ?>
                        <li><a href=""><span class="glyphicon glyphicon-user" id="username">
                                    社員名</span></a></li>
                        <li class="login"><a href="../loginout/loginout.php"><span class="glyphicon glyphicon-log-in" id="login"> Login</span></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</div>

</html>