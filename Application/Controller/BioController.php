<?php

namespace Application\Controller;

use Application\Model\Page;
use Application\Model\Gallery;
use Application\Model\PullRequest;
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
		$page_model         = new Page($this->getDatabase());
		$gallery_model      = new Gallery($this->getDatabase(), $this->container['timezone']);
		$pull_request_model = new PullRequest($this->getDatabase());

		if (!$language_prefix)
		{
			$language_prefix = $this->getLanguage();
			$this->redirect($this->generateURL('bio_index', array('language_prefix' => $language_prefix)));
		}

		$response = new Response();
		$response->setCache(array
		(
			'etag'          => NULL,//md5(serialize($pictures)),
			'last_modified' => \DateTime::createFromFormat('U', time() - 60),
			'max_age'       => 3600,
			's_maxage'      => 3600,
			'public'        => TRUE,
		));

		if ($response->isNotModified($this->getRequest()))
		{
			return $response;
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
			'pull_requests'  => $pull_request_model->selectPullRequests(10),
			'pictures'       => $gallery_model->selectPics(8),
			'requests_link'  => $this->container['localization']->get('requests_link', $language_prefix),
			'pictures_link'  => $this->container['localization']->get('pictures_link', $language_prefix),
			'requests_title' => $this->container['localization']->get('bio_requests_title', $language_prefix),
			'pictures_title' => $this->container['localization']->get('bio_pictures_title', $language_prefix),
		);
		$data_filters = array
		(
			'styles'      => Template::FILTER_RAW,
			'scripts'     => Template::FILTER_RAW,
			'content'     => Template::FILTER_XSS,
		);
		return $this->render('frontend/frontend', $data, Template::FILTER_ESCAPE, $data_filters);
	}

		/**
	 * @param $language_prefix
	 *
	 * @return Response
	 */
	public function requests ($language_prefix)
	{
		$page_model         = new Page($this->getDatabase());
		$pull_request_model = new PullRequest($this->getDatabase());

		if (!$language_prefix)
		{
			$language_prefix = $this->getLanguage();
			$this->redirect($this->generateURL('bio_index', array('language_prefix' => $language_prefix)));
		}

		$response = new Response();
		$response->setCache(array
		(
			'etag'          => NULL,//md5(serialize($pictures)),
			'last_modified' => \DateTime::createFromFormat('U', time() - 60),
			'max_age'       => 3600,
			's_maxage'      => 3600,
			'public'        => TRUE,
		));

		if ($response->isNotModified($this->getRequest()))
		{
			return $response;
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
			'subtemplates' => array('content' => 'frontend' . DS . 'content' . DS . 'bio-requests'),
			'benchmark'    => $this->container['localization']->get('footer_benchmark', $language_prefix),
			'language'     => $language_prefix,
			'language_links' => array
			(
				'ru' => $this->generateURL('bio_index', array('language_prefix' => 'ru', 'bio_index' => '')),
				'en' => $this->generateURL('bio_index', array('language_prefix' => 'en', 'bio_index' => '')),
			),
			//TODO: Add pagination
			'pull_requests'  => $pull_request_model->selectPullRequests(),
			'comeback'       => $this->container['localization']->get('comeback_link_home', $language_prefix),
			'requests_title' => $this->container['localization']->get('requests_title', $language_prefix),
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