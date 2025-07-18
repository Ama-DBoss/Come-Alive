<?php
include 'db.php';
$id = $_POST['id'];
$emoji = $_POST['emoji'];
$ip = $_SERVER['REMOTE_ADDR'];

$conn->query("INSERT INTO likes (item_id, user_ip, emoji)
              VALUES ('$id', '$ip', '$emoji')
              ON DUPLICATE KEY UPDATE emoji='$emoji'");

$res = $conn->query("SELECT emoji, COUNT(*) AS cnt FROM likes WHERE item_id='$id' GROUP BY emoji");
$counts = [];
while ($r = $res->fetch_assoc()) $counts[$r['emoji']] = $r['cnt'];
echo json_encode($counts);