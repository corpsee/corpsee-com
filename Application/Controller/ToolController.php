<?php

namespace Application\Controller;

use Nameless\Core\Controller;
use Application\Model\Page;

class ToolController extends Controller
{
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