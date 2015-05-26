<?php

namespace Application\Controller;

use Application\Model\Page;
use Application\Model\Gallery;
use Application\Model\Tag;
use Nameless\Core\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * IndexController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class GalleryController extends FrontendController
{
    /**
     * @param $language_prefix
     *
     * @return Response
     */
    public function listItems($language_prefix)
    {
        $page_model    = new Page($this->getDatabase());
        $gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);
        $tag_model     = new Tag($this->getDatabase(), $this->container['timezone']);

        $lm_pictures = $gallery_model->getLastModifyDate();
        $lm_tags     = $tag_model->getLastModifyDate();
        $last_modify = ($lm_pictures > $lm_tags) ? $lm_pictures : $lm_tags;

        $response = $this->prepareResponse($last_modify);
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }

        $this->container['localization']->load('frontend', 'application', $language_prefix);

        $total = $this->container['benchmark']->getAppStatistic();

        $data = [
            'styles'  => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
            'scripts' => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
            'page'    => $page_model->getPage('gallery/list', $language_prefix),
            'total'   => [
                'time'   => round($total['time'], 5),
                'memory' => sizeHumanize($total['memory']),
            ],
            'subtemplates'   => ['content' => 'frontend/content/gallery-list'],
            'header'         => $this->container['localization']->get('header_gallery_list', $language_prefix),
            'sort_header'    => $this->container['localization']->get('sort_header_gallery', $language_prefix),
            'sort_by_date'   => $this->container['localization']->get('sort_by_date_gallery', $language_prefix),
            'sort_by_tags'   => $this->container['localization']->get('sort_by_tags_gallery', $language_prefix),
            'benchmark'      => $this->container['localization']->get('footer_benchmark', $language_prefix),
            'pictures'       => $gallery_model->selectAllPicsSortByYear(),
            'tags'           => $tag_model->selectAllTagsWithClass($gallery_model),
            'language'       => $language_prefix,
            'language_links' => [
                'ru' => $this->generateURL(
                    'gallery_list',
                    ['language_prefix' => 'ru', 'index_gallery' => '/list']
                ),
                'en' => $this->generateURL(
                    'gallery_list',
                    ['language_prefix' => 'en', 'index_gallery' => '/list']
                ),
            ],
        ];

        $data_filters = [
            'styles' => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
        ];

        return $this->render('frontend/frontend', $data, Template::FILTER_ESCAPE, $data_filters);
    }

    /**
     * @param $tag
     * @param $language_prefix
     *
     * @return Response
     */
    public function onetag($tag, $language_prefix)
    {
        if (is_null($tag)) {
            $this->notFound();
        }

        $page_model    = new Page($this->getDatabase());
        $gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);

        $last_modify = $gallery_model->getLastModifyDate();

        $response = $this->prepareResponse($last_modify);
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }

        $this->container['localization']->load('frontend', 'application', $language_prefix);

        $total = $this->container['benchmark']->getAppStatistic();

        $data = [
            'styles'  => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
            'scripts' => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
            'page'    => $page_model->getPage('gallery/onetag', $language_prefix),
            'total'   => [
                'time'   => round($total['time'], 5),
                'memory' => sizeHumanize($total['memory']),
            ],
            'subtemplates' => ['content' => 'frontend/content/gallery-onetag'],
            'header'       => $this->container['localization']->get(
                'header_gallery_onetag',
                $language_prefix,
                ['tag' => $tag]
            ),
            'sort_header'    => $this->container['localization']->get('sort_header_gallery', $language_prefix),
            'sort_by_date'   => $this->container['localization']->get('sort_by_date_gallery', $language_prefix),
            'sort_by_tags'   => $this->container['localization']->get('sort_by_tags_gallery', $language_prefix),
            'comeback'       => $this->container['localization']->get('comeback_link_gallery', $language_prefix),
            'benchmark'      => $this->container['localization']->get('footer_benchmark', $language_prefix),
            'pictures'       => $gallery_model->selectPicsByTag($tag),
            'language'       => $language_prefix,
            'language_links' => [
                'ru' => $this->generateURL('gallery_one_tag', ['language_prefix' => 'ru', 'tag' => $tag]),
                'en' => $this->generateURL('gallery_one_tag', ['language_prefix' => 'en', 'tag' => $tag]),
            ],
        ];

        $data['page']['title']       .= ' ' . $tag;
        $data['page']['description'] .= ' ' . $tag;
        $data['page']['keywords']    .= ', ' . $tag;

        $data_filters = [
            'styles'  => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
        ];
        return $this->render('frontend/frontend', $data, Template::FILTER_ESCAPE, $data_filters);
    }

    /**
     * @param $language_prefix
     *
     * @return Response
     */
    public function bytag($language_prefix)
    {
        $page_model    = new Page($this->getDatabase());
        $gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);
        $tag_model     = new Tag($this->getDatabase(), $this->container['timezone']);

        $lm_pictures = $gallery_model->getLastModifyDate();
        $lm_tags     = $tag_model->getLastModifyDate();
        $last_modify = ($lm_pictures > $lm_tags) ? $lm_pictures : $lm_tags;

        $response = $this->prepareResponse($last_modify);
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }

        $this->container['localization']->load('frontend', 'application', $language_prefix);

        $total = $this->container['benchmark']->getAppStatistic();

        $data = [
            'styles'  => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
            'scripts' => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
            'page'    => $page_model->getPage('gallery/bytag', $language_prefix),
            'total'   => [
                'time'   => round($total['time'], 5),
                'memory' => sizeHumanize($total['memory']),
            ],
            'subtemplates'       => ['content' => 'frontend/content/gallery-bytag'],
            'header'             => $this->container['localization']->get('header_gallery_bytag', $language_prefix),
            'sort_header'        => $this->container['localization']->get('sort_header_gallery', $language_prefix),
            'sort_by_date'       => $this->container['localization']->get('sort_by_date_gallery', $language_prefix),
            'sort_by_tags'       => $this->container['localization']->get('sort_by_tags_gallery', $language_prefix),
            'benchmark'          => $this->container['localization']->get('footer_benchmark', $language_prefix),
            'tags_with_pictures' => $tag_model->selectAllTagsWithPics($gallery_model),
            'tags'               => $tag_model->selectAllTagsWithClass($gallery_model),
            'language'           => $language_prefix,
            'language_links'     => [
                'ru' => $this->generateURL('gallery_bytag', ['language_prefix' => 'ru']),
                'en' => $this->generateURL('gallery_bytag', ['language_prefix' => 'en']),
            ],
        ];

        $data_filters = [
            'styles'  => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
        ];
        return $this->render('frontend/frontend', $data, Template::FILTER_ESCAPE, $data_filters);
    }
}
