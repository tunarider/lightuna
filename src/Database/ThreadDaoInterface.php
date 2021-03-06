<?php
namespace Lightuna\Database;

use Lightuna\Exception\DataAccessException;
use Lightuna\Object\Thread;

/**
 * Interface ThreadDaoInterface
 * @package Lightuna\Database
 */
interface ThreadDaoInterface
{
    /**
     * @param int $threadUid
     * @return Thread
     * @throws DataAccessException
     */
    public function getThreadByThreadUid(int $threadUid): Thread;
    
    /**
     * @param string $boardId
     * @param int $limit
     * @return Thread[]
     * @throws DataAccessException
     */
    public function getThreadListByBoardUid(string $boardId, int $limit): array;

    /**
     * @param int $threadUid
     * @return int
     * @throws DataAccessException
     */
    public function getThreadSize(int $threadUid): int;

    /**
     * @param Thread $thread
     * @throws DataAccessException
     */
    public function createThread(Thread $thread);
    
    /**
     * @param int $threadUid
     * @return int
     * @throws DataAccessException
     */
    public function getLastResponseSequence(int $threadUid): int;

    /**
     * @param int $threadUid
     * @param \DateTime $dateTime
     * @throws DataAccessException
     */
    public function setUpdateDate(int $threadUid, \DateTime $dateTime): void;

    /**
     * @return int
     * @throws DataAccessException
     */
    public function getNextThreadUid(): int;

    /**
     * @param int $threadUid
     * @param bool $end
     * @throws DataAccessException
     */
    public function setThreadEnd(int $threadUid, bool $end): void;

    /**
     * @param string $boardUid
     * @param string $keyword
     * @param int $start
     * @param int $limit
     * @return Thread[]
     * @throws DataAccessException
     */
    public function findByThreadTitle(string $boardUid, string $keyword, int $start, int $limit): array;

    /**
     * @param string $boardUid
     * @param string $keyword
     * @param int $start
     * @param int $limit
     * @return Thread[]
     * @throws DataAccessException
     */
    public function findByThreadOwner(string $boardUid, string $keyword, int $start, int $limit): array;
}
