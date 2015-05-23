<?php

namespace Application\Controller;

use Application\Model\Page;
use Nameless\Modules\Auth\Auth;
use Nameless\Modules\Auth\Providers\FileUserProvider;
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
    public function login()
    {
        if (in_array('ROLE_REGISTERED', $this->container['auth.user']->getUserGroups())) {
            return $this->redirect($this->generateURL('admin_gallery_list'));
        }

        $page_model = new Page($this->getDatabase());

        if ($this->isMethod('POST')) {
            $auth_config  = $this->container['auth'];
            $auth         = new Auth(
                new FileUserProvider($auth_config['users']),
                $this->getPost('login'),
                $this->getPost('password')
            );
            $authenticate = $auth->authenticate();

            if ($authenticate === 0) {
                $this->container['auth.user']->login($auth);
                return $this->redirect($this->generateURL('admin_gallery_list'));
            } elseif ($authenticate === 1) {
                return $this->forward('admin_error', ['code' => ErrorController::ERROR_INVALID_LOGIN]);
            } else {
                return $this->forward('admin_error', ['code' => ErrorController::ERROR_INVALID_PASSWORD]);
            }
        }

        $data = [
            'styles'       => $this->container['assets.dispatcher']->getAssets('backend', $this->getStyles(), true),
            'scripts'      => $this->container['assets.dispatcher']->getAssets('backend', $this->getScripts(), true),
            'page'         => $page_model->getPage('admin/login', 'ru'),
            'subtemplates' => ['content' => 'backend/content/login/login'],
            'action'       => $this->generateURL('admin_login'),
        ];
        $data_filters = [
            'styles'  => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
        ];
        return $this->render('backend/backend-bootstrap', $data, Template::FILTER_ESCAPE, $data_filters);
    }

    /**
     * @return RedirectResponse
     */
    public function logout()
    {
        $this->container['auth.user']->logout();
        return $this->redirect($this->generateURL('admin_login'));
    }
}
