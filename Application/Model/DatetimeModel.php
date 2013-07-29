<?php

namespace Application\Model;

use Nameless\Modules\Database\Database;
use Nameless\Modules\Database\Model;

/**
 * Gallery model class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class DatetimeModel extends Model
{
	/**
	 * @var \DateTimeZone
	 */
	protected $timezone;

	/**
	 * @param Database $database
	 * @param string   $timezone
	 */
	public function __construct(Database $database, $timezone = 'UTC')
	{
		$this->timezone = new \DateTimeZone($timezone);
		parent::__construct($database);
	}
}