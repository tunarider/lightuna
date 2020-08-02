<?php
$baseUrl = $config['site']['baseUrl'];
$boardUids = array_filter(array_keys($config['boards']), function ($boardUid) {
    return ($boardUid !== '__default__');
});
$list = '';
foreach ($boardUids as $boardUid) {
    $boardName = $config['boards'][$boardUid]['name'];
    $list .= <<<HTML
<li><a href="$baseUrl/index.php/$boardUid">$boardName</a></li>
HTML;
}

$traceList = '';
if ($_SERVER['SCRIPT_NAME'] === "{$baseUrl}/trace.php") {
    $maxResponseView = $board['maxResponseView'];
    $prevResponseEnd = $responseStart - 1;
    $prevResponseStart = max(1, $prevResponseEnd - $maxResponseView);
    $nextResponseStart = $responseEnd + 1;
    $nextResponseEnd = $nextResponseStart + $maxResponseView;
    if ($thread->getSize() <= $nextResponseStart) {
        $nextResponseStart = 'recent';
        $nextResponseEnd = '';
    }
    $traceList = <<<HTML
<li><a href="$baseUrl/index.php/{$board['uid']}">게시판으로</a></li>
<li><a href="$baseUrl/trace.php/{$board['uid']}/$threadUid">전부 보기</a></li>
<li><a href="$baseUrl/trace.php/{$board['uid']}/$threadUid/recent">최근 $maxResponseView 보기</a></li>
<li><a href="$baseUrl/trace.php/{$board['uid']}/$threadUid/{$prevResponseStart}/{$prevResponseEnd}">이전 $maxResponseView</a></li>
<li><a href="$baseUrl/trace.php/{$board['uid']}/$threadUid/{$nextResponseStart}/{$nextResponseEnd}">다음 $maxResponseView</a></li>
HTML;
}
?>
<nav>
    <ul>
        <li><a href="#top">맨 위</a></li>
        <li><a href="#bottom">맨 아래</a></li>
        <?= $traceList ?>
        <?= $list ?>
        <li><a href="https://wiki.tunaground.net" target="_blank">참치백과</a></li>
    </ul>
</nav>
