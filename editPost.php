<?php
	include_once('session.php');
	include_once('Post.php');
	include_once('header.php');
	
	$post = new Post($db);

	if(isset($_POST['btnUpdate'])){
		$title = strip_tags(trim($_POST['title']));
		$content = trim($_POST['content']);
    	if(!empty($_POST['title']) && !empty($_POST['content'])){
			$postId = $_POST['id'];
			$_POST['tags'] = trim($_POST['tags']);
    		if($post->updatePost($postId, $title, $content)){
				// update post success
				if(isset($_POST['search'])){
					header("Location:index.php?" . $_POST['search']);
				} else {
					header("Location:view.php?id=" . $_POST['id']);
				}
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
					<div class="card-header">编辑文章</div>
					<div class="card-body">
					<?php foreach($post->findPostById($_GET['id']) as $postItem){ ?>
						<div class="form-group mb-3">
							<label for="title">标题:</label>
							<input type="text" name="title" class="form-control" value="<?php echo $postItem['title'] ?>" required />
						</div>

						<div class="form-group mb-3">
							<label for="content">内容:</label>
							<textarea name="content" class="form-control"><?php echo $postItem['content'] ?></textarea>
						</div>

						<div class="form-group mb-3">
							<label for="image">图片:</label>
							<input type="file" name="image" class="form-control" accept=".png,.jpeg,.jpg" />
							<div class="form-text">图片格式: jpg, jpeg 和 png </div>
						</div>

						<div class="form-group mb-3">
							<label for="tags">标签:</label>
							<textarea name="tags" class="form-control"><?php echo $postItem['tags'] ?></textarea>
							<div class="form-text">标签之间用空格分隔, 例如: python java</div>
						</div>

						<button type="submit" name="btnUpdate" class="btn btn-primary">保存</button>
						<input type="hidden" name="id" value="<?php echo $postItem['id'] ?>" />
						<?php if(isset($_GET['search'])) { ?>
						<input type="hidden" name="search" value="<?php echo $_GET['search'] ?>" />
						<?php } ?>
					<?php } ?>
					</div>
				</div>
			</form>

		</div>
	</div>

</div>
