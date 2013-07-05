<?php

namespace Application\Model;

use Nameless\Modules\Database\Model;

class Page extends Model
{
	public function getPage ($name)
	{
		return $this->database->selectOne("SELECT * FROM `tbl_pages` WHERE `name` = ?", array($name));
	}
}