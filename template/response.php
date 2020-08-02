<?php
/**
 * @var \Lightuna\Object\Response $response
 */
if (!isset($response)) {
    return;
}

$date = $response->getCreateDate()->format('Y-m-d');
$week = $board['customWeek'][$response->getCreateDate()->format('w')];
$time = $response->getCreateDate()->format('H:i:s');
$createDate = "{$date} ({$week}) $time";

if ($response->getAttachment() !== '') {
    $imagePath = $config['site']['baseUrl']
        . $config['site']['imageUploadPrefix']
        . '/image/'
        . $response->getAttachment();
    $thumbPath = $config['site']['baseUrl']
        . $config['site']['imageUploadPrefix']
        . '/thumb/'
        . $response->getAttachment();
    $image = <<<HTML
<a href="$imagePath">
    <img src="$thumbPath"/>
</a>
HTML;
} else {
    $image = '';
}

if ($response->getYoutube() !== '') {
    $youtubeLink = $response->getYoutube();
    preg_match('/https:\/\/www\.youtube.com\/watch\?v=(.+)/', $youtubeLink, $matches);
    if (isset($matches[1])) {
        $youtubeEmbed = <<<HTML
<iframe
    class="youtube"
    src="https://www.youtube.com/embed/$matches[1]"
    frameborder="0"
    allowfullscreen>
</iframe><br/>
HTML;
    }
} else {
    $youtubeEmbed = '';
}

if ($response->getSequence() > 0) {
    $baseUrl = $config['site']['baseUrl'];
    $threadUid = $response->getThreadUid();
    $responseUid = $response->getResponseUid();
    $maskButtonHtml = <<<HTML
<button class="button_default response_mask" onclick="maskResponse('$baseUrl', $threadUid, $responseUid)">
Mask
</button>
HTML;
} else {
    $maskButtonHtml = '';
}

$responseContent = $response->getContent();
if (isset($shrinkResponse) && $shrinkResponse === true) {
    $lineCount = preg_match_all('/<br ?\/?>/', $responseContent);
    if ($lineCount > $board['maxResponseLineView']) {
        $responseContents = preg_split('/<br ?\/?>/', $responseContent, $board['maxResponseLineView']);
        array_pop($responseContents);
        $responseContents[] = <<<HTML
<a href="{$config['site']['baseUrl']}/trace.php/{$board['uid']}/{$response->getThreadUid()}/{$response->getSequence()}">
더 보기
</a>
HTML;
        $responseContent = join('<br/>', $responseContents);
    }
}
?>
<div class="response"
     id="response_<?= $response->getThreadUid() ?>_<?= $response->getSequence() ?>"
     data-board-uid="<?= $board['uid'] ?>"
     data-thread-uid="<?= $response->getThreadUid() ?>"
     data-response-sequence="<?= $response->getSequence() ?>">
    <p class="response_info">
        <span class="response_sequence"><?= $response->getSequence() ?></span>
        <span class="response_owner"><?= $response->getUserName() ?></span>
        <span class="response_owner_id">
            (<a onclick="banUserId('<?= $baseUrl ?>', <?= $response->getThreadUid() ?>, <?= $response->getResponseUid() ?>)"><?= $response->getUserId() ?></a>)
        </span>
        <span class="response_mask_button">
        <?= $maskButtonHtml ?>
        </span>
    </p>
    <p class="response_create_date"><?= $createDate ?></p>
    <?= $image ?>
    <div class="content">
        <?= $youtubeEmbed ?>
        <?= $responseContent ?>
    </div>
</div>
