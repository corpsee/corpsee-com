<?php

namespace Application\Controller;

use Application\Model\Page;
use Application\Model\Gallery;
use Application\Model\Tag;
use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Template;

/**
 * TagController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class AdminTagController extends BackendController
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
			'styles'       => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles(), TRUE),
			'scripts'      => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts(), TRUE),
			'page'         => $page_model->getPage('admin/tag/list', 'ru'),
			'subtemplates' => array('content' => 'backend/content/tags/tags-list'),
			'tags'         => $tag_model->selectAllTagsWithPicInString($gallery_model),
			'menu_links'   => $this->getMenuLinks(),
			'links'        => array
			(
				'add'       => $this->container['auto.user']->getAccessByRoute('admin_tag_add'),
				'delete'    => $this->container['auto.user']->getAccessByRoute('admin_tag_delete'),
				'edit'      => $this->container['auto.user']->getAccessByRoute('admin_tag_edit'),
			)
		);
		$data_filters = array
		(
			'styles'      => Template::FILTER_RAW,
			'scripts'     => Template::FILTER_RAW,
		);
		return $this->render('backend/backend', $data, Template::FILTER_ESCAPE, $data_filters);
	}

	/**
	 * @return Response
	 */
	public function add ()
	{
		$page_model    = new Page($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);
		$tag_model     = new Tag($this->getDatabase(), $this->container['timezone']);

		// ajax-валидация (клиентская)
		if ($this->isAjax())
		{
			return $this->getValidation('TagForm');
		}

		if ($this->isMethod('POST'))
		{
			if ($this->container['validation.validator']->validate('TagForm'))
			{
				return $this->forward('admin_error', array('code' => ErrorController::ERROR_INVALID_DATA));
			}

			try
			{
				$tag_model->addTag($gallery_model, $this->getPost('tag'), $this->getPost('pictures'));
			}
			catch (\LogicException $e)
			{
				return $this->forward('admin_error', array('code' => ErrorController::ERROR_TAG_ALREADY_EXISTS));
			}
			return $this->forward('admin_tag_list');
		}

		$data = array
		(
			'styles'       => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles(), TRUE),
			'scripts'      => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts(), TRUE),
			'page'         => $page_model->getPage('admin/tag/add', 'ru'),
			'subtemplates' => array('content' => 'backend/content/tags/tags-add'),
			'pictures'     => $gallery_model->selectAllPics(),
			'menu_links'   => $this->getMenuLinks(),
		);
		$data_filters = array
		(
			'styles'      => Template::FILTER_RAW,
			'scripts'     => Template::FILTER_RAW,
		);
		return $this->render('backend/backend', $data, Template::FILTER_ESCAPE, $data_filters);
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
			return $this->getValidation('TagForm'); exit();
		}

		if ($this->isMethod('post'))
		{
			if ($this->container['validation.validator']->validate('TagForm'))
			{
				return $this->forward('admin_error', array('code' => ErrorController::ERROR_INVALID_DATA));
			}

			$tag_model->UpdateTag
			(
				(int)$id,
				$this->getPost('pictures')
			);
			return $this->forward('admin_tag_list');
		}

		$tag      = $tag_model->selectTagByID($id);
		$pictures = $gallery_model->selectPicsByTag($tag['tag']);

		$data = array
		(
			'styles'       => $this->container['assets.dispatcher']->getAssets('frontend', $this->getStyles(), TRUE),
			'scripts'      => $this->container['assets.dispatcher']->getAssets('frontend', $this->getScripts(), TRUE),
			'page'         => $page_model->getPage('admin/tag/edit', 'ru'),
			'subtemplates' => array('content' => 'backend/content/tags/tags-edit'),
			'values'       => array
			(
				'tag'      => $tag['tag'],
				'pictures' => $pictures
			),
			'pictures'     => $gallery_model->selectAllPics(),
			'menu_links'   => $this->getMenuLinks(),
		);
		$data_filters = array
		(
			'styles'      => Template::FILTER_RAW,
			'scripts'     => Template::FILTER_RAW,
		);
		return $this->render('backend/backend', $data, Template::FILTER_ESCAPE, $data_filters);
	}

	/**
	 * @param integer $id
	 *
	 * @return Response
	 */
	public function delete ($id)
	{
		$gallery_model = new Gallery($this->getDatabase(), $this->container['timezone']);
		$tag_model     = new Tag($this->getDatabase(), $this->container['timezone']);

		$tag_model->deleteTag($gallery_model, $id);
		return $this->forward('admin_tag_list');
	}
}
