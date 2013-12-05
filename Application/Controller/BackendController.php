<?php

namespace Application\Controller;

use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Controller;
use Nameless\Modules\Auto\AccessDeniedException;

/**
 * Base BackendController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class BackendController extends Controller
{
	/**
	 * @return array
	 */
	protected function getStyles()
	{
		return array
		(
			FILE_PATH_URL . 'css/reset.css',
			FILE_PATH_URL . 'css/typographic.css',
			FILE_PATH_URL . 'lib/jquery-ui/1.10.3/themes/base/jquery-ui.css',
			FILE_PATH_URL . 'lib/jcrop/0.9.12/css/jcrop.css',
			FILE_PATH_URL . 'lib/chosen/1.0.0/chosen.css',
		);
	}

	/**
	 * @return array
	 */
	protected function getScripts()
	{
		return array
		(
			FILE_PATH_URL . 'lib/jquery/1.10.2/jquery.js',
			FILE_PATH_URL . 'lib/jquery-ui/1.10.3/ui/jquery-ui.js',
			FILE_PATH_URL . 'lib/jcrop/0.9.12/js/jcrop.js',
			FILE_PATH_URL . 'lib/chosen/1.0.0/chosen.js',
			FILE_PATH_URL . 'js/backend.js',
		);
	}

	/**
	 * @throws \Nameless\Modules\Auto\AccessDeniedException
	 */
	public function before()
	{

		$access = $this->container['auto.user']->getAccessByRoute($this->getAttributes('_route'));

		if (!$access)
		{
			throw new AccessDeniedException('Access Denied!');
		}
	}

	/**
	 * @param string $form_name
	 *
	 * @return Response
	 */
	protected function getValidation ($form_name)
	{
		if ($msg = $this->container['validation.validator']->validate($form_name))
		{
			$response = array('status' => 'error', 'msg' => $msg);
		}
		else
		{
			$response = array('status' => 'success');
		}

		return new Response(json_encode($response), 200, array('Content-Type' => 'application/json'));
	}
}