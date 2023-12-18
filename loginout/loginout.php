<?php
session_start();
include('../inc/header.php');
include('../inc/message.php');
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

    .form-control {
        padding: 25px 12px;
    }

    label {
        float: left;
    }

    button#toggle-password {
        margin-right: -85%;
        margin-top: -22%;
    }
</style>
<div id="tile_body">
    <!-- loginout form {s} -->
    <form action="" method="post">
        <?php
        if (isset($_SESSION['login_fail'])) {
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
            <strong>
                <h1 class="h2 mb-3 font-weight-normal" style="margin-bottom:40px; font-weight: bold; font-size: 45px;">ネットで勤怠</h1>
            </strong>
        </div>
        <?php if (isset($_SESSION['auth'])) : ?>
            <title>ログアウト</title>
            <div class="form-label-group">
                <input id="email" name="email" placeholder="Email" class="form-control" type="text" value="<?= $_SESSION['auth_email'] ?>">
                <label for="email" class="sr-only">Email</label>
            </div>
            <br>
            <div class="form-label-group" id="divpwd">
                <input id="name" name="name" class="form-control" type="text" value="<?= $_SESSION['auth_name'] ?>">
                <label for="name" class="sr-only">Name</label>
            </div>
            <br>
            <input class="btn btn-lg btn-primary btn-block" type="submit" id="btnLogout" name="btnLogout" value="Sign out">
        <?php else : ?>
            <title>ログイン</title>
            <label for="email">メールアドレス</label><br>
            <div class="form-label-group">
                <label for="email" class="sr-only">メールアドレス</label>
                <input id="email" name="email" placeholder="メールアドレス" class="form-control" type="text" value="<?= $_POST['email'] ?>">
            </div>
            <br>
            <label for="password">パスワード</label><br>
            <div class="form-label-group" id="divpwd">
                <label for="pwd" class="sr-only">パスワード</label>
                <input id="pwd" name="pwd" placeholder="パスワード" class="form-control" type="password" value="<?= $_POST['pwd'] ?>">
                <button class="btn btn-default" type="button" id="toggle-password">
                    <span id="eye-icon" class="glyphicon glyphicon-eye-close"></span>
                </button>
            </div>
            <br>
            <input class="btn btn-lg btn-primary btn-block" type="submit" id="btnLogin" name="btnLogin" value="ログイン">
        <?php endif; ?>
        <span style="font-size:0.7em;">毎月の勤怠資料がない場合は新規登録するからログイン時間がかかる場合もあります。</span>
        <p class="mt-5 mb-3 text-muted text-center" style="margin-top:40px">© 2019. GANASYS. All rights reserved.</p>
    </form>
</div>
<script>
    // Login button handling
    $(document).on('click', '#btnLogin', function(e) {
        var email = $("#email").val();
        var pwd = $("#pwd").val();
        var emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
        var passwordRegex = /^[A-Za-z0-9]+$/; // 半角英数字のみを許可

        if (email == "") {
            alert("<?php echo $user_email_empty; ?>");
            e.preventDefault();
            $("#email").focus();
            return false;
        }

        if (pwd == "") {
            alert("<?php echo $user_pwd_empty; ?>");
            e.preventDefault();
            $("#pwd").focus();
            return false;
        }

        if (!emailRegex.test(email)) {
            alert("<?php echo $login_email_fail; ?>");
            e.preventDefault();
            $("#email").focus();
            return false;
        }

        if (!passwordRegex.test(pwd)) {
            alert("<?php echo $login_pwd_fail; ?>");
            e.preventDefault();
            $("#pwd").focus();
            return false;
        }
    });

    window.onload = function() {
        setTimeout(hideLoadingOverlay, 100);
        startLoading();
    };
    // loading UX
    function showLoadingOverlay() {
        const overlay = document.getElementById("overlay");
        overlay.style.display = "block";
        document.body.style.pointerEvents = "none";
    }

    function hideLoadingOverlay() {
        const overlay = document.getElementById("overlay");
        overlay.style.display = "none";
        document.body.style.pointerEvents = "auto";
    }

    showLoadingOverlay();

    function startLoading() {
        NProgress.start();
        setTimeout(function() {
            NProgress.done();
        }, 100);
    }

    // JavaScript to toggle password visibility
    $(document).ready(function() {
        $("#toggle-password").click(function() {
            var pwdInput = $("#pwd");
            var eyeIcon = $("#eye-icon");

            // Toggle password visibility
            if (pwdInput.attr("type") === "password") {
                pwdInput.attr("type", "text");
                eyeIcon.removeClass("glyphicon-eye-close").addClass("glyphicon-eye-open");
            } else {
                pwdInput.attr("type", "password");
                eyeIcon.removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close");
            }
        });
    });
</script>
<?php include('../inc/footer.php'); ?>