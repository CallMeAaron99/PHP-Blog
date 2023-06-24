<?php 
	session_start();

	include_once('header.php');
	include_once('post.php');

	$post = new Post($db);
?>

<script type="text/javascript">
    function showDeletePostModal(deletePostId, deletePostTitle){
		document.getElementById("deletePostTitle").innerText = deletePostTitle;
        document.getElementById("deletePostBtn").href = "deletePost.php?id=" + deletePostId + "&search=" + encodeURIComponent(window.location.search);
    }
</script>

<div class="container">
	<div class="row">
		<?php foreach($post->findPostById($_GET['id']) as $postItem){ ?>
			<div class="col-md-4">
				<h2 class="display-5 text-body-emphasis mb-1"><?php echo $postItem['title'] ?></h2>
			</div>

			<div class="col-md-4 offset-md-4">
				<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $postItem['user_id']) { ?>
					<a href="editPost.php?id=<?php echo $postItem['id']; ?>" class="btn btn-primary me-2">编辑</a>
					<button type="button" name="btnDelete" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletePostModal" 
					onclick="showDeletePostModal(<?php echo $postItem['id']; ?>, '<?php echo $postItem['title']; ?>')">删除</button>
				<?php } ?>
			</div>
			<p class="blog-post-meta"><?php echo date('Y-m-d H:i:s',strtotime($postItem['created_at'])); ?> by <?php echo $postItem['username']; ?></p>
			<?php if(!empty($postItem['image'])) { ?>
				<p><img src="images/<?php echo $postItem['image']; ?>" class="d-block mx-auto img-fluid object-fit-scale" alt="image" loading="lazy" style="width: 700px; height: 500px;"></p>
			<?php } ?>
			<p><?php echo $postItem['content']; ?></p>
		<?php } ?>
	</div>
</div>

<div class="modal fade" id="deletePostModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">删除文章</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-truncate">确定要删除<span id="deletePostTitle"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
        <a id="deletePostBtn" class="btn btn-danger">删除</a>
      </div>
    </div>
  </div>
</div>
