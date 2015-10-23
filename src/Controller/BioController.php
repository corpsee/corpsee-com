<?php

namespace Application\Controller;

use Application\Model\Page;
use Application\Model\Gallery;
use Application\Model\Project;
use Application\Model\PullRequest;
use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Template;

/**
 * BioController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class BioController extends FrontendController
{
    /**
     * @param $language_prefix
     *
     * @return Response
     */
    public function index($language_prefix)
    {
        $page_model         = new Page($this->getDatabase());
        $gallery_model      = new Gallery($this->getDatabase());
        $pull_request_model = new PullRequest($this->getDatabase());
        $project_model      = new Project($this->getDatabase());

        if ($redirect = $this->languageRedirect($language_prefix, 'bio_index')) {
            return $redirect;
        }

        $response = $this->prepareResponse(\DateTime::createFromFormat('U', time() - 60));
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }

        $this->container['localization']->load('frontend', 'application', $language_prefix);

        $total = $this->container['benchmark']->getAppStatistic();

        $data = [
            'styles'  => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
            'scripts' => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
            'page'    => $page_model->getPage('bio/index', $language_prefix),
            'total'   => [
                'time'   => round($total['time'], 5),
                'memory' => sizeHumanize($total['memory']),
            ],
            'subtemplates'   => ['content' => 'frontend/content/bio'],
            'content'        => $this->container['localization']->get('content_bio', $language_prefix),
            'benchmark'      => $this->container['localization']->get('footer_benchmark', $language_prefix),
            'language'       => $language_prefix,
            'language_links' => [
                'ru' => $this->generateURL('bio_index', ['language_prefix' => 'ru', 'bio_index' => '']),
                'en' => $this->generateURL('bio_index', ['language_prefix' => 'en', 'bio_index' => '']),
            ],
            'pull_requests'  => $pull_request_model->selectPullRequests(5),
            'projects'       => $project_model->getAll(),
            'pictures'       => $gallery_model->selectPics(8),
            'requests_link'  => $this->container['localization']->get('requests_link', $language_prefix),
            'projects_link'  => $this->container['localization']->get('projects_link', $language_prefix),
            'pictures_link'  => $this->container['localization']->get('pictures_link', $language_prefix),
            'requests_title' => $this->container['localization']->get('bio_requests_title', $language_prefix),
            'projects_title' => $this->container['localization']->get('bio_projects_title', $language_prefix),
            'pictures_title' => $this->container['localization']->get('bio_pictures_title', $language_prefix),
        ];

        $data_filters = [
            'styles'  => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
            'content' => Template::FILTER_XSS,
        ];
        return $this->render('frontend/frontend', $data, Template::FILTER_ESCAPE, $data_filters);
    }

    /**
     * @param $language_prefix
     *
     * @return Response
     */
    public function requests($year, $language_prefix)
    {
        $page_model = new Page($this->getDatabase());
        $pull_request_model = new PullRequest($this->getDatabase());

        if (null !== $year) {
            $year = (integer)$year;
        } else {
            $year = (integer)date('Y');
        }
        $pull_requests = $pull_request_model->selectPullRequests(null, $year);

        if (!$pull_requests && (($year - 1) >= 2013)) {
            return $this->redirect(
                $this->generateURL('bio_requests', ['language_prefix' => $language_prefix, 'year' => ($year - 1)])
            );
        }

        if ($redirect = $this->languageRedirect($language_prefix, 'bio_index')) {
            return $redirect;
        }

        $response = $this->prepareResponse(\DateTime::createFromFormat('U', time() - 60));
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }

        $this->container['localization']->load('frontend', 'application', $language_prefix);

        $total = $this->container['benchmark']->getAppStatistic();

        $data = [
            'styles'  => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles()),
            'scripts' => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts()),
            'page'    => $page_model->getPage('bio/index', $language_prefix),
            'total'   => [
                'time'   => round($total['time'], 5),
                'memory' => sizeHumanize($total['memory']),
            ],
            'subtemplates'   => ['content' => 'frontend/content/bio-requests'],
            'benchmark'      => $this->container['localization']->get('footer_benchmark', $language_prefix),
            'language'       => $language_prefix,
            'language_links' => [
                'ru' => $this->generateURL('bio_index', ['language_prefix' => 'ru', 'bio_index' => '']),
                'en' => $this->generateURL('bio_index', ['language_prefix' => 'en', 'bio_index' => '']),
            ],
            'year'           => $year,
            'pull_requests'  => $pull_requests,
            'comeback'       => $this->container['localization']->get('comeback_link_home', $language_prefix),
            'requests_title' => $this->container['localization']->get('requests_title', $language_prefix),
        ];

        $data_filters = [
            'styles'  => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
            'content' => Template::FILTER_XSS,
        ];

        return $this->render('frontend/frontend', $data, Template::FILTER_ESCAPE, $data_filters);
    }
}
