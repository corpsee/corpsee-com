<?php

namespace Application\Controller;

use Application\Model\Page;
use Nameless\Modules\Auto\Auto;
use Nameless\Modules\Auto\Providers\FileUserProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Template;

/**
 * Base AdminController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class AdminController extends BackendController
{
	/**
	 * @return RedirectResponse|Response
	 */
	public function login ()
	{
		if (in_array('ROLE_REGISTERED', $this->container['auto.user']->getUserGroups()))
		{
			return $this->redirect($this->generateURL('admin_gallery_list'));
		}

		$page_model = new Page($this->getDatabase());

		/*if ($this->getRequest()->cookies->has(User::COOKIE_AUTOLOGIN) && !$auto->autoAuthenticate($this->getCookies(User::COOKIE_AUTOLOGIN)))
		{
			$this->container['auto.user']->autoLogin($auto);
			echo 1; exit;
			return $this->redirect('/admin/gallery');
		}*/

		if ($this->isMethod('POST'))
		{
			// аутентификация
			$auto = new Auto(new FileUserProvider($this->container['auto.users']), $this->getPost('login'), $this->getPost('password'));
			$authenticate = $auto->authenticate();

			if ($authenticate === 0)
			{
				//$response = new RedirectResponse('/admin/gallery');
				//$response = $this->container['auto.user']->login($auto, $response, 3600*24*30);
				$this->container['auto.user']->login($auto);
				return $this->redirect($this->generateURL('admin_gallery_list'));
			}
			elseif ($authenticate === 1)
			{
				return $this->forward('admin_error', array('code' => ErrorController::ERROR_INVALID_LOGIN));
			}
			else
			{
				return $this->forward('admin_error', array('code' => ErrorController::ERROR_INVALID_PASSWORD));
			}
		}

		$asset_packages = $this->container['assets.packages'];
		$styles = array
		(
			$asset_packages['bootstrap']['css'],
			FILE_PATH_URL . 'css/backend-bootstrap.less',
		);

		$data = array
		(
			'styles'       => $this->container['assets.dispatcher']->getAssets('frontend', $styles, TRUE),
			'scripts'      => $this->container['assets.dispatcher']->getAssets('frontend', array(), TRUE),
			'page'         => $page_model->getPage('admin/login', 'ru'),
			'subtemplates' => array('content' => 'backend' . DS . 'content' . DS . 'login' . DS . 'login-bootstrap'),
			'action'       => $this->generateURL('admin_login'),
		);
		$data_filters = array
		(
			'styles'      => Template::FILTER_RAW,
			'scripts'     => Template::FILTER_RAW,
		);
		return $this->render('backend/backend-bootstrap', $data, Template::FILTER_ESCAPE, $data_filters);
	}

	/**
	 * @return RedirectResponse
	 */
	public function logout ()
	{
		$this->container['auto.user']->logout();
		return $this->redirect($this->generateURL('admin_login'));
	}
}
