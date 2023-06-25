<!doctype html>
<html lang="en" data-bs-theme="dark">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script> -->
    <link href="./static/bootstrap-5.3.0-dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="./static/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>
  </head>
  <body>
    <div class="container">
      <header class="d-flex justify-content-center py-3">
        <ul class="nav nav-pills me-lg-auto mb-2 justify-content-center mb-md-0">
          <li class="nav-item">
            <a href="index.php"
              class="nav-link link-body-emphasis <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active'; ?>">首页</a>
          </li>
          <li class="nav-item">
            <a href="addPost.php"
              class="nav-link link-body-emphasis <?php if(basename($_SERVER['PHP_SELF']) == 'addPost.php') echo 'active'; ?>">发布文章</a>
          </li>
        </ul>
        <?php if(!empty($_SESSION['username'])){ ?>
        <div class="dropdown text-end">
          <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false">
            <?php echo $_SESSION['username']; ?>
          </a>
          <ul class="dropdown-menu text-small">
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editUsernameModal">修改密码</a></li>
            <li>
              <hr class="dropdown-divider" />
            </li>
            <li><a class="dropdown-item" href="logout.php">注销</a></li>
          </ul>
        </div>
        <?php }else{ ?>
        <div class="text-end">
          <a href="login.php" class="btn btn-light text-dark me-2">登录</a>
          <a href="signup.php" class="btn btn-primary">注册</a>
        </div>
        <?php } ?>
      </header>
      <div class="modal fade" id="editUsernameModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">修改用户名</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="editUser.php" method="POST">
              <div class="modal-body">
                  <div class="mb-3">
                    <label for="old-password" class="col-form-label">原密码:</label>
                    <input type="password" class="form-control" placeholder="原密码" name="old_password" required />
                  </div>
                  <div class="mb-3">
                    <label for="password" class="col-form-label">新密码:</label>
                    <input type="password" class="form-control" placeholder="新密码" name="password" required />
                  </div>
                  <div class="mb-3">
                    <label for="confirm-password" class="col-form-label">确认新密码:</label>
                    <input type="password" class="form-control" placeholder="确认新密码" name="confirm_password" required />
                  </div>
              </div>
              <div class="modal-footer">
                <button type="submit" name="updateUserBtn" class="btn btn-primary me-2">保存</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
