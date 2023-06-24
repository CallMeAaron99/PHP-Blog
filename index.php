<?php
    session_start();

    if (isset($_COOKIE['remember_me'])) {
        list($user_id, $username) = explode(':', $_COOKIE['remember_me']);
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
    }

    include('header.php');
    include('post.php');
    include('Tag.php');
    
    $post = new Post($db);
    $tag = new Tag($db);
?>

<script type="text/javascript">
    function showDeletePostModal(deletePostId, deletePostTitle){
        document.getElementById("deletePostTitle").innerText = deletePostTitle;
        document.getElementById("deletePostBtn").href = "deletePost.php?id=" + deletePostId + "&search=" + encodeURIComponent(window.location.search);
    }
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2">
            <div class="d-flex flex-column flex-shrink-0 p-3" style="width: 280px;">
                <p class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-body-emphasis"><span class="fs-4">标签:</span></p>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <?php
                        $tagList = $tag->findAllTag();
                        if($tagList) {
                            // has tag
                    ?>
                    <?php foreach($tagList as $tagItem) { ?>
                        <li class="nav-item">
                            <a href="index.php?tag=<?php echo $tagItem['id'] ?>&title=<?php echo isset($_GET['title']) ? $_GET['title'] : '' ?>" 
                            class="nav-link link-body-emphasis <?php if(isset($_GET['tag']) && $_GET['tag'] == $tagItem['id']) echo 'active'; ?>"><?php echo $tagItem['name'] ?></a>
                        </li>
                    <?php } ?>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="row m-3 justify-content-center">
                <div class="col-md-4">
                    <form method="GET">
                        <div class="input-group rounded">
                            <input type="search" class="form-control" placeholder="搜索" name="title" />
                            <button type="submit" class="btn btn-outline-primary">搜索</button>
                            <?php if(isset($_GET['tag']) && !empty($_GET['tag'])) { ?>
                                <input type="hidden" name="tag" value="<?php echo $_GET['tag'] ?>" />
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php
                    $postList = $post->findAllPost();
                    if($postList) {
                        // has post
                ?>
                <?php foreach($postList as $postItem) { ?>
                    <div class="col">
                        <div class="card" style="height: 20rem">
                            <div class="row g-0 position-relative">
                                <div class="col-md-4">
                                    <?php if(!empty($postItem['image'])) { ?>
                                        <img class="d-block mx-auto img-fluid object-fit-scale" src="images/<?php echo $postItem['image']; ?>" alt="thumbnail" style="width: 200px; height: 250px;">
                                    <?php } ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h3 class="card-title text-truncate">
                                            <a href="viewPost.php?id=<?php echo $postItem['id'];?>" class="link-underline link-underline-opacity-0 link-underline-opacity-75-hover stretched-link">
                                                <?php echo $postItem['title']; ?>
                                            </a>
                                        </h3>
                                        <p class="card-text text-truncate">
                                            <?php echo $postItem['content']; ?>
                                        </p>
                                        <p class="card-text"><small class="text-body-secondary">作者: <?php echo $postItem['username']; ?></small></p>
                                        <p class="card-text"><small class="text-body-secondary">发布于: <?php echo date('Y-m-d H:i:s',strtotime($postItem['created_at'])); ?></small></p>
                                        <p class="card-text"><small class="text-body-secondary">最近更新: <?php echo date('Y-m-d H:i:s',strtotime($postItem['updated_at'])); ?></small></p>
                                        <p class="card-text text-truncate"><small class="text-body-secondary">#<?php echo str_replace(' ', ' #', $postItem['tags']); ?></small></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-4">
                                    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $postItem['user_id']) { ?>
                                        <a href="editPost.php?id=<?php echo $postItem['id']; ?>&search=<?php echo urlencode($_SERVER['QUERY_STRING']); ?>" class="btn btn-primary me-2">编辑</a>
                                        <button type="button" name="btnDelete" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletePostModal" 
                                        onclick="showDeletePostModal(<?php echo $postItem['id']; ?>, '<?php echo $postItem['title']; ?>')">删除</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php } ?>
            </div>

            <?php
                $total = $post->getPostCount();
                $pageSize = $GLOBALS['pageSize'];
                $pageCount = ceil($total / $pageSize);
                $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
                $previousPage = max(1, $currentPage - 1);
                $nextPage = min($pageCount, $currentPage + 1);
            ?>

            <?php if($total != 0) {?>
            <div class="row mt-2">
                <div class="col">
                    <nav>
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php if($currentPage == 1) echo 'disabled'; ?>">
                                <a class="page-link" href="index.php?page=<?php echo $previousPage ?>">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                            </li>
                            <?php for($i = 1; $i <= $pageCount; $i++) { ?>
                                <li class="page-item <?php if($currentPage == $i) echo 'active'; ?>">
                                    <a class="page-link" href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php } ?>
                            <li class="page-item <?php if($currentPage == $pageCount) echo 'disabled'; ?>">
                                <a class="page-link" href="index.php?page=<?php echo $nextPage ?>">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <?php } ?>
        </div>
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