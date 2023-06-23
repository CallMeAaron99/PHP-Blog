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
            <a href="index.php" class="nav-link link-body-emphasis <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active'; ?>">首页</a>
          </li>
          <li class="nav-item">
            <a href="addPost.php" class="nav-link link-body-emphasis <?php if(basename($_SERVER['PHP_SELF']) == 'addPost.php') echo 'active'; ?>">发布文章</a>
          </li>
        </ul>

          <?php if(!empty($_SESSION['username'])){ ?>
            <div class="dropdown text-end">
              <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo $_SESSION['username']; ?>
              </a>
              <ul class="dropdown-menu text-small">
                <!-- <li><a class="dropdown-item" href="#">Edit profile</a></li>
                <li><hr class="dropdown-divider"></li> -->
                <li><a class="dropdown-item" href="signOut.php">注销</a></li>
              </ul>
            </div>
          <?php }else{  ?>
            <div class="text-end">
              <a href="login.php" class="btn btn-light text-dark me-2">登录</a>
              <a href="signUp.php" class="btn btn-primary">注册</a>
            </div>
          <?php  } ?>
          
      </header>
    </div>
  </body>
</html>