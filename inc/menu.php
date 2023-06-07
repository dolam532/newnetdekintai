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

        /* 모달 팝업 lock */
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
                    <li class="dropdown active">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="../#" aria-expanded="false">勤怠 <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="menu-level1"><a href="../kintai/kintaiReg.php">勤務表（本社用）</a></li>
                            <li class="menu-level1"><a href="../kintai/kintaiMonthly.php">月間勤怠</a></li>
                        </ul>
                    </li>
                    <li class="dropdown active">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="../#" aria-expanded="false">休暇 <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="menu-level1"><a href="../kyuka/kyukaReg.php">休暇届</a></li>
                            <li class="menu-level3"><a href="../kyuka/kyukaMonthly.php">休暇使用現状</a></li>
                        </ul>
                    </li>
                    <li class="dropdown active" id="manager">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="../#" aria-expanded="false">基本情報
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="menu-level3"><a href="">基礎コード登録</a></li>
                            <li class="menu-level3"><a href="">勤務日登録</a></li>
                            <li class="menu-level3"><a href="">祝日登録</a></li>
                            <li class="menu-level3"><a href="../user/userList.php">社員登録</a></li>
                            <li class="menu-level3"><a href="">年次休暇登録</a></li>
                            <li class="menu-level3"><a href="">現場登録</a></li>
                            <li class="menu-level3"><a href="">現場別勤務社員</a></li>
                            <li class="menu-level3"><a href="">社員ログイン内訳</a></li>
                            <li class="menu-level3"><a href="">管理情報登録</a>
                            </li>
                            <li class="menu-level3"><a href="">お知らせ登録</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown active" id="gana">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="../#" aria-expanded="true">GANASYS<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="">使用者登録</a></li>
                            <li><a href="">管理者登録</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href=""><span class="glyphicon glyphicon-user" id="username">
                                社員名</span></a></li>
                    <li class="login"><a href="../login/login.php"><span class="glyphicon glyphicon-log-in" id="login"> Login</span></a></li>
                    <li class="logout" style="display: none;"><a href=""><span class="glyphicon glyphicon-log-out" id="logout"> Logout</span></a></li>
                </ul>
            </div>
        </div>
    </nav>
</div>

</html>