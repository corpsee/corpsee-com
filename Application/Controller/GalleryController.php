<?php

namespace Application\Controller;

use Application\Model\Page;
use Application\Model\Gallery;
use Application\Model\Tag;
use Nameless\Core\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * IndexController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class GalleryController extends FrontendController
{
	/**
	 * @param $language_prefix
	 *
	 * @return Response
	 */
	public function listItems ($language_prefix)
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
			'max_age'       => 0,
			's_maxage'      => 0,
			'public'        => TRUE,
		));

		if ($response->isNotModified($this->getRequest()))
		{
			return $response;
		}

		$this->container['localization']->load('frontend', 'application', $language_prefix);

		$total = $this->container['benchmark']->getAppStatistic();

		$data = array
		(

			'styles'       => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
			'scripts'      => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
			'page'         => $page_model->getPage('gallery/list', $language_prefix),
			'total'        => array
			(
				'time'   => round($total['time'], 5),
				'memory' => sizeHumanize($total['memory']),
			),
			'subtemplates'   => array('content' => 'frontend' . DS . 'gallery_list'),
			'header'         => $this->container['localization']->get('header_gallery_list', $language_prefix),
			'sort_header'    => $this->container['localization']->get('sort_header_gallery', $language_prefix),
			'sort_by_date'   => $this->container['localization']->get('sort_by_date_gallery', $language_prefix),
			'sort_by_tags'   => $this->container['localization']->get('sort_by_tags_gallery', $language_prefix),
			'benchmark'      => $this->container['localization']->get('footer_benchmark', $language_prefix),
			'pictures'       => $gallery_model->selectAllPicsSortByYear(),
			'tags'           => $tag_model->selectAllTagsWithClass($gallery_model),
			'language'       => $language_prefix,
			'language_links' => array
			(
				'ru' => $this->generateURL('gallery_list', array('language_prefix' => 'ru', 'index_gallery' => '/list')),
				'en' => $this->generateURL('gallery_list', array('language_prefix' => 'en', 'index_gallery' => '/list')),
			),
			'gallery_link'  => $this->generateURL('gallery_list', array('language_prefix' => $language_prefix, 'index_gallery' => '/list')),
		);
		$data_filters = array
		(
			'styles'      => Template::FILTER_RAW,
			'scripts'     => Template::FILTER_RAW,
		);
		return $this->render('front_page', $data, Template::FILTER_ESCAPE, $data_filters);
	}

	/**
	 * @param $tag
	 * @param $language_prefix
	 *
	 * @return Response
	 */
	public function onetag ($tag, $language_prefix)
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

		$this->container['localization']->load('frontend', 'application', $language_prefix);

		$total = $this->container['benchmark']->getAppStatistic();

		//TODO: подредактировать шаблон, вынести тэг в заголовок и тд
		$data = array
		(
			'styles'       => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
			'scripts'      => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
			'page'         => $page_model->getPage('gallery/onetag', $language_prefix),
			'total'        => array
			(
				'time'   => round($total['time'], 5),
				'memory' => sizeHumanize($total['memory']),
			),
			'subtemplates'   => array('content' => 'frontend' . DS . 'gallery_onetag'),
			'header'         => $this->container['localization']->get('header_gallery_onetag', $language_prefix, array('tag' => $tag)),
			'sort_header'    => $this->container['localization']->get('sort_header_gallery', $language_prefix),
			'sort_by_date'   => $this->container['localization']->get('sort_by_date_gallery', $language_prefix),
			'sort_by_tags'   => $this->container['localization']->get('sort_by_tags_gallery', $language_prefix),
			'comeback'       => $this->container['localization']->get('comeback_link_gallery', $language_prefix),
			'benchmark'      => $this->container['localization']->get('footer_benchmark', $language_prefix),
			'pictures'       => $gallery_model->selectPicsByTag($tag),
			'language'       => $language_prefix,
			'language_links' => array
			(
				'ru' => $this->generateURL('gallery_one_tag', array('language_prefix' => 'ru', 'tag' => $tag)),
				'en' => $this->generateURL('gallery_one_tag', array('language_prefix' => 'en', 'tag' => $tag)),
			),
			'gallery_link'  => $this->generateURL('gallery_list', array('language_prefix' => $language_prefix, 'index_gallery' => '/list')),
		);
		$data['page']['title']        .= ' ' . $tag;
		$data['page']['description']  .= ' ' . $tag;
		$data['page']['keywords']     .= ', ' . $tag;

		$data_filters = array
		(
			'styles'      => Template::FILTER_RAW,
			'scripts'     => Template::FILTER_RAW,
		);
		return $this->render('front_page', $data, Template::FILTER_ESCAPE, $data_filters);
	}

	/**
	 * @param $language_prefix
	 *
	 * @return Response
	 */
	public function bytag ($language_prefix)
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

		$this->container['localization']->load('frontend', 'application', $language_prefix);

		$total = $this->container['benchmark']->getAppStatistic();

		$data = array
		(
			'styles'             => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
			'scripts'            => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
			'page'               => $page_model->getPage('gallery/bytag', $language_prefix),
			'total'        => array
			(
				'time'   => round($total['time'], 5),
				'memory' => sizeHumanize($total['memory']),
			),
			'subtemplates'       => array('content' => 'frontend' . DS . 'gallery_bytag'),
			'header'             => $this->container['localization']->get('header_gallery_bytag', $language_prefix),
			'sort_header'        => $this->container['localization']->get('sort_header_gallery', $language_prefix),
			'sort_by_date'       => $this->container['localization']->get('sort_by_date_gallery', $language_prefix),
			'sort_by_tags'       => $this->container['localization']->get('sort_by_tags_gallery', $language_prefix),
			'benchmark'          => $this->container['localization']->get('footer_benchmark', $language_prefix),
			'tags_with_pictures' => $tag_model->selectAllTagsWithPics($gallery_model),
			'tags'               => $tag_model->selectAllTagsWithClass($gallery_model),
			'language'           => $language_prefix,
			'language_links'     => array
			(
				'ru' => $this->generateURL('gallery_bytag', array('language_prefix' => 'ru')),
				'en' => $this->generateURL('gallery_bytag', array('language_prefix' => 'en')),
			),
			'gallery_link'      => $this->generateURL('gallery_list', array('language_prefix' => $language_prefix, 'index_gallery' => '/list')),
		);
		$data_filters = array
		(
			'styles'      => Template::FILTER_RAW,
			'scripts'     => Template::FILTER_RAW,
		);
		return $this->render('front_page', $data, Template::FILTER_ESCAPE, $data_filters);
	}
}