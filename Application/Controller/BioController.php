<?php

namespace Application\Controller;

use Application\Model\Page;
use Symfony\Component\HttpFoundation\Response;

/**
 * BioController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class BioController extends FrontendController
{
	/**
	 * @return Response
	 */
	public function index ()
	{
		$page_model = new Page($this->getDatabase());

		// Caching
		/*$lm_template = \DateTime::createFromFormat('U', filemtime($this->container['templates_path'] . 'front_page.' . $this->container['templates_extension']));
		$lm_template->setTimezone(new \DateTimeZone($this->container['timezone']));

		$lm_subtemplate = \DateTime::createFromFormat('U', filemtime($this->container['templates_path'] . 'frontend' . DS . 'bio.' . $this->container['templates_extension']));
		$lm_subtemplate->setTimezone(new \DateTimeZone($this->container['timezone']));

		$last_modify = ($lm_template > $lm_subtemplate) ? $lm_template : $lm_subtemplate;

		$response = new Response();
		$response->setCache(array
		(
			'etag'          => NULL,
			'last_modified' => $last_modify,
			'max_age'       => 0,
			's_maxage'      => 0,
			'public'        => TRUE,
		));

		if ($response->isNotModified($this->getRequest()))
		{
			return $response;
		}*/

		$total = $this->container['benchmark']->getAppStatistic();

		$data = array
		(

			'styles'       => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
			'scripts'      => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
			'page'         => $page_model->getPage('bio/index'),
			'total'        => array
			(
				'time'   => round($total['time'], 5),
				'memory' => sizeHumanize($total['memory']),
			),
			'subtemplates' => array('content' => 'frontend' . DS . 'bio'),
			'header'       => $this->container['localization']->get('header_bio', $this->getLanguage()),
			'content'      => $this->container['localization']->get('content_bio', $this->getLanguage()),
			'benchmark'    => $this->container['localization']->get('footer_benchmark', $this->getLanguage()),
		);
		return $this->render('front_page', $data);
	}
}