<?php

use Lightuna\Database\DataSource;
use Lightuna\Database\ResponseDao;
use Lightuna\Database\ThreadDao;
use Lightuna\Object\Board;
use Lightuna\Service\AttachmentService;
use Lightuna\Service\PostService;
use Lightuna\Util\NetworkUtil;
use Lightuna\Util\ThumbUtil;
use Lightuna\Util\Redirection;
use Lightuna\Util\ContextParser;
use Lightuna\Log\Logger;
use Lightuna\Exception\DataAccessException;
use Lightuna\Exception\InvalidUserInputException;
use Lightuna\Util\ExceptionHandler;

const FRONT_PAGE = true;

require('./require.php');

$contextParser = new ContextParser();
$logger = new Logger($config['site']['logFilePath'], $contextParser);
$exceptionHandler = new ExceptionHandler($config, $logger);
$boardUid = $_POST['board_uid'];
$board = new Board($config, $boardUid);
$dataSource = new DataSource(
    $config['database']['host'],
    $config['database']['port'],
    $config['database']['user'],
    $config['database']['password'],
    $config['database']['schema'],
    $config['database']['options'],
    $logger
);
$netUtil = new NetworkUtil();

$threadDao = new ThreadDao($dataSource, $logger);
$responseDao = new ResponseDao($dataSource, $logger);
$postService = new PostService($dataSource, $threadDao, $responseDao, $board);
$attachmentService = new AttachmentService($config, $board, new ThumbUtil());

$type = $_POST['type'];
$userName = htmlspecialchars($_POST['name']);
if ($userName === '') {
    $userName = $board['userName'];
}
$console = explode('.', $_POST['console']);
$content = str_replace(PHP_EOL, '<br/>', htmlspecialchars($_POST['content']));
$returnUrl = $_POST['return_url'];
$ip = $netUtil->getIP();
$currentDateTime = new DateTime();

try {
    if ($_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {
        $attachment = $attachmentService->upload($_FILES['attachment']);
    } else {
        $attachment = '';
    }

    if ($type === 'thread') {
        $title = $_POST['title'];
        $password = $_POST['password'];
        $postService->postThread(
            $userName,
            $console,
            $content,
            $attachment,
            $title,
            $password,
            $ip,
            $currentDateTime
        );
    } else {
        $threadUid = $_POST['thread_uid'];
        $postService->postResponse(
            $threadUid,
            $userName,
            $console,
            $content,
            $attachment,
            $ip,
            $currentDateTime
        );
    }
    Redirection::temporary($returnUrl);
} catch (PDOException $e) {
    $logger->error('post.php: Database exception: {msg}', ['msg' => $e->getMessage()]);
    $exceptionHandler->handle('/database', $e);
} catch (DataAccessException $e) {
    $logger->error('post.php: Data access exception: {msg}', ['msg' => $e->getMessage()]);
    $exceptionHandler->handle('/data-access', $e);
} catch (InvalidUserInputException $e) {
    // TODO: 재입력 요청 구현
    $logger->notice('post.php: Invalid user input exception: {msg}', ['msg' => $e->getMessage()]);
    echo $e->getMessage();
}
