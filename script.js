let page = 1, loading = false;

function loadContent() {
  $.get("fetch.php", { page: page }, res => {
    $("#content-container").append(res);
    page++;
    loading = false;
  });
}

function react(id, emoji) {
  $.post("like.php", { id, emoji }, res => {
    const counts = JSON.parse(res);
    let html = '';
    for (let e in counts) html += `<span>${e} ${counts[e]}</span> `;
    $(`#emoji-counts-${id}`).html(html);
  });
}

function addComment(id) {
  const text = $(`#comment-input-${id}`).val().trim();
  if (!text) return;
  $.post("comment.php", { id, comment: text }, res => {
    $(`#comments-${id}`).prepend(`<div id="comment-${res.id}" class="comment"><span>${res.text}</span> ${res.own}</div>`);
    $(`#comment-input-${id}`).val('');
  }, "json");
}

function deleteComment(commentId) {
  $.post("delete_comment.php", { comment_id: commentId }, res => {
    if (res === "deleted") $(`#comment-${commentId}`).remove();
  });
}

function editComment(commentId) {
  const newText = prompt("Edit comment:");
  if (newText) {
    $.post("edit_comment.php", { comment_id: commentId, new_text: newText }, res => {
      $(`#comment-${commentId} span`).text(res);
    });
  }
}

$(window).scroll(() => {
  if (!loading && $(window).scrollTop() + $(window).height() > $(document).height() - 200) {
    loading = true;
    loadContent();
  }
});

$(document).on("click", ".emoji-btn", function(){
  react($(this).data("id"), $(this).data("emoji"));
});
$(document).on("click", ".comment-btn", function(){
  addComment($(this).data("id"));
});
$(document).on("click", ".delete-comment-btn", function(){
  deleteComment($(this).data("cid"));
});
$(document).on("click", ".edit-comment-btn", function(){
  editComment($(this).data("cid"));
});

$(document).ready(loadContent);