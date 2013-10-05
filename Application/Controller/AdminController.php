<?php

namespace Application\Controller;

use Application\Model\Page;
use Nameless\Modules\Auto\Auto;
use Nameless\Modules\Auto\User;
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
			return $this->redirect('/admin/gallery/list');
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
				//return $response;
				//echo 1; exit;
				$this->container['auto.user']->login($auto);
				return $this->redirect('/admin/gallery/list');
			}
			elseif ($authenticate === 1)
			{
				return $this->forward('admin_error', array('code' => 1));
			}
			else
			{
				return $this->forward('admin_error', array('code' => 2));
			}
		}
		$styles = array
		(
			FILE_PATH_URL . 'libs/bootstrap/docs/assets/css/bootstrap.css',
			FILE_PATH_URL . 'styles/backend2.css',
		);

		$data = array
		(
			'styles'       => $this->container['assets.dispatcher']->getAssets('frontend', $styles, TRUE),
			'scripts'      => $this->container['assets.dispatcher']->getAssets('frontend', array(), TRUE),
			'page'         => $page_model->getPage('admin/login', 'ru'),
			'subtemplates' => array('content' => 'backend' . DS . 'login2'),
			'action'       => '/admin/login',
		);
		$data_filters = array
		(
			'styles'      => Template::FILTER_RAW,
			'scripts'     => Template::FILTER_RAW,
		);
		return $this->render('back_page2', $data, Template::FILTER_ESCAPE, $data_filters);
	}

	/**
	 * @return RedirectResponse
	 */
	public function logout ()
	{
		$this->container['auto.user']->logout();
		return $this->redirect('/admin/login');
	}
}
