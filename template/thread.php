<div class="thread"
     id="thread_<?= $thread->getThreadUid() ?>">
    <?php
    require(__DIR__ . '/thread_header.php');
    ?>
    <div class="thread_body">
        <?php
        foreach ($thread->getResponses() as $response) {
            require(__DIR__ . '/response.php');
        }
        ?>
    </div>
    <?php if (!$thread->getEnd()) { ?>
    <?php     require(__DIR__ . '/create_response.php'); ?>
    <?php } else { ?>
    <div class="dead_sign">끝.</div>
    <?php } ?>
</div>
