<?php

namespace Application\Controller;

use Nameless\Core\Controller;
use Application\Model\Page;
use Application\Model\Gallery;
use Application\Model\Tag;
use Symfony\Component\HttpFoundation\Response;

/**
 * IndexController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class GalleryController extends Controller
{
	public function __construct()
	{
		//$this->container['localization']->load('gallery');
	}
	/**
	 * @return array
	 */
	protected function getScripts()
	{
		return array
		(
			FILE_PATH_URL . 'lib/jquery/1.10.2/jquery.js',
			FILE_PATH_URL . 'lib/lightbox/2.6-custom/lightbox.js',
			FILE_PATH_URL . 'scripts/frontend.js'
		);
	}

	/**
	 * @return array
	 */
	protected function getStyles()
	{
		return array
		(
			FILE_PATH_URL . 'lib/lightbox/2.6-custom/lightbox.css',
			FILE_PATH_URL . 'lib/normalize/1.1.2/normalize.css',
			FILE_PATH_URL . 'styles/frontend.less',
		);
	}

	/**
	 * @return Response
	 */
	public function listItems ()
	{
		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);
		$tag_model     = new Tag($this->getDatabase(), $this->container['timezone']);

		$language = 'ru';
		$this->container['localization']->load('frontend', 'application', $language);

		//TODO: see last modify templates date (See BioController)
		$lm_pictures = $gallery_model->getLastModifyDate();
		$lm_tags     = $tag_model->getLastModifyDate();
		$last_modify = ($lm_pictures > $lm_tags) ? $lm_pictures : $lm_tags;

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
		}

		$total = $this->container['benchmark']->getAppStatistic();

		$data = array
		(

			'styles'       => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
			'scripts'      => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
			'page'         => $page_model->getPage('gallery/list'),
			'total'        => array
			(
				'time'   => round($total['time'], 5),
				'memory' => sizeHumanize($total['memory']),
			),
			'subtemplates' => array('content' => 'frontend' . DS . 'gallery_list'),
			'header'       => $this->container['localization']->get('header_gallery_list', array(), $language),
			'sort_header'  => $this->container['localization']->get('sort_header_gallery', array(), $language),
			'sort_by_date' => $this->container['localization']->get('sort_by_date_gallery', array(), $language),
			'sort_by_tags' => $this->container['localization']->get('sort_by_tags_gallery', array(), $language),
			'benchmark'    => $this->container['localization']->get('footer_benchmark', array(), $language),
			'pictures'     => $gallery_model->selectAllPicsSortByYear(),
			'tags'         => $tag_model->selectAllTagsWithClass($gallery_model),
		);
		return $this->render('front_page', $data, $response);
	}

	/**
	 * @param string $tag
	 *
	 * @return Response
	 */
	public function onetag ($tag)
	{
		if (is_null($tag))
		{
			$this->notFound();
		}

		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);

		$language = 'ru';
		$this->container['localization']->load('frontend', 'application', $language);

		$last_modify = $gallery_model->getLastModifyDate();

		$response = new Response();
		$response->setCache(array
		(
			'etag'          => NULL,//md5(serialize($pictures)),
			'last_modified' => $last_modify,
			'max_age'       => 0,//60,
			's_maxage'      => 0,//60,
			'public'        => TRUE,
		));

		if ($response->isNotModified($this->getRequest()))
		{
			return $response;
		}

		$total = $this->container['benchmark']->getAppStatistic();

		//TODO: подредактировать шаблон, вынести тэг в заголовок и тд
		$data = array
		(
			'styles'       => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
			'scripts'      => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
			'page'         => $page_model->getPage('gallery/onetag'),
			'total'        => array
			(
				'time'   => round($total['time'], 5),
				'memory' => sizeHumanize($total['memory']),
			),
			'subtemplates' => array('content' => 'frontend' . DS . 'gallery_onetag'),
			'header'       => $this->container['localization']->get('header_gallery_onetag', array('tag' => $tag), $language),
			'sort_header'  => $this->container['localization']->get('sort_header_gallery', array(), $language),
			'sort_by_date' => $this->container['localization']->get('sort_by_date_gallery', array(), $language),
			'sort_by_tags' => $this->container['localization']->get('sort_by_tags_gallery', array(), $language),
			'benchmark'    => $this->container['localization']->get('footer_benchmark', array(), $language),
			'pictures'     => $gallery_model->selectPicsByTag($tag),
		);
		$data['page']['title']       .= ' ' . $tag;
		$data['page']['description'] .= ' ' . $tag;
		$data['page']['keywords']     .= ', ' . $tag;

		return $this->render('front_page', $data, $response);
	}

	/**
	 * @return Response
	 */
	public function bytag ()
	{
		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);
		$tag_model     = new Tag($this->getDatabase(), $this->container['timezone']);

		$language = 'ru';
		$this->container['localization']->load('frontend', 'application', $language);

		$lm_pictures = $gallery_model->getLastModifyDate();
		$lm_tags     = $tag_model->getLastModifyDate();
		$last_modify = ($lm_pictures > $lm_tags) ? $lm_pictures : $lm_tags;

		$response = new Response();
		$response->setCache(array
		(
			'etag'          => NULL,//md5(serialize($pictures)),
			'last_modified' => $last_modify,
			'max_age'       => 0,//60,
			's_maxage'      => 0,//60,
			'public'        => TRUE,
		));

		if ($response->isNotModified($this->getRequest()))
		{
			return $response;
		}

		$total = $this->container['benchmark']->getAppStatistic();

		$data = array
		(
			'styles'             => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
			'scripts'            => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
			'page'               => $page_model->getPage('gallery/bytag'),
			'total'        => array
			(
				'time'   => round($total['time'], 5),
				'memory' => sizeHumanize($total['memory']),
			),
			'subtemplates'       => array('content' => 'frontend' . DS . 'gallery_bytag'),
			'header'             => $this->container['localization']->get('header_gallery_bytag', array(), $language),
			'sort_header'        => $this->container['localization']->get('sort_header_gallery', array(), $language),
			'sort_by_date'       => $this->container['localization']->get('sort_by_date_gallery', array(), $language),
			'sort_by_tags'       => $this->container['localization']->get('sort_by_tags_gallery', array(), $language),
			'comeback'           => $this->container['localization']->get('comeback_link_gallery', array(), $language),
			'benchmark'          => $this->container['localization']->get('footer_benchmark', array(), $language),
			'tags_with_pictures' => $tag_model->selectAllTagsWithPics($gallery_model),
			'tags'               => $tag_model->selectAllTagsWithClass($gallery_model),
		);
		return $this->render('front_page', $data, $response);
	}
}