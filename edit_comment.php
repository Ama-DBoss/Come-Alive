<?php
include 'db.php';
$id = $_POST['comment_id'];
$text = trim($_POST['new_text']);
$ip = $_SERVER['REMOTE_ADDR'];
$conn->query("UPDATE comments SET comment_text='$text' WHERE id=$id AND user_ip='$ip'");
echo htmlspecialchars($text);