<?php

namespace Application\Controller;

use Application\Model\Page;
use Nameless\Modules\Auto\AccessDeniedException;
use Nameless\Core\Template;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Debug\Exception\FlattenException;

/**
 * ErrorController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ErrorController extends FrontendController
{
	/**
	 * @param integer $code
	 *
	 * @return Response
	 */
	public function errorAdmin ($code)
	{
		$page_model = new Page($this->getDatabase());

		$data = array
		(
			'styles'       => $this->getStyles(),
			'scripts'      => $this->getScripts(),
			'page'         => $page_model->getPage('admin/error'),
			'subtemplates' => array('content' => 'backend' . DS . 'error'),
		);

		//print_r($code); exit;
		switch ((integer)$code)
		{
			case 1:
				$data['error'] = 'Неверный логин.';
				break;
			case 2:
				$data['error'] = 'Неверный пароль.';
				break;
			case 3:
				$data['error'] = 'Неверный тип графического файла.';
				break;
			case 4:
				$data['error'] = 'Ошибка в введенных данных.';
				break;
			case 6:
				$data['error'] = 'Такая метка уже существует.';
				break;
			default:
				$data['error'] = 'Ошибка!';
		}

		return $this->render('back_page_minus', $data);
	}

	/**
	 * @param integer $code
	 *
	 * @throws AccessDeniedException
	 * @throws HttpException
	 */
	public function errorServer ($code)
	{
		switch ((integer)$code)
		{
			case 403:
				throw new AccessDeniedException('Access denied!');
				break;
			case 404:
				return $this->notFound('Not found');
				break;
			default:
				throw new HttpException(500, 'Server error!');
		}
	}

	public function error (FlattenException $exception)
	{
		$code = (integer)$exception->getStatusCode();

		if (403 !== $code && 404 !== $code)
		{
			$code = 500;
		}

		$language_prefix = $this->getLanguage();
		$this->container['localization']->load('frontend', 'application', $language_prefix);

		$page_model = new Page($this->getDatabase());

		$total    = $this->container['benchmark']->getAppStatistic();

		$data = array
		(

			'styles'       => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
			'scripts'      => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
			'page'         => $page_model->getPage('error/' . $code, $language_prefix),
			'total'        => array
			(
				'time'   => round($total['time'], 5),
				'memory' => sizeHumanize($total['memory']),
			),
			'content'      => $this->container['localization']->get('content_' . $code, $language_prefix),
			'benchmark'    => $this->container['localization']->get('footer_benchmark', $language_prefix),
			'language'     => $language_prefix,
			'language_links' => array
			(
				'ru' => $this->generateURL('bio_index', array('language_prefix' => 'ru', 'bio_index' => '')),
				'en' => $this->generateURL('bio_index', array('language_prefix' => 'en', 'bio_index' => '')),
			),
			'gallery_link' => $this->generateURL('gallery_list', array('language_prefix' => $language_prefix, 'index_gallery' => '/list')),
			'comeback'     => $this->container['localization']->get('comeback_link_home', $language_prefix),
		);
		$data_filters = array
		(
			'styles'      => Template::FILTER_RAW,
			'scripts'     => Template::FILTER_RAW,
			'content'     => Template::FILTER_XSS,
		);
		return $this->render('error', $data, Template::FILTER_ESCAPE, $data_filters);
	}
}
