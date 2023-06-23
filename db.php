<?php
    $db = mysqli_connect("localhost","root","","blog");
    if(mysqli_connect_error()){
        // failed to connect to mysql
        echo "failed to connect".mysqli_connect_error();
    }
?>