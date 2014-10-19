<?php

namespace Application\Controller;

use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Controller;
use Nameless\Modules\Auto\AccessDeniedException;

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
    protected $asset_packages = null;

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
                'url'   => $this->generateURL('admin_logout'),
                'text'  => 'Выйти',
                'class' => 'last',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function  getAssetPackages()
    {
        if (is_null($this->asset_packages)) {
            $this->asset_packages = $this->container['assets.packages'];
        }
        return $this->asset_packages;
    }

    /**
     * @return array
     */
    protected function getStyles()
    {
        $asset_packages = $this->getAssetPackages();

        return [
            FILE_PATH_URL . 'css/reset.css',
            FILE_PATH_URL . 'css/typographic.css',
            $asset_packages['jquery-ui']['css'],
            $asset_packages['jcrop']['css'],
            $asset_packages['chosen']['css'],
        ];
    }

    /**
     * @return array
     */
    protected function getScripts()
    {
        $asset_packages = $this->getAssetPackages();

        return [
            $asset_packages['jquery']['js'],
            $asset_packages['jquery-ui']['js'],
            $asset_packages['jcrop']['js'],
            $asset_packages['chosen']['js'],
            FILE_PATH_URL . 'js/backend.js',
        ];
    }

    /**
     * @throws \Nameless\Modules\Auto\AccessDeniedException
     */
    public function before()
    {
        $access = $this->container['auto.user']->getAccessByRoute($this->getAttributes('_route'));

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
        if ($msg = $this->container['validation.validator']->validate($form_name)) {
            $response = ['status' => 'error', 'msg' => $msg];
        } else {
            $response = ['status' => 'success'];
        }

        return new Response(json_encode($response), 200, ['Content-Type' => 'application/json']);
    }
}