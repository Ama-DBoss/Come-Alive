<?php
include 'db.php';
$id = $_POST['comment_id'];
$ip = $_SERVER['REMOTE_ADDR'];
$conn->query("DELETE FROM comments WHERE id=$id AND user_ip='$ip'");
echo "deleted";