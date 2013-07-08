<?php

namespace Application\Controller;

use Application\Model\Page;
use Nameless\Modules\Auto\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrorController extends BackendController
{
	public function errorAdmin ($code = NULL)
	{
		$page_model = new Page($this->getDatabase());

		$data = array
		(
			'styles'       => $this->getStyles(),
			'scripts'      => $this->getScripts(),
			'page'         => $page_model->getPage('admin/error'),
			'subtemplates' => array('content' => 'backend' . DS . 'error'),
		);

		switch ($code)
		{
			case 1:
				$data['error'] = 'Неправильный логин!';
				break;
			case 2:
				$data['error'] = 'Неправильный пароль!';
				break;
			case 3:
				$data['error'] = 'Неверный тип графического файла!';
				break;
			case 4:
				$data['error'] = 'Ошибка в введенных данных!';
				break;
			case 6:
				$data['error'] = 'Такая метка уже существует!';
				break;
			default:
				$data['error'] = 'Ошибка!';
		}

		return $this->render('back_page_minus', $data);
	}

	public function errorServer ($code)
	{
		switch ((int)$code)
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
}
