<?php

namespace Application\Controller;

use Application\Model\Page;
use Application\Model\Gallery;
use Application\Model\Tag;
use Nameless\Modules\Auto\Auto;
use Symfony\Component\HttpFoundation\Response;

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
		$tag_model     = new Tag($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());

		$data = array
		(
			'styles'       => $this->getStyles(),
			'scripts'      => $this->getScripts(),
			'page'         => $page_model->getPage('admin/tag/list'),
			'subtemplates' => array('content' => 'backend' . DS . 'tags' . DS . 'tags_list'),
			'tags'         => $tag_model->selectAllTagsWithPicInString($gallery_model),
			'links'        => array
			(
				'add'       => $this->container['auto.user']->getAccessByRoute('admin_tag_add'),
				'delete'    => $this->container['auto.user']->getAccessByRoute('admin_tag_delete'),
				'edit'      => $this->container['auto.user']->getAccessByRoute('admin_tag_edit'),
			)
		);
		//echo '<pre>'; var_dump($data); echo '</pre>';
		return $this->render('back_page', $data);
	}

	/**
	 * @return Response
	 */
	public function add ()
	{
		$page_model    = new Page($this->getDatabase());
		$tag_model     = new Tag($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());

		// ajax-валидация (клиентская)
		if ($this->isAjax())
		{
			return $this->getValidation('TagForm');
		}

		if ($this->isMethod('POST'))
		{
			if ($this->container['validation.validator']->validate('TagForm'))
			{
				return $this->forward('admin_error', array('code' => 4));
			}

			if (!$tag_model->addTag($gallery_model, $this->getPost('tag'), $this->getPost('pictures')))
			{
				return $this->forward('admin_error', array('code' => 6));
			}
			return $this->forward('admin_tag_list');
		}

		$data = array
		(
			'styles'       => $this->getStyles(),
			'scripts'      => $this->getScripts(),
			'page'         => $page_model->getPage('admin/tag/add'),
			'subtemplates' => array('content' => 'backend' . DS . 'tags' . DS . 'tags_add'),
			'pictures'     => $gallery_model->selectAllPics()
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
		$tag_model     = new Tag($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());

		// ajax-валидация (клиентская)
		if ($this->isAjax())
		{
			return $this->getValidation('TagForm'); exit();
		}

		if ($this->isMethod('post'))
		{
			if ($this->container['validation.validator']->validate('TagForm'))
			{
				return $this->forward('admin_error', array('code' => 4));
			}

			$tag_model->UpdateTag
			(
				$gallery_model,
				(int)$id,
				$this->getPost('tag'),
				$this->getPost('pictures')
			);
			return $this->forward('admin_tag_list');
		}

		$tag      = $tag_model->selectTagByID($id);
		$pictures = $gallery_model->selectPicsByTag($tag['tag']);

		$data = array
		(
			'styles'       => $this->getStyles(),
			'scripts'      => $this->getScripts(),
			'page'         => $page_model->getPage('admin/tag/edit'),
			'subtemplates' => array('content' => 'backend' . DS . 'tags' . DS . 'tags_edit'),
			'values'       => array
			(
				'tag'      => $tag['tag'],
				'pictures' => $pictures
			),
			'pictures'     => $gallery_model->selectAllPics(),
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
		$tag_model     = new Tag($this->getDatabase());
		$gallery_model = new Gallery($this->getDatabase());

		$tag_model->deleteTag($gallery_model, $id);
		return $this->forward('admin_tag_list');
	}
}
