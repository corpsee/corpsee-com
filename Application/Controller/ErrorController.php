<?php

namespace Application\Controller;

use Application\Model\Page;
use Nameless\Modules\Auth\AccessDeniedException;
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
    const ERROR_UNKNOWN            = 0;
    const ERROR_INVALID_LOGIN      = 1;
    const ERROR_INVALID_PASSWORD   = 2;
    const ERROR_INVALID_IMAGE_TYPE = 3;
    const ERROR_INVALID_DATA       = 4;
    const ERROR_TAG_ALREADY_EXISTS = 5;

    /**
     * @param integer $code
     *
     * @return Response
     */
    public function errorAdmin($code)
    {
        $page_model = new Page($this->getDatabase());

        $asset_packages = $this->getAssetPackages();
        $data = [
            'styles'       => $this->container['assets.dispatcher']->getAssets('backend', [
                        FILE_PATH_URL . 'css/reset.css',
                        FILE_PATH_URL . 'css/typographic.css',
                        $asset_packages['jquery-ui']['css'],
                        $asset_packages['jcrop']['css'],
                        $asset_packages['chosen']['css'],
                    ], true),
            'scripts'      => $this->container['assets.dispatcher']->getAssets('backend', [
                        $asset_packages['jquery']['js'],
                        $asset_packages['jquery-ui']['js'],
                        $asset_packages['jcrop']['js'],
                        $asset_packages['chosen']['js'],
                        FILE_PATH_URL . 'js/backend.js',
                    ], true),
            'page'         => $page_model->getPage('admin/error'),
            'subtemplates' => ['content' => 'backend/content/error/error'],
        ];

        switch ((integer)$code) {
            case self::ERROR_INVALID_LOGIN:
                $data['error'] = 'Неверный логин.';
                break;
            case self::ERROR_INVALID_PASSWORD:
                $data['error'] = 'Неверный пароль.';
                break;
            case self::ERROR_INVALID_IMAGE_TYPE:
                $data['error'] = 'Неверный тип графического файла.';
                break;
            case self::ERROR_INVALID_DATA:
                $data['error'] = 'Ошибка в введенных данных.';
                break;
            case self::ERROR_TAG_ALREADY_EXISTS:
                $data['error'] = 'Такая метка уже существует.';
                break;
            case self::ERROR_UNKNOWN:
            default:
                $data['error'] = 'Ошибка!';
        }
        $data_filters = [
            'styles'  => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
        ];
        return $this->render('backend/backend-error', $data, Template::FILTER_ESCAPE, $data_filters);
    }

    /**
     * @param integer $code
     *
     * @throws AccessDeniedException
     * @throws HttpException
     */
    public function errorServer($code)
    {
        switch ((integer)$code) {
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

    public function error(FlattenException $exception)
    {
        $code = (integer)$exception->getStatusCode();

        if (403 !== $code && 404 !== $code) {
            $code = 500;
        }

        $language_prefix = $this->getLanguage();
        $this->container['localization']->load('frontend', 'application', $language_prefix);

        $page_model = new Page($this->getDatabase());

        $total = $this->container['benchmark']->getAppStatistic();

        $data = [
            'styles'  => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
            'scripts' => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
            'page'    => $page_model->getPage('error/' . $code, $language_prefix),
            'total'   => [
                'time'   => round($total['time'], 5),
                'memory' => sizeHumanize($total['memory']),
            ],
            'content'   => $this->container['localization']->get('content_' . $code, $language_prefix),
            'benchmark' => $this->container['localization']->get('footer_benchmark', $language_prefix),
            'language'  => $language_prefix,
            'language_links' => [
                'ru' => $this->generateURL('bio_index', ['language_prefix' => 'ru', 'bio_index' => '']),
                'en' => $this->generateURL('bio_index', ['language_prefix' => 'en', 'bio_index' => '']),
            ],
            'comeback' => $this->container['localization']->get('comeback_link_home', $language_prefix),
        ];

        $data_filters = [
            'styles'  => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
            'content' => Template::FILTER_XSS,
        ];
        return $this->render('frontend/frontend-error', $data, Template::FILTER_ESCAPE, $data_filters);
    }
}
