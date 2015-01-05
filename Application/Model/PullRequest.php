<?php

namespace Application\Model;

use Nameless\Modules\Database\Model;

/**
 * PullRequests model class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class PullRequest extends Model
{
    /**
     * @param string $repository
     * @param integer $number
     *
     * @return boolean
     */
    public function isIssetPullRequest($repository, $number)
    {
        $row = $this->database->selectOne(
            'SELECT COUNT(*) AS "count" FROM "pull_requests" WHERE "repository" = ? AND "number" = ?',
            [$repository, $number]
        );
        if ($row && (boolean)$row['count']) {
            return true;
        }
        return false;
    }

    /**
     * @param integer|null $limit
     *
     * @return array|false
     */
    public function selectPullRequests($limit = null)
    {
        if (is_integer($limit)) {
            return $this->database->selectMany(
                'SELECT * FROM "pull_requests" ORDER BY "create_date" DESC LIMIT ?',
                [$limit]
            );
        }
        return $this->database->selectMany('SELECT * FROM "pull_requests" ORDER BY "create_date" DESC');
    }

    /**
     * @param string $repository
     * @param integer $number
     * @param string $body
     * @param string $title
     * @param string $status
     * @param integer $commits
     * @param integer $additions
     * @param integer $deletions
     * @param integer $files
     * @param integer $create_date
     *
     * @return integer
     */
    public function insertPullRequest(
        $repository,
        $number,
        $body,
        $title,
        $status,
        $commits,
        $additions,
        $deletions,
        $files,
        $create_date
    ) {
        return $this->database->execute('
            INSERT INTO "pull_requests"
                ("repository", "number", "body", "title", "status", "commits", "additions", "deletions", "files", "create_date")
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ', [$repository, $number, $body, $title, $status, $commits, $additions, $deletions, $files, $create_date]
        );
    }

    /**
     * @param string $repository
     * @param integer $number
     * @param string $body
     * @param string $title
     * @param string $status
     * @param integer $commits
     * @param integer $additions
     * @param integer $deletions
     * @param integer $files
     * @param integer $create_date
     *
     * @return integer
     */
    public function updatePullRequest(
        $repository,
        $number,
        $body,
        $title,
        $status,
        $commits,
        $additions,
        $deletions,
        $files,
        $create_date
    ) {
        return $this->database->execute('
            UPDATE "pull_requests"
                SET "body" = ?, "title" = ?, "status" = ?, "commits" = ?, "additions" = ?, "deletions" = ?, "files" = ?, "create_date" = ?
                WHERE "repository" = ? AND "number" = ?
                ', [$body, $title, $status, $commits, $additions, $deletions, $files, $create_date, $repository, $number]
        );
    }
}