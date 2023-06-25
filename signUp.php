<?php
	include_once('User.php');
	include_once('header.php');

	$user = new User($db);

	if(isset($_POST['btnSignUp'])) {
		if(empty($_SESSION['captcha_code'] ) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0) {  
			// captcha verification is incorrect
			$msg = "验证码不正确";
		} else {
			// captcha verification is correct
			if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
				if($_POST['password'] == $_POST['confirm_password']) {
					if($user->signUp($_POST['username'], md5($_POST['password']))) {
						// user sign up success
						header("Location:index.php");
						return;
					} else {
						// username alraedy exists
						$msg = "用户名已存在";
					}
				} else {
					// passwords do not match
					$msg = "两次密码不匹配";
				}
			} else {
				// one or more fields are empty
				$msg = "所有输入框不能为空";
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
			<form action="signUp.php" method="POST">
				<h1 class="h3 mb-3 fw-normal">注册</h1>
				
				<div class="form-floating mb-3">
					<input type="text" class="form-control" placeholder="用户名" name="username" required />
					<label for="floatingUsername">用户名</label>
				</div>

				<div class="form-floating mb-3">
					<input type="password" class="form-control" placeholder="密码" name="password" required />
					<label for="floatingPassword">密码</label>
				</div>

				<div class="form-floating mb-3">
					<input type="password" class="form-control" placeholder="确认密码" name="confirm_password" required />
					<label for="floatingPassword">确认密码</label>
				</div>

				<div class="text-center mb-3">
					<a href='javascript: refreshCaptcha();'><img src="captcha.php?rand=<?php echo rand(); ?>" id="captchaimg" class="rounded"></a>
				</div>

				<div class="form-floating mb-3">
					<input type="text" class="form-control" placeholder="验证码" name="captcha_code" required />
					<label for="floatingInput">验证码</label>
				</div>
				
				<button class="btn btn-primary w-100 py-2" type="submit" name="btnSignUp">注册</button>
			</form>
		</div>
	</div>
</div>
