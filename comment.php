<?php
include 'db.php';
$id = $_POST['id'];
$comment = trim($_POST['comment']);
$ip = $_SERVER['REMOTE_ADDR'];
$stmt = $conn->prepare("INSERT INTO comments (item_id, comment_text, user_ip) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $id, $comment, $ip);
$stmt->execute();
echo json_encode(["id"=>$stmt->insert_id, "text"=>$comment, "own"=>"<button class='edit-comment-btn' data-cid='{$stmt->insert_id}'>✏️</button><button class='delete-comment-btn' data-cid='{$stmt->insert_id}'>🗑️</button>"]);