<?php
    include_once('User.php');
    include_once('header.php');
    
    $user = new User($db);

    function errorMessage($msg){
        echo <<<END
			<div class="alert alert-danger alert-dismissible container">
				$msg
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		END;
    }

    if(isset($_POST['updateUserBtn'])) {
        if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
            // one or more fields are empty
            errorMessage("所有输入框不能为空");
            return;
        }
        
        if($_POST['password'] != $_POST['confirm_password']) {
            // passwords do not match
            errorMessage("两次密码不匹配");
            return;
        }

        if(!$user->checkPassword(md5($_POST['old_password']))){
            // old password incorrect
            errorMessage("原密码不正确");
            return;
        }

        if(!$user->updatePassword(md5($_POST['password']))){
            // old password same with new password
            errorMessage("原密码和新密码一致");
            return;
        }

        // update success
        // header("Location:index.php");
        return;
    }
?>