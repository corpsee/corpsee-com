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
	 * @return Response
	 */
	public function index ()
	{
		$page_model = new Page($this->getDatabase());

		$data = array
		(
			'styles'       => array
			(
				FILE_PATH_URL . 'styles/reset.css',
				FILE_PATH_URL . 'styles/typographic.css'
			),
			'scripts'      => array
			(
				FILE_PATH_URL . 'jquery/jquery-1.8.3.min.js',
			),
			'page'         => $page_model->getPage('admin/login'),
			'subtemplates' => array('content' => 'frontend' . DS . 'tool_index'),
		);

		return $this->render('back_page_minus', $data);
	}
}