<?php

namespace Application\Model;

use Application\Model\Gallery;

/**
 * Tag model class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Tag extends DatetimeModel
{
	/**
	 * @param array $data
	 *
	 * @return array
	 */
	// Форматирует дату при выборке данных из базы
	private function formatDate (array $data)
	{
		$post_date = \DateTime::createFromFormat('U', $data['post_date']);
		$post_date->setTimezone($this->timezone);
		$data['post_date'] = $post_date->format('d.m.Y');

		$modify_date = \DateTime::createFromFormat('U', $data['modify_date']);
		$modify_date->setTimezone($this->timezone);
		$data['modify_date'] = $modify_date->format('d.m.Y');

		return $data;
	}

	/**
	 * @param integer $id
	 *
	 * @return array
	 */
	// id, tag
	public function selectTagByID ($id)
	{
		return $this->database->selectOne("SELECT * FROM `tbl_tags` WHERE `id` = ?", array($id));
	}

	/**
	 * @param integer $id
	 * @param Gallery $gallery_model
	 *
	 * @return array
	 */
	// id, tag, class
	public function selectTagByIDWithClass ($id, Gallery $gallery_model)
	{
		$data = $this->selectTagByID($id);

		$count = $gallery_model->countPicByTag($data['tag']);
		$data['class'] = $this->tagClass($count);

		return $data;
	}

	/**
	 * @return array
	 */
	// array: id, tag
	public function selectAllTags ()
	{
		return $data = $this->database->selectMany("SELECT * FROM `tbl_tags`");
	}

	/**
	 * @param Gallery $gallery_model
	 *
	 * @return array
	 */
	// array: id, tag, class
	public function selectAllTagsWithClass (Gallery $gallery_model)
	{
		$data = $this->selectAllTags();

		foreach ($data as &$row)
		{
			$count = $gallery_model->countPicByTag($row['tag']);
			$row['class'] = $this->tagClass($count);
		}
		unset($row);

		return $data;
	}

	/**
	 * @param Gallery $gallery_model
	 *
	 * @return array
	 */
	// array: id, tag, class, one string of pictures
	public function selectAllTagsWithPicInString (Gallery $gallery_model)
	{
		$data = $this->selectAllTagsWithClass($gallery_model);

		foreach ($data as &$row)
		{
			$row['pictures'] = $gallery_model->selectPicsInStringByTag($row['tag']);
			$row = $this->formatDate($row);
		}
		unset($row);

		return $data;
	}

	/**
	 * @param Gallery $gallery_model
	 *
	 * @return array
	 */
	// array: id, tag, pictures
	public function selectAllTagsWithPics (Gallery $gallery_model)
	{
		$data = $this->selectAllTags();

		foreach ($data as &$row)
		{
			$row['pictures'] = $gallery_model->selectPicsByTag($row['tag']);
		}
		unset($row);

		return $data;
	}

	/**
	 * @param $picture_id
	 *
	 * @return array
	 */
	// array: id, tag
	public function selectTagsByPicID ($picture_id)
	{
		return $this->database->selectMany
		("
			SELECT t.id, t.tag FROM `tbl_pictures_tags` AS `pt`
			LEFT JOIN `tbl_tags` AS `t`
			ON pt.tags_id = t.id
			WHERE pt.pictures_id = ?
		", array($picture_id));
	}

	/**
	 * @return string
	 */
	// one string of tags
	public function selectAllTagsInString ()
	{
		$data = $this->selectAllTags();

		$tags = array();
		foreach ($data as $item)
		{
			$tags[] = $item['tag'];
		}

		return arrayToString($tags);
	}

	/**
	 * @param integer $picture_id
	 *
	 * @return string
	 */
	// one string of tags by picture id
	public function selectTagsInStringByPicID ($picture_id)
	{
		$data = $this->selectTagsByPicID($picture_id);

		$tags = array();
		foreach ($data as $item)
		{
			$tags[] = $item['tag'];
		}

		return arrayToString($tags);
	}

	/**
	 * @param Gallery $gallery_model
	 * @param string  $tag
	 * @param array   $pictures
	 *
	 * @return bool
	 * @throws \LogicException
	 */
	public function addTag (Gallery $gallery_model, $tag, $pictures)
	{
		$data = $this->database->selectOne("SELECT COUNT(*) AS `count` FROM `tbl_tags` WHERE `tag` = ?", array($tag));

		if ($data['count'] == 0)
		{
			$this->database->beginTransaction();

				$tag = standardizeString(trim($tag));
				$tag_id = $this->database->execute("INSERT INTO `tbl_tags` (`tag`, `post_date`, `modify_date`) VALUES (?, ?, ?)", array($tag, time(), time()));
				$this->setLastModifyDate();

				foreach ($pictures as $picture)
				{
					$pic = $this->database->selectOne("SELECT `id` FROM `tbl_pictures` WHERE `title` = ?", array($picture));

					if ($pic)
					{
						$this->database->execute("INSERT INTO `tbl_pictures_tags` (`pictures_id`, `tags_id`) VALUES (?, ?)", array($pic['id'], $tag_id));
					}
				}

				if ($pictures)
				{
					$gallery_model->setLastModifyDate();
				}

			$this->database->commit();
			return TRUE;
		}
		else
		{
			throw new \LogicException('Tag already exist', 1);
		}
	}

	/**
	 * @param integer $tag_id
	 * @param array   $pictures
	 */
	public function UpdateTag ($tag_id, $pictures)
	{
		$this->database->beginTransaction();

			$this->database->execute("UPDATE `tbl_tags` SET `modify_date` = ? WHERE `id` = ?", array(time(),$tag_id));
			$this->database->execute("DELETE FROM `tbl_pictures_tags` WHERE `tags_id` = ?", array($tag_id));
			$this->setLastModifyDate();

			foreach ($pictures as $picture)
			{
				$pic = $this->database->selectOne("SELECT `id` FROM `tbl_pictures` WHERE `title` = ?", array($picture));

				if ($pic)
				{
					$this->database->execute("INSERT INTO `tbl_pictures_tags` (`pictures_id`, `tags_id`) VALUES (?, ?)", array($pic['id'], $tag_id));
				}
			}

		$this->database->commit();
	}

	/**
	 * @param Gallery $gallery_model
	 * @param integer $id
	 */
	public function deleteTag (Gallery $gallery_model, $id)
	{
		$this->database->beginTransaction();

			$this->database->execute("DELETE FROM `tbl_tags` WHERE `id` = ?", array($id));
			$this->setLastModifyDate();

			$deleted_pic = $this->database->execute("DELETE FROM `tbl_pictures_tags` WHERE `tags_id` = ?", array($id));

			if ((int)$deleted_pic > 0)
			{
				$gallery_model->setLastModifyDate();
			}

		$this->database->commit();
	}

	/**
	 * @param integer $count
	 *
	 * @return string
	 */
	public function tagClass ($count)
	{
		switch ($count)
		{
			case 0:
				$result = 'tag0'; break;
			case 1: case 2:
				$result = 'tag1'; break;
			case 3: case 4:
				$result = 'tag2'; break;
			case 5: case 6:
				$result = 'tag3'; break;
			case 7: case 8:
				$result = 'tag4'; break;
			case 9: case 10:
				$result = 'tag5'; break;
			default:
				$result = 'tag6'; break;
		}
		return $result;
	}

	/**
	 * @return integer
	 */
	// Устанавливаем время последнего изменения таблицы
	public function setLastModifyDate ()
	{
		return $this->database->execute("UPDATE `tbl_last_modify` SET `modify_date` = ? WHERE `table` = 'tbl_pictures'", array(time()));
	}

	/**
	 * @return \DateTime
	 */
	// Получаем время последнего изменения таблицы
	public function getLastModifyDate ()
	{
		$data = $this->database->selectOne("SELECT `modify_date` FROM `tbl_last_modify` WHERE `table` = 'tbl_pictures'");

		$modify_date = \DateTime::createFromFormat('U', $data['modify_date']);
		$modify_date->setTimezone($this->timezone);
		return $modify_date;
	}
}