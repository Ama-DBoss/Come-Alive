<?php
include 'db.php';
$page = max(1, intval($_GET['page'] ?? 1));
$limit=1; $offset=($page-1)*$limit;
$items=[];

foreach (["quotes" => "quote_text", "jokes" => "joke_text", "poems" => "poem_text"] as $tbl=>$col){
  $res = $conn->query("SELECT id, $col AS text, '$tbl' AS type FROM $tbl");
  while ($r = $res->fetch_assoc()) $items[]=$r;
}

shuffle($items);
$items = array_slice($items, $offset, $limit);
$ip = $_SERVER['REMOTE_ADDR'];

foreach($items as $it):
  $id = "{$it['type']}_{$it['id']}";
  $likeRes = $conn->query("SELECT emoji, COUNT(*) AS cnt FROM likes WHERE item_id='$id' GROUP BY emoji");
  $counts=[];
  while ($r = $likeRes->fetch_assoc()) $counts[$r['emoji']]=$r['cnt'];
  $comRes = $conn->query("SELECT id, comment_text, user_ip FROM comments WHERE item_id='$id' ORDER BY id DESC LIMIT 3");
?>
 <div class="item">
  <strong><?=ucfirst($it['type'])?>:</strong>
  <p><?=htmlspecialchars($it['text'])?></p>
  <?php foreach(['👍','😂','❤️','😮'] as $e): ?>
    <span class="emoji-btn" data-id="<?=$id?>" data-emoji="<?=$e?>"><?=$e?></span>
  <?php endforeach ?>
  <div id="emoji-counts-<?=$id?>"><?=implode(' ',array_map(fn($e,$c)=>"$e $c",array_keys($counts),array_values($counts)))?></div>
  <hr><br>
  <input style="align:left;" type="text" id="comment-input-<?=$id?>" placeholder="Add comment">
  <button class="comment-btn" data-id="<?=$id?>">Comment</button><br>
  <br>
  <div style="align:left; background-color: black; color: white;" id="comments-<?=$id?>"><br>
    <?php while($c = $comRes->fetch_assoc()):
      $own = ($c['user_ip']===$ip); ?>
      <div style="align:left; background-color: black; color: white;" id="comment-<?=$c['id']?>" class="comment"><span><?=htmlspecialchars($c['comment_text'])?></span><?=$own?></div>
    <?php endwhile; ?>
  </div>
 </div>
<?php endforeach; ?>
