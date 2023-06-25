<?php
    include_once('session.php');
    include_once('Post.php');

    $post = new Post($db);

    if(isset($_GET['id']) && !empty($_GET['id'])) {
        $postId = $_GET['id'];
        if($post->deletePostById($postId)){
            // delete post success
            if(isset($_GET['search'])){
                // from index.php
                header("Location:index.php" . $_GET['search']);
            } else {
                // from view.php
                header("Location:index.php");
            }
        }
    }
?>