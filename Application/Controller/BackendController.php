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
			FILE_PATH_URL . 'styles/reset.css',
			FILE_PATH_URL . 'styles/typographic.css',
			FILE_PATH_URL . 'libs/jquery-ui/themes/base/jquery.ui.css',
			FILE_PATH_URL . 'libs/jcrop/css/jquery.Jcrop.css',
			FILE_PATH_URL . 'libs/chosen/chosen.css',
		);
	}

	/**
	 * @return array
	 */
	protected function getScripts()
	{
		return array
		(
			FILE_PATH_URL . 'libs/jquery/jquery.js',
			FILE_PATH_URL . 'libs/jquery-ui/ui/jquery-ui.js',
			FILE_PATH_URL . 'libs/jcrop/js/jquery.Jcrop.js',
			FILE_PATH_URL . 'libs/chosen/chosen.jquery.js',
			FILE_PATH_URL . 'scripts/backend.js',
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