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
	const ASSETS_NAME = 'frontend-bio';

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

	/**
	 * @return Response
	 */
	public function index ()
	{
		$page_model    = new Page($this->getDatabase());

		/*$lm_pictures = $gallery_model->getLastModifyDate();
		$lm_tags     = $tag_model->getLastModifyDate();
		$last_modify = ($lm_pictures > $lm_tags) ? $lm_pictures : $lm_tags;
		$last_modify

		$response = new Response();
		$response->setCache(array
		(
			'etag'          => NULL,//md5(serialize($pictures)),
			'last_modified' => $last_modify,
			'max_age'       => 0,
			's_maxage'      => 0,
			'public'        => TRUE,
		));

		if ($response->isNotModified($this->getRequest()))
		{
			return $response;
		}*/

		$data = array
		(

			'styles'       => $this->container['assets.dispatcher']->getAssets(self::ASSETS_NAME, $this->getStyles()),
			'scripts'      => $this->container['assets.dispatcher']->getAssets(self::ASSETS_NAME, $this->getScripts()),
			'page'         => $page_model->getPage('index/bio'),
			'subtemplates' => array('content' => 'frontend' . DS . 'bio'),
		);
		return $this->render('front_page', $data/*, $response*/);
	}
}