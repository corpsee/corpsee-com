<?php

namespace Application\Controller;

use Application\Model\Page;
use Application\Model\Gallery;
use Application\Model\Tag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Nameless\Core\Template;

/**
 * AdminGalleryController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class AdminGalleryController extends BackendController
{
    /**
     * @return Response
     */
    public function listItems()
    {
        $page_model    = new Page($this->getDatabase());
        $gallery_model = new Gallery($this->getDatabase());
        $tag_model     = new Tag($this->getDatabase());

        $data = [
            'styles'       => $this->container['assets.dispatcher']->getAssets('backend', $this->getStyles(), true),
            'scripts'      => $this->container['assets.dispatcher']->getAssets('backend', $this->getScripts(), true),
            'page'         => $page_model->getPage('admin/gallery/list', 'ru'),
            'subtemplates' => ['content' => 'backend/content/gallery/gallery-list'],
            'pictures'     => $gallery_model->selectAllPicsWithTags($tag_model),
            'menu_links'   => $this->getMenuLinks(),
            'links'        => [
                'add'       => $this->container['auth.user']->getAccessByRoute('admin_gallery_add'),
                'delete'    => $this->container['auth.user']->getAccessByRoute('admin_gallery_delete'),
                'edit'      => $this->container['auth.user']->getAccessByRoute('admin_gallery_edit'),
                'editimage' => $this->container['auth.user']->getAccessByRoute('admin_gallery_editimage'),
                'crop'      => $this->container['auth.user']->getAccessByRoute('admin_gallery_crop'),
            ]
        ];
        $data_filters = [
            'styles'  => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
        ];

        return $this->render('backend/backend', $data, Template::FILTER_ESCAPE, $data_filters);
    }

    /**
     * @return RedirectResponse|Response
     */
    public function add()
    {
        $page_model    = new Page($this->getDatabase());
        $gallery_model = new Gallery($this->getDatabase());
        $tag_model     = new Tag($this->getDatabase());

        if ($this->isAjax()) {
            return $this->getValidation('GalleryForm');
        }

        if ($this->isMethod('POST')) {
            if ($this->validate('GalleryForm')) {
                return $this->forward('admin_error', ['code' => ErrorController::ERROR_INVALID_DATA]);
            }

            $file = $this->getFiles('file');
            $filename       = explode('.', $file->getClientOriginalName());
            $filename_clear = standardizeFilename($filename[0]);
            $fileinfo       = getimagesize($file->getPathName());

            if ($fileinfo['mime'] == 'image/jpeg' || 'image/png' || 'image/gif') {
                $gallery_model->addPicture(
                    $tag_model,
                    $this->container['request']->request->get('title'),
                    $file->getPathName(),
                    $filename_clear,
                    $this->container['request']->request->get('description'),
                    $this->container['request']->request->get('tags'),
                    $this->container['request']->request->get('create_date'),
                    $fileinfo['mime']
                );
                return $this->redirect($this->generateURL('admin_gallery_crop', ['image' => $filename_clear]));
            } else {
                return $this->forward('admin_error', ['code' => ErrorController::ERROR_INVALID_IMAGE_TYPE]);
            }
        }

        $data = [
            'styles'       => $this->container['assets.dispatcher']->getAssets('backend', $this->getStyles(), true),
            'scripts'      => $this->container['assets.dispatcher']->getAssets('backend', $this->getScripts(), true),
            'page'         => $page_model->getPage('admin/gallery/add', 'ru'),
            'subtemplates' => ['content' => 'backend/content/gallery/gallery-add'],
            'tags'         => $tag_model->selectAllTagsInString(),
            'menu_links'   => $this->getMenuLinks(),
        ];
        $data_filters = [
            'styles'  => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
        ];
        return $this->render('backend/backend', $data, Template::FILTER_ESCAPE, $data_filters);
    }

    /**
     * @param string $image
     *
     * @return RedirectResponse|Response
     */
    public function crop($image)
    {
        $page_model = new Page($this->getDatabase());
        $gallery_model = new Gallery($this->getDatabase());

        if ($this->isMethod('POST')) {
            $gallery_model->cropPicture(
                $this->getPost('w'),
                $this->getPost('h'),
                $this->getPost('x'),
                $this->getPost('y'),
                $image
            );
            return $this->redirect($this->generateURL('admin_gallery_result', ['image' => $image]));
        }

        $source_img = imagecreatefromjpeg(FILE_PATH . 'pictures/x/' . $image . '.jpg');

        $data = [
            'styles'       => $this->container['assets.dispatcher']->getAssets('backend', $this->getStyles(), true),
            'scripts'      => $this->container['assets.dispatcher']->getAssets('backend', $this->getScripts(), true),
            'page'         => $page_model->getPage('admin/gallery/crop', 'ru'),
            'subtemplates' => ['content' => 'backend/content/gallery/gallery-crop'],
            'image'        => [
                'image'  => $image,
                'width'  => imagesx($source_img),
                'height' => imagesy($source_img)
            ],
            'menu_links' => $this->getMenuLinks(),
        ];
        $data_filters = [
            'styles'  => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
        ];
        return $this->render('backend/backend', $data, Template::FILTER_ESCAPE, $data_filters);
    }

    /**
     * @param string $image
     *
     * @return Response
     */
    public function result($image)
    {
        $page_model = new Page($this->getDatabase());

        $data = [
            'styles'       => $this->container['assets.dispatcher']->getAssets('backend', $this->getStyles(), true),
            'scripts'      => $this->container['assets.dispatcher']->getAssets('backend', $this->getScripts(), true),
            'page'         => $page_model->getPage('admin/gallery/result', 'ru'),
            'subtemplates' => ['content' => 'backend/content/gallery/gallery-result'],
            'image'        => ['min' => $image . '-min', 'gray' => $image . '-gray'],
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
        $gallery_model = new Gallery($this->getDatabase());
        $tag_model     = new Tag($this->getDatabase());

        if ($this->isAjax()) {
            return $this->getValidation('GalleryForm');
        }

        if ($this->isMethod('POST')) {
            if ($this->validate('GalleryForm')) {
                return $this->forward('admin_error', ['code' => ErrorController::ERROR_INVALID_DATA]);
            }

            $gallery_model->UpdatePicture(
                $tag_model,
                $id,
                $this->getPost('title'),
                $this->getPost('description'),
                $this->getPost('tags'),
                $this->getPost('create_date')
            );
            return $this->forward('admin_gallery_list');
        }

        $data = [
            'styles'       => $this->container['assets.dispatcher']->getAssets('backend', $this->getStyles(), true),
            'scripts'      => $this->container['assets.dispatcher']->getAssets('backend', $this->getScripts(), true),
            'page'         => $page_model->getPage('admin/gallery/edit', 'ru'),
            'subtemplates' => ['content' => 'backend/content/gallery/gallery-edit'],
            'tags'         => $tag_model->selectAllTagsInString(),
            'menu_links'   => $this->getMenuLinks(),
        ];

        $picture = $gallery_model->selectPicByIDWithTagsInString($id, $tag_model);
        $image = FILE_PATH_URL . 'pictures/x/' . $picture['image'] . '.jpg';

        $data['values'] = [
            'title'       => $picture['title'],
            'description' => $picture['description'],
            'tags'        => $picture['tags'],
            'create_date' => $picture['create_date'],
            'filename'    => $image,
        ];
        $data['image'] = ['id' => $id];

        $data_filters = [
            'styles'  => Template::FILTER_RAW,
            'scripts' => Template::FILTER_RAW,
        ];
        return $this->render('backend/backend', $data, Template::FILTER_ESCAPE, $data_filters);
    }

    /**
     * @param integer $id
     *
     * @return RedirectResponse|Response
     */
    public function editImage($id)
    {
        $page_model = new Page($this->getDatabase());
        $gallery_model = new Gallery($this->getDatabase());

        if ($this->isMethod('POST')) {
            $file = $this->getFiles('file');

            $filename = explode('.', $file->getClientOriginalName());
            $filename_clear = standardizeFilename($filename[0]);
            $fileinfo = getimagesize($file->getPathName());

            try {
                $gallery_model->updatePictureImage(
                    $id,
                    $file->getPathName(),
                    $filename_clear,
                    $fileinfo['mime']
                );
                return $this->redirect($this->generateURL('admin_gallery_crop', ['image' => $filename_clear]));
            } catch (\RuntimeException $e) {
                return $this->forward('admin_error', ['code' => ErrorController::ERROR_INVALID_IMAGE_TYPE]);
            }
        }

        $data = [
            'styles'       => $this->container['assets.dispatcher']->getAssets('backend', $this->getStyles(), true),
            'scripts'      => $this->container['assets.dispatcher']->getAssets('backend', $this->getScripts(), true),
            'page'         => $page_model->getPage('admin/gallery/editimage', 'ru'),
            'subtemplates' => ['content' => 'backend/content/gallery/gallery-editimage'],
            'image'        => ['id' => $id],
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
        $gallery_model = new Gallery($this->getDatabase());

        $gallery_model->deletePicture($id);
        return $this->forward('admin_gallery_list');
    }
}
