<?php
session_start();
include('../inc/header.php');
include('../model/loginmodel.php');

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
</style>
<div id="tile_body">
    <!-- login form {s} -->
    <form action="" method="post">
        <?php
        if (isset($_SESSION['login_empty'])) {
        ?>
            <div class="alert alert-warning alert-dismissible" role="alert" auto-close="3000">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo $_SESSION['login_empty']; ?>
            </div>
        <?php
            unset($_SESSION['login_empty']);
        } elseif (isset($_SESSION['login_fail'])) {
        ?>
            <div class="alert alert-warning alert-dismissible" role="alert" auto-close="3000">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo $_SESSION['login_fail']; ?>
            </div>
        <?php
            unset($_SESSION['login_fail']);
        }
        ?>
        <div class="text-center mb-4">
            <h1 class="h2 mb-3 font-weight-normal" style="margin-bottom:40px">NETDEKINTAI.COM</h1>
        </div>

        <div class="form-label-group">
            <input id="uid" name="uid" placeholder="User ID" class="form-control" type="text" value="">
            <label for="uid" class="sr-only">User ID</label>
        </div>
        <br>
        <div class="form-label-group" id="divpwd">
            <input id="pwd" name="pwd" placeholder="User Password" class="form-control" type="password" value="">
            <label for="pwd" class="sr-only">User Password</label>
        </div>
        <div class="form-label-group" id="divname" style="display: none;">
            <input id="name" name="name" class="form-control" type="text" value="">
            <label for="name" class="sr-only">User Name</label>
        </div>
        <br>
        <input class="btn btn-lg btn-primary btn-block" type="submit" id="btnLogin" name="btnLogin" value="Sign in">
        <input class="btn btn-lg btn-primary btn-block" type="button" id="btnLogout" name="btnLogout" style="display: none;" value="Sign out">
        <span style="font-size:0.7em;">毎月の勤怠資料がない場合は新規登録するからログイン時間がかかる場合もあります。</span>
        <p class="mt-5 mb-3 text-muted text-center" style="margin-top:40px">© 2019. GANASYS. All rights reserved.</p>
    </form>
</div>
<?php include('../inc/footer.php'); ?>