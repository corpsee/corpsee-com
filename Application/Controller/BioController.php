<?php

namespace Application\Controller;

use Nameless\Core\Controller;

/**
 * BioController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class BioController extends Controller
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
			FILE_PATH_URL . 'lib/normalize/1.1.2/normalize.css',
			FILE_PATH_URL . 'styles/frontend.less',
		);
	}

	public function index ()
	{

	}
}