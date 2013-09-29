<?php

namespace Application\Controller;

use Nameless\Core\Controller;
use Application\Model\Page;
use Symfony\Component\HttpFoundation\Response;

/**
 * ToolController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ToolController extends Controller
{
	/**
	 * @return array
	 */
	protected function getScripts()
	{
		return array();
	}

	/**
	 * @return array
	 */
	protected function getStyles()
	{
		return array
		(
			FILE_PATH_URL . 'libs/normalize-css/normalize.css',
			FILE_PATH_URL . 'styles/frontend.less',
		);
	}

	public function password ()
	{

	}
}