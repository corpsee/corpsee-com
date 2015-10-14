<?php

namespace Application\Controller;

use Application\Model\Page;
use Application\Model\Project;
use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Template;

/**
 * AdminProjectController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class AdminProjectController extends BackendController
{
    /**
     * @return Response
     */
    public function listItems()
    {
        $page_model    = new Page($this->getDatabase());
        $project_model = new Project($this->getDatabase());

        $data = [
            'styles'       => $this->container['assets.dispatcher']->getAssets('backend', $this->getStyles(), true),
            'scripts'      => $this->container['assets.dispatcher']->getAssets('backend', $this->getScripts(), true),
            'page'         => $page_model->getPage('admin/project/list', 'ru'),
            'projects'     => $project_model->getAll(),
            'subtemplates' => ['content' => 'backend/content/projects/projects-list'],
            'menu_links'   => $this->getMenuLinks(),
            'links'        => [
                'add'    => $this->container['auth.user']->getAccessByRoute('admin_project_add'),
                'delete' => $this->container['auth.user']->getAccessByRoute('admin_project_delete'),
                'edit'   => $this->container['auth.user']->getAccessByRoute('admin_project_edit'),
            ]
        ];
        $data_filters = [
            'styles'  => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
        ];

        return $this->render('backend/backend', $data, Template::FILTER_ESCAPE, $data_filters);
    }

    /**
     * @return Response
     */
    public function add()
    {
        $page_model    = new Page($this->getDatabase());
        $project_model = new Project($this->getDatabase());

        if ($this->isAjax()) {
            return $this->getValidation('ProjectForm');
        }

        if ($this->isMethod('POST')) {
            if ($this->validate('ProjectForm')) {
                return $this->forward('admin_error', ['code' => ErrorController::ERROR_INVALID_DATA]);
            }

            $file     = $this->getFiles('file');
            $filename = null;
            $fileinfo = null;
            if ($file) {
                $fileinfo = getimagesize($file->getPathName());
                $fileinfo = $fileinfo['mime'];
                $filename = $file->getPathName();
            }

            try {
                if ($fileinfo['mime'] == 'image/jpeg' || 'image/png' || 'image/gif') {
                    $project_model->create(
                        $this->container['request']->request->get('title'),
                        $this->container['request']->request->get('description'),
                        $this->container['request']->request->get('link'),
                        $this->container['request']->request->get('role'),
                        $filename,
                        $fileinfo
                    );
                    return $this->forward('admin_project_list');
                } else {
                    return $this->forward('admin_error', ['code' => ErrorController::ERROR_INVALID_IMAGE_TYPE]);
                }
            } catch (\LogicException $e) {
                return $this->forward('admin_error', ['code' => ErrorController::ERROR_PROJECT_ALREADY_EXISTS]);
            }
        }

        $data = [
            'styles'       => $this->container['assets.dispatcher']->getAssets('backend', $this->getStyles(), true),
            'scripts'      => $this->container['assets.dispatcher']->getAssets('backend', $this->getScripts(), true),
            'page'         => $page_model->getPage('admin/project/add', 'ru'),
            'subtemplates' => ['content' => 'backend/content/projects/projects-add'],
            'menu_links'   => $this->getMenuLinks(),
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
        $page_model    = new Page($this->getDatabase());
        $project_model = new Project($this->getDatabase());

        if ($this->isAjax()) {
            return $this->getValidation('TagForm');
        }

        if ($this->isMethod('post')) {
            if ($this->validate('TagForm')) {
                return $this->forward('admin_error', ['code' => ErrorController::ERROR_INVALID_DATA]);
            }

            $file     = $this->getFiles('file');
            $filename = null;
            $fileinfo = null;
            if ($file) {
                $fileinfo = getimagesize($file->getPathName());
                $fileinfo = $fileinfo['mime'];
                $filename = $file->getPathName();
            }

            if ($fileinfo['mime'] == 'image/jpeg' || 'image/png' || 'image/gif') {
                $project_model->update(
                    $id,
                    $this->container['request']->request->get('title'),
                    $this->container['request']->request->get('description'),
                    $this->container['request']->request->get('link'),
                    $this->container['request']->request->get('role'),
                    $filename,
                    $fileinfo
                );
                return $this->forward('admin_project_list');
            } else {
                return $this->forward('admin_error', ['code' => ErrorController::ERROR_INVALID_IMAGE_TYPE]);
            }
        }

        $data = [
            'styles'       => $this->container['assets.dispatcher']->getAssets('backend', $this->getStyles(), true),
            'scripts'      => $this->container['assets.dispatcher']->getAssets('backend', $this->getScripts(), true),
            'page'         => $page_model->getPage('admin/project/edit', 'ru'),
            'subtemplates' => ['content' => 'backend/content/projects/projects-edit'],
            'values'       => $project_model->get($id),
            'menu_links'   => $this->getMenuLinks(),
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
    public function delete($id)
    {
        $project_model = new Project($this->getDatabase());

        $project_model->delete($id);
        return $this->forward('admin_project_list');
    }
}
