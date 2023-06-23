<?php
	include('header.php'); 
	include('User.php');

	$user = new User($db);

	if(isset($_POST['btnLogin'])) {
		if(empty($_SESSION['captcha_code'] ) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0){  
			// Captcha verification is incorrect
			$msg = "验证码不正确";
		} else {
			// Captcha verification is Correct
			if($user->login($_POST['username'], md5($_POST['password']))) {
				// login success
				if(isset($_POST['rememberMe'])) {
					// remember me checked
					$user_id = $_SESSION['user_id'];
					$username = $_SESSION['username'];
					setcookie('remember_me', $user_id . ':' . $username, time() + (86400 * 30));
				}
                header("Location:index.php");
				return;
			} else {
				$msg = "用户名或密码不正确";
			}
		}
		
		echo <<<END
			<div class="alert alert-danger alert-dismissible container">
				$msg
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		END;
	}
?>

<script type='text/javascript'>
	function refreshCaptcha() {
		let img = document.getElementById("captchaimg");
		img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
	}
</script>

<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-4">
			<form action="login.php" method="POST">

				<h1 class="h3 mb-3 fw-normal">登录</h1>
				
				<div class="form-floating mb-3">
					<input type="text" class="form-control" id="floatingInput" placeholder="用户名" name="username" required>
					<label for="floatingInput">用户名</label>
				</div>

				<div class="form-floating mb-3">
				<input type="password" class="form-control" id="floatingPassword" placeholder="密码" name="password" required>
				<label for="floatingPassword">密码</label>
				</div>

				<div class="text-center mb-3">
					<a href='javascript: refreshCaptcha();'><img src="captcha.php?rand=<?php echo rand(); ?>" id="captchaimg" class="rounded"></a>
				</div>

				<div class="form-floating mb-3">
					<input type="text" class="form-control" id="floatingInput" placeholder="验证码" name="captcha_code" required>
					<label for="floatingInput">验证码</label>
				</div>

				<div class="form-check text-start my-3 mb-3">
					<input class="form-check-input" type="checkbox" name="rememberMe">
					<label class="form-check-label" for="flexCheckDefault">记住我</label>
				</div>
				
				<button class="btn btn-primary w-100 py-2" type="submit" name="btnLogin" >Login</button>
			</form>
		</div>
	</div>
</div>