<?php

namespace Application\Model;

use Nameless\Modules\Database\Model;

/**
 * Page model class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Page extends Model
{
	/**
	 * @param string $name
	 *
	 * @return array
	 */
	public function getPage ($name)
	{
		return $this->database->selectOne("SELECT * FROM `tbl_pages` WHERE `name` = ?", array($name));
	}
}