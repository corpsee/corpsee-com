<?php

namespace Application\Controller;

use Application\Model\Page;
use Nameless\Modules\Auto\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * ErrorController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ErrorController extends BackendController
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

	/**
	 * @param integer $code
	 *
	 * @throws AccessDeniedException
	 * @throws HttpException
	 */
	public function errorServer ($code)
	{
		//print_r($code); exit;
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
}
