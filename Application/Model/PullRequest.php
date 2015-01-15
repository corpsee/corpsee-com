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
     * @param integer|null $year
     *
     * @return array|false
     */
    public function selectPullRequests($limit = null, $year = null)
    {
        $sql    = 'SELECT * FROM "pull_requests"';
        $params = [];

        if (is_integer($year)) {
            $sql      .= ' WHERE date_part(\'year\', "create_date") = ?';
            $params[] = $year;
        }

        $sql .= ' ORDER BY "create_date" DESC';

        if (is_integer($limit)) {
            $sql      .= ' LIMIT ?';
            $params[] = $limit;
        }
        //var_dump($sql); exit;

        return $this->database->selectMany($sql, $params);
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