<?php
	include('session.php');
	include('header.php');
	include('post.php');
	
	$post = new Post($db);
?>

<?php
    if(isset($_POST['btnPost'])){
		$title = strip_tags(trim($_POST['title']));
		$content = trim($_POST['content']);
    	if(!empty($_POST['title']) && !empty($_POST['content'])){
			$_POST['tags'] = trim($_POST['tags']);
			$userId = $_SESSION['user_id'];
    		if($post->insertPost($title, $content, $userId)){
				// insert post success
    			header("Location:index.php");
    		}else{
				// invalid image type
				echo <<<END
                    <div class="alert alert-danger alert-dismissible container">
                        图片格式不正确
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                END;
			}
    	}else{
			// title or content field is empty
    		echo <<<END
				<div class="alert alert-danger alert-dismissible container">
					标题和内容不能为空
					<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				</div>
			END;
    	}
    }
?>

<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<form method="POST" enctype="multipart/form-data">
				<div class="card">
					<div class="card-header">发布文章</div>

					<div class="card-body">
						<div class="form-group mb-3">
							<label for="title">标题:</label>
							<input type="text" name="title" class="form-control">
						</div>

						<div class="form-group mb-3">
							<label for="content">内容:</label>
							<textarea name="content" class="form-control"></textarea>
						</div>

						<div class="form-group mb-3">
							<label for="image">图片:</label>
							<input type="file" name="image" class="form-control" accept=".png,.jpeg,.jpg">
							<div class="form-text">图片格式: jpg, jpeg 和 png </div>
						</div>

						<div class="form-group mb-3">
							<label for="tags">标签:</label>
							<textarea name="tags" class="form-control"></textarea>
							<div class="form-text">标签之间用空格分隔, 例如: python java</div>
						</div>

						<button type="submit" name="btnPost" class="btn btn-primary">发布</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
