<?php

namespace Application\Controller;

use Application\Model\Page;
use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Template;

/**
 * BioController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class BioController extends FrontendController
{
	/**
	 * @param $language_prefix
	 *
	 * @return Response
	 */
	public function index ($language_prefix)
	{
		$page_model = new Page($this->getDatabase());

		if (!$language_prefix)
		{
			$language_prefix = $this->getLanguage();
			$this->redirect($this->generateURL('bio_index', array('language_prefix' => $language_prefix)));
		}
		$this->container['localization']->load('frontend', 'application', $language_prefix);

		$total    = $this->container['benchmark']->getAppStatistic();

		$data = array
		(

			'styles'       => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
			'scripts'      => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
			'page'         => $page_model->getPage('bio/index', $language_prefix),
			'total'        => array
			(
				'time'   => round($total['time'], 5),
				'memory' => sizeHumanize($total['memory']),
			),
			'subtemplates' => array('content' => 'frontend' . DS . 'content' . DS . 'bio'),
			'content'      => $this->container['localization']->get('content_bio', $language_prefix),
			'benchmark'    => $this->container['localization']->get('footer_benchmark', $language_prefix),
			'language'     => $language_prefix,
			'language_links' => array
			(
				'ru' => $this->generateURL('bio_index', array('language_prefix' => 'ru', 'bio_index' => '')),
				'en' => $this->generateURL('bio_index', array('language_prefix' => 'en', 'bio_index' => '')),
			),
			'gallery_link' => $this->generateURL('gallery_list', array('language_prefix' => $language_prefix, 'index_gallery' => '/list')),
		);
		$data_filters = array
		(
			'styles'      => Template::FILTER_RAW,
			'scripts'     => Template::FILTER_RAW,
			'content'     => Template::FILTER_XSS,
		);
		return $this->render('frontend/frontend', $data, Template::FILTER_ESCAPE, $data_filters);
	}
}