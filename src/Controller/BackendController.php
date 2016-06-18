<?php

namespace Application\Controller;

use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Controller;
use Nameless\Modules\Auth\AccessDeniedException;

/**
 * Base BackendController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class BackendController extends Controller
{
    /**
     * @var array
     */
    protected $asset_libs = null;

    /**
     * @return array
     */
    protected function getMenuLinks()
    {
        return [[
                'url'   => $this->generateURL('bio_index'),
                'text'  => 'Фронтэнд',
                'class' => 'first',
            ], [
                'url'   => $this->generateURL('admin_gallery_list'),
                'text'  => 'Галерея',
                'class' => '',
            ], [
                'url'   => $this->generateURL('admin_tag_list'),
                'text'  => 'Метки',
                'class' => '',
            ], [
                'url'   => $this->generateURL('admin_project_list'),
                'text'  => 'Проекты',
                'class' => '',
            ], [
                'url'   => $this->generateURL('admin_pull_request_list'),
                'text'  => 'Запросы',
                'class' => '',
            ], [
                'url'   => $this->generateURL('admin_logout'),
                'text'  => 'Выйти',
                'class' => 'last',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getAssetLibs()
    {
        if (is_null($this->asset_libs)) {
            $assets = $this->container['assets'];
            $this->asset_libs = $assets['libs'];
        }

        return $this->asset_libs;
    }

    /**
     * @return array
     */
    protected function getStyles()
    {
        $asset_libs = $this->getAssetLibs();

        return [
            FILE_PATH_URL . 'css/reset.css',
            FILE_PATH_URL . 'css/typographic.css',
            $asset_libs['jquery-ui']['css'],
            $asset_libs['jcrop']['css'],
            $asset_libs['chosen']['css'],
        ];
    }

    /**
     * @return array
     */
    protected function getScripts()
    {
        $asset_libs = $this->getAssetLibs();

        return [
            $asset_libs['jquery']['js'],
            $asset_libs['jquery-ui']['js'],
            $asset_libs['jcrop']['js'],
            $asset_libs['chosen']['js'],
            FILE_PATH_URL . 'js/backend.js',
        ];
    }

    /**
     * @throws \Nameless\Modules\Auth\AccessDeniedException
     */
    public function before()
    {
        $access = $this->container['auth.user']->getAccessByRoute($this->getAttributes('_route'));

        if (!$access) {
            throw new AccessDeniedException('Access Denied!');
        }
    }

    /**
     * @param string $form_name
     *
     * @return Response
     */
    protected function getValidation($form_name)
    {
        if ($msg = $this->validate($form_name)) {
            $response = ['status' => 'error', 'msg' => $msg];
        } else {
            $response = ['status' => 'success'];
        }

        return new Response(json_encode($response), 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @param string $form
     * 
     * @return array
     */
    protected function validate($form)
    {
        $errors = [];
        $validation_config = $this->container['validation'];
        if (isset($validation_config['rules'][$form])) {
            $post = $this->container['request']->request->all();
            foreach ($post as $key => $value) {
                if (isset($validation_config['rules'][$form][$key])) {
                    if ($validate = $this->container['validation.validator']->validate(
                        $value,
                        $validation_config['rules'][$form][$key]
                    )) {
                        $errors[] = $validate;
                    }
                }
            }
        } else {
            throw new \InvalidArgumentException('Invalid form name');
        }
        return $errors;
    }
}
