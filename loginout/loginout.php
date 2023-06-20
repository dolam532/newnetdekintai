<?php
session_start();
include('../inc/header.php');
include('../model/loginoutmodel.php');

?>
<style>
    body {
        text-align: center;
        margin-top: 5%;
        line-height: 2;
    }

    form {
        display: inline-block;
    }

    /* For Mobile Landscape View iPhone XR,12Pro */
    @media screen and (max-device-width: 896px) and (orientation: landscape) {
        div#tile_body {
            margin-top: -3%;
            margin-bottom: -5%;
        }
    }

    /* For Mobile Landscape View iPhone X,6,7,8 PLUS */
    @media screen and (max-device-width: 837px) and (orientation: landscape) {
        div#tile_body {
            margin-top: -5%;
            margin-bottom: -5%;
        }
    }

    /* For Mobile Landscape View iPhone XR,12Pro */
    @media screen and (max-device-width: 414px) and (orientation: portrait) {
        form {
            margin-right: 16px;
            margin-left: 15px;
        }
    }
</style>
<div id="tile_body">
    <!-- loginout form {s} -->
    <form action="" method="post">
        <?php if (isset($_SESSION['login_empty'])) : ?>
            <div class="alert alert-warning alert-dismissible" role="alert" auto-close="3000">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo $_SESSION['login_empty']; ?>
            </div>
        <?php
            unset($_SESSION['login_empty']);
        elseif (isset($_SESSION['login_fail'])) :
        ?>
            <div class="alert alert-warning alert-dismissible" role="alert" auto-close="3000">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo $_SESSION['login_fail']; ?>
            </div>
        <?php
            unset($_SESSION['login_fail']);
        endif;
        ?>
        <div class="text-center mb-4">
            <h1 class="h2 mb-3 font-weight-normal" style="margin-bottom:40px">NETDEKINTAI.COM</h1>
        </div>
        <?php if (isset($_SESSION['auth'])) : ?>
            <div class="form-label-group">
                <input id="uid" name="uid" placeholder="User ID" class="form-control" type="text" value="<?= $_SESSION['auth_uid'] ?>">
                <label for="uid" class="sr-only">User ID</label>
            </div>
            <br>
            <div class="form-label-group" id="divpwd">
                <input id="name" name="name" class="form-control" type="text" value="<?= $_SESSION['auth_name'] ?>">
                <label for="name" class="sr-only">User Name</label>
            </div>
            <br>
            <input class="btn btn-lg btn-primary btn-block" type="submit" id="btnLogout" name="btnLogout" value="Sign out">
        <?php else : ?>
            <div class="form-label-group">
                <input id="uid" name="uid" placeholder="User ID" class="form-control" type="text" value="<?= $_POST['uid'] ?>">
                <label for="uid" class="sr-only">User ID</label>
            </div>
            <br>
            <div class="form-label-group" id="divpwd">
                <input id="pwd" name="pwd" placeholder="User Password" class="form-control" type="password" value="<?= $_POST['pwd'] ?>">
                <label for="pwd" class="sr-only">User Password</label>
            </div>
            <br>
            <input class="btn btn-lg btn-primary btn-block" type="submit" id="btnLogin" name="btnLogin" value="Sign in">
        <?php endif; ?>
        <span style="font-size:0.7em;">毎月の勤怠資料がない場合は新規登録するからログイン時間がかかる場合もあります。</span>
        <p class="mt-5 mb-3 text-muted text-center" style="margin-top:40px">© 2019. GANASYS. All rights reserved.</p>
    </form>
</div>
<script>
    //로그인 버튼 처리
    $(document).on('click', '#btnLogin', function(e) {
        var uid = $("#uid").val(); //태그의 value 속성값
        var letters = /^[A-Za-z]+$/;

        if (!uid.match(letters)) {
            alert("IDをアルファベットで入力してください。");
            e.preventDefault();
            $("#uid").focus();
            return true;
        }
    });
</script>
<?php include('../inc/footer.php'); ?>