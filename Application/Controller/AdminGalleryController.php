<?php

namespace Application\Controller;

use Application\Model\Page;
use Application\Model\Gallery;
use Application\Model\Tag;
use Nameless\Modules\Auto\Auto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * GalleryController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class AdminGalleryController extends BackendController
{
	/**
	 * @return Response
	 */
	public function listItems ()
	{
		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);
		$tag_model     = new Tag($this->getDatabase(), $this->container['timezone']);

		$data = array
		(
			'styles'       => $this->getStyles(),
			'scripts'      => $this->getScripts(),
			'page'         => $page_model->getPage('admin/gallery/list'),
			'subtemplates' => array('content' => 'backend' . DS . 'gallery' . DS . 'gallery_list'),
			'pictures'     => $gallery_model->selectAllPicsWithTags($tag_model),
			'links'        => array
			(
				'add'       => $this->container['auto.user']->getAccessByRoute('admin_gallery_add'),
				'delete'    => $this->container['auto.user']->getAccessByRoute('admin_gallery_delete'),
				'edit'      => $this->container['auto.user']->getAccessByRoute('admin_gallery_edit'),
				'editimage' => $this->container['auto.user']->getAccessByRoute('admin_gallery_editimage'),
				'crop'      => $this->container['auto.user']->getAccessByRoute('admin_gallery_crop'),
			)
		);
		return $this->render('back_page', $data);
	}

	/**
	 * @return RedirectResponse|Response
	 */
	public function add ()
	{
		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);
		$tag_model     = new Tag($this->getDatabase(), $this->container['timezone']);

		// ajax-валидация (клиентская)
		if ($this->isAjax())
		{
			//print_r($this->getValidation('GalleryForm')); exit();
			return $this->getValidation('GalleryForm');
		}

		if ($this->isMethod('POST'))
		{
			// валидация
			if ($this->container['validation.validator']->validate('GalleryForm'))
			{
				return $this->forward('admin_error', array('code' => 4));
			}

			$file = $this->getFiles('file');
			// TODO: FILES? +некрасивый код с типами
			$filename       = explode('.', $file->getClientOriginalName());
			$filename_clear = standardizeFilename($filename[0]);
			$fileinfo       = getimagesize($file->getPathName());

			if ($fileinfo['mime'] == 'image/jpeg' || 'image/png' || 'image/gif')
			{
				$gallery_model->addPicture
				(
					$tag_model,
					$this->container['request']->request->get('title'),
					$file->getPathName(),
					$filename_clear,
					$this->container['request']->request->get('description'),
					$this->container['request']->request->get('tags'),
					$this->container['request']->request->get('create_date'),
					$fileinfo['mime']
				);
				return $this->redirect('/admin/gallery/crop/' . $filename_clear);
			}
			else
			{
				return $this->forward('admin_error', array('code' => 3));
			}
		}

		$data = array
		(
			'styles'       => $this->getStyles(),
			'scripts'      => $this->getScripts(),
			'page'         => $page_model->getPage('admin/gallery/add'),
			'subtemplates' => array('content' => 'backend' . DS . 'gallery' . DS . 'gallery_add'),
			'tags'         => $tag_model->selectAllTagsInString(),
		);
		return $this->render('back_page', $data);
	}

	/**
	 * @param string $image
	 *
	 * @return RedirectResponse|Response
	 */
	public function crop ($image)
	{
		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);

		if ($this->isMethod('POST'))
		{
			//print_r($this->getPost()); exit();
			$gallery_model->cropPicture
			(
				$this->getPost('w'),
				$this->getPost('h'),
				$this->getPost('x'),
				$this->getPost('y'),
				$image
			);
			return $this->redirect('/admin/gallery/result/' . $image);
		}

		$source_img = imagecreatefromjpeg(FILE_PATH . 'pictures/x/' . $image . '.jpg');

		$data = array
		(
			'styles'       => $this->getStyles(),
			'scripts'      => $this->getScripts(),
			'page'         => $page_model->getPage('admin/gallery/crop'),
			'subtemplates' => array('content' => 'backend' . DS . 'gallery' . DS . 'gallery_crop'),
			'image'        => array
			(
				'image' => $image,
				'width'    => imagesx($source_img),
				'height'   => imagesy($source_img)
			)
		);
		return $this->render('back_page', $data);
	}

	/**
	 * @param string $image
	 *
	 * @return Response
	 */
	public function result ($image)
	{
		$page_model = new Page($this->getDatabase());

		$data = array
		(
			'styles'       => $this->getStyles(),
			'scripts'      => $this->getScripts(),
			'page'         => $page_model->getPage('admin/gallery/result'),
			'subtemplates' => array('content' => 'backend' . DS . 'gallery' . DS . 'gallery_result'),
			'image'        => array('min'  => $image . '-min', 'gray' => $image . '-gray')
		);
		return $this->render('back_page', $data);
	}

	/**
	 * @param integer $id
	 *
	 * @return Response
	 */
	public function edit ($id)
	{
		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);
		$tag_model     = new Tag($this->getDatabase(), $this->container['timezone']);

		// ajax-валидация (клиентская)
		if ($this->isAjax())
		{
			return $this->getValidation('GalleryForm');
		}

		if ($this->isMethod('POST'))
		{
			//echo '<pre>'; print_r($_POST); exit();
			if ($this->container['validation.validator']->validate('GalleryForm'))
			{
				return $this->forward('admin_error', array('code' => 4));
			}

			$gallery_model->UpdatePicture
			(
				$tag_model,
				$id,
				$this->getPost('title'),
				$this->getPost('description'),
				$this->getPost('tags'),
				$this->getPost('create_date')
			);
			return $this->forward('admin_gallery_list');
		}

		$data = array
		(
			'styles'       => $this->getStyles(),
			'scripts'      => $this->getScripts(),
			'page'         => $page_model->getPage('admin/gallery/edit'),
			'subtemplates' => array('content' => 'backend' . DS . 'gallery' . DS . 'gallery_edit'),
			'tags'         => $tag_model->selectAllTagsInString()
		);

		$picture = $gallery_model->selectPicByIDWithTagsInString($id, $tag_model);
		$image = FILE_PATH_URL . 'pictures/x/' . $picture['image'] . '.jpg';

		$data['values'] = array
		(
			'title'       => $picture['title'],
			'description' => $picture['description'],
			'tags'        => $picture['tags'],
			'create_date' => $picture['create_date'],
			'filename'    => $image,
		);
		$data['image']  = array('id' => $id);

		return $this->render('back_page', $data);
	}

	/**
	 * @param integer $id
	 *
	 * @return RedirectResponse|Response
	 */
	public function editImage ($id)
	{
		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);

		if ($this->isMethod('POST'))
		{
			$file = $this->getFiles('file');

			$filename       = explode('.', $file->getClientOriginalName());
			$filename_clear = standardizeFilename($filename[0]);
			$fileinfo       = getimagesize($file->getPathName());

			try
			{
				$gallery_model->updatePictureImage
				(
					$id,
					$file->getPathName(),
					$filename_clear,
					$fileinfo['mime']
				);
				return $this->redirect('/admin/gallery/crop/' . $filename_clear);
			}
			catch (\RuntimeException $e)
			{
				return $this->forward('admin_error', array('code' => 3));
			}
		}

		$data = array
		(
			'styles'       => $this->getStyles(),
			'scripts'      => $this->getScripts(),
			'page'         => $page_model->getPage('admin/gallery/editimage'),
			'subtemplates' => array('content' => 'backend' . DS . 'gallery' . DS . 'gallery_editimage'),
			'image'        => array('id' => $id),
		);
		return $this->render('back_page', $data);
	}

	/**
	 * @param integer $id
	 *
	 * @return Response
	 */
	public function delete ($id)
	{
		$gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);

		$gallery_model->deletePicture($id);
		return $this->forward('admin_gallery_list');
	}
}
