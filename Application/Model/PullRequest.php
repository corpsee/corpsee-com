<?php

namespace Application\Model;

use Nameless\Modules\Database\Model;
use Github\Client;
use Github\ResultPager;

/**
 * PullRequests model class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class PullRequest extends Model
{
	/**
	 * @param string  $repository
	 * @param integer $pull_request_id
	 *
	 * @return bool
	 */
	public function isIssetPullRequest ($repository, $pull_request_id)
	{
		$row = $this->database->selectOne("SELECT COUNT(*) AS count FROM `tbl_pull_requests` WHERE repository = ? AND pull_request_id = ?", array($repository, $pull_request_id));
		if ($row && (boolean)$row['count'])
		{
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * @param integer|NULL $limit
	 *
	 * @return array|false
	 */
	public function selectPullRequests ($limit = NULL)
	{
		if (is_integer($limit))
		{
			return $this->database->selectMany("SELECT * FROM `tbl_pull_requests` ORDER BY id DESC LIMIT ?", array($limit));
		}
		return $this->database->selectMany("SELECT * FROM `tbl_pull_requests` ORDER BY id DESC");
	}

	/**
	 * @param string  $repository
	 * @param integer $pull_request_id
	 * @param string  $data
	 *
	 * @return int
	 */
	public function insertPullRequest ($repository, $pull_request_id, $data)
	{
		return $this->database->execute("INSERT INTO `tbl_pull_requests` (`repository`, `pull_request_id`, `data`) VALUES (?, ?, ?)", array($repository, $pull_request_id, $data));
	}
}