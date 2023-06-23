<?php
    include('post.php');
    $post = new Post($db);

    if(isset($_GET['id']) && !empty($_GET['id'])) {
        $postId = $_GET['id'];
        if($post->deletePostById($postId)){
            // delete post success
            if(isset($_GET['search'])){
                // from indext.php
                header("location:index.php" . $_GET['search']);
            } else {
                // from view.php
                header("location:index.php");
            }
        }
    }
?>