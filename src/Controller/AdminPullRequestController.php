<?php

namespace Application\Controller;

use Application\Model\Page;
use Application\Model\PullRequest;
use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Template;

/**
 * AdminPullRequestController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class AdminPullRequestController extends BackendController
{
    /**
     * @return Response
     */
    public function listItems()
    {
        $page_model         = new Page($this->getDatabase());
        $pull_request_model = new PullRequest($this->getDatabase());

        $data = [
            'styles'        => $this->container['assets.dispatcher']->getAssets('backend', $this->getStyles(), true),
            'scripts'       => $this->container['assets.dispatcher']->getAssets('backend', $this->getScripts(), true),
            'page'          => $page_model->getPage('admin/pull_request/list', 'ru'),
            'pull_requests' => $pull_request_model->selectPullRequests(),
            'subtemplates'  => ['content' => 'backend/content/pull_requests/pull_requests-list'],
            'menu_links'    => $this->getMenuLinks(),
            'links'         => [
                'edit'   => $this->container['auth.user']->getAccessByRoute('admin_pull_request_edit'),
            ]
        ];
        $data_filters = [
            'styles'  => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
        ];

        return $this->render('backend/backend', $data, Template::FILTER_ESCAPE, $data_filters);
    }

    /**
     * @param integer $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $page_model         = new Page($this->getDatabase());
        $pull_request_model = new PullRequest($this->getDatabase());

        if ($this->isAjax()) {
            return $this->getValidation('PullRequestForm');
        }

        $pull_request = $pull_request_model->get($id);
        if (!$pull_request) {
            return $this->forward('admin_error', ['code' => ErrorController::ERROR_INVALID_DATA]);
        }

        if ($this->isMethod('post')) {
            if ($this->validate('PullRequestForm')) {
                return $this->forward('admin_error', ['code' => ErrorController::ERROR_INVALID_DATA]);
            }

            $pull_request_model->updatePullRequest(
                $pull_request['repository'],
                $pull_request['number'],
                $pull_request['body'],
                $pull_request['title'],
                trim($this->container['request']->request->get('status')),
                $pull_request['commits'],
                $pull_request['additions'],
                $pull_request['deletions'],
                $pull_request['files'],
                $pull_request['create_date']
            );
            return $this->forward('admin_pull_request_list');
        }

        $data = [
            'styles'       => $this->container['assets.dispatcher']->getAssets('backend', $this->getStyles(), true),
            'scripts'      => $this->container['assets.dispatcher']->getAssets('backend', $this->getScripts(), true),
            'page'         => $page_model->getPage('admin/project/edit', 'ru'),
            'subtemplates' => ['content' => 'backend/content/pull_requests/pull_requests-edit'],
            'values'       => $pull_request,
            'menu_links'   => $this->getMenuLinks(),
        ];
        $data_filters = [
            'styles'  => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
        ];
        return $this->render('backend/backend', $data, Template::FILTER_ESCAPE, $data_filters);
    }
}
