<?php
if (!isset($content)) {
    $content = '';
}
?>
<fieldset class="post_form">
    <form action="<?= $config['site']['baseUrl'] ?>/post.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="type" value="response">
        <input type="hidden" name="board_uid" value="<?= $board['uid'] ?>" class="post_form_board_uid">
        <input type="hidden" name="thread_uid" value="<?= $thread->getThreadUid() ?>" class="post_form_thread_uid">
        <input type="hidden" name="return_url" value="<?= $returnUrl ?>">
        <input type="text"
               name="name"
               placeholder="나메(<?= $board['maxNameLength'] ?>자까지)"
               value=""
               class="post_form_default post_form_name"
               data-thread-uid="<?= $thread->getThreadUid() ?>">
        <input type="text"
               name="console"
               placeholder="콘솔"
               value=""
               class="post_form_default post_form_console"
               data-thread-uid="<?= $thread->getThreadUid() ?>">
        <textarea name="content"
                  placeholder="본문(<?= $board['maxContentLength'] ?>자까지)"
                  spellcheck="false"
                  required=""
                  class="post_form_content"><?= $content ?></textarea>
        <input type="text"
               name="youtube"
               placeholder="유튜브 링크하기(예시: https://www.youtube.com/watch?v=a1b2c3d4)"
               class="post_form_default">
        <input type="file" name="attachment" class="post_form_attachment">
        <div class="post_form_button_group">
            <button class="button_default post_form_submit">작성</button>
            <button class="button_default post_form_default post_form_test">테스트</button>
        </div>
    </form>
</fieldset>
