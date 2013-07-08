<?php

namespace Application\Controller;

use Application\Model\Page;
use Nameless\Modules\Auto\Auto;
use Nameless\Modules\Auto\User;
use Nameless\Modules\Auto\Providers\FileUserProvider;

class AdminController extends BackendController
{
	public function login ()
	{
		if (in_array('ROLE_REGISTERED', $this->container['auto.user']->getUserGroups()))
		{
			return $this->redirect('/admin/gallery');
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
				return $this->redirect('/admin/gallery');
			}
			elseif ($authenticate === 1)
			{
				return $this->forward('error', array('code' => 1));
			}
			else
			{
				return $this->forward('error', array('code' => 2));
			}
		}

		$data = array
		(
			'styles'       => array
			(
				FILE_PATH_URL . 'styles/reset.css',
				FILE_PATH_URL . 'styles/typographic.css'
			),
			'scripts'      => array(),
			'page'         => $page_model->getPage('admin/login'),
			'subtemplates' => array('content' => 'backend' . DS . 'login'),
			'action'       => '/admin/login',
		);

		return $this->render('back_page_minus', $data);
	}

	public function logout ()
	{
		$this->container['auto.user']->logout();
		return $this->redirect('/admin');
	}
}
