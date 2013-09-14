<?php

namespace Application\Controller;

use Application\Model\Page;
use Application\Model\Gallery;
use Application\Model\Tag;
use Symfony\Component\HttpFoundation\Response;

/**
 * IndexController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class GalleryController extends FrontendController
{
	/**
	 * @return Response
	 */
	public function listItems ()
	{
		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);
		$tag_model     = new Tag($this->getDatabase(), $this->container['timezone']);

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
			'header'       => $this->container['localization']->get('header_gallery_list',  $this->getLanguage()),
			'sort_header'  => $this->container['localization']->get('sort_header_gallery',  $this->getLanguage()),
			'sort_by_date' => $this->container['localization']->get('sort_by_date_gallery',  $this->getLanguage()),
			'sort_by_tags' => $this->container['localization']->get('sort_by_tags_gallery',  $this->getLanguage()),
			'benchmark'    => $this->container['localization']->get('footer_benchmark',  $this->getLanguage()),
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
			'header'       => $this->container['localization']->get('header_gallery_onetag',  $this->getLanguage(), array('tag' => $tag)),
			'sort_header'  => $this->container['localization']->get('sort_header_gallery',  $this->getLanguage()),
			'sort_by_date' => $this->container['localization']->get('sort_by_date_gallery',  $this->getLanguage()),
			'sort_by_tags' => $this->container['localization']->get('sort_by_tags_gallery',  $this->getLanguage()),
			'comeback'     => $this->container['localization']->get('comeback_link_gallery',  $this->getLanguage()),
			'benchmark'    => $this->container['localization']->get('footer_benchmark',  $this->getLanguage()),
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
			'header'             => $this->container['localization']->get('header_gallery_bytag',  $this->getLanguage()),
			'sort_header'        => $this->container['localization']->get('sort_header_gallery',  $this->getLanguage()),
			'sort_by_date'       => $this->container['localization']->get('sort_by_date_gallery',  $this->getLanguage()),
			'sort_by_tags'       => $this->container['localization']->get('sort_by_tags_gallery',  $this->getLanguage()),
			'benchmark'          => $this->container['localization']->get('footer_benchmark',  $this->getLanguage()),
			'tags_with_pictures' => $tag_model->selectAllTagsWithPics($gallery_model),
			'tags'               => $tag_model->selectAllTagsWithClass($gallery_model),
		);
		return $this->render('front_page', $data, $response);
	}
}