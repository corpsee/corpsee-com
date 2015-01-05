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
     * @param integer $id
     *
     * @return array
     */
    // id, tag
    public function selectTagByID($id)
    {
        return $this->database->selectOne('SELECT * FROM "tags" WHERE "id" = ?', [$id]);
    }

    /**
     * @param integer $id
     * @param Gallery $gallery_model
     *
     * @return array
     */
    // id, tag, class
    public function selectTagByIDWithClass($id, Gallery $gallery_model)
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
    public function selectAllTags()
    {
        return $data = $this->database->selectMany('SELECT * FROM "tags"');
    }

    /**
     * @param Gallery $gallery_model
     *
     * @return array
     */
    // array: id, tag, class
    public function selectAllTagsWithClass(Gallery $gallery_model)
    {
        $data = $this->selectAllTags();

        foreach ($data as &$row) {
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
    public function selectAllTagsWithPicInString(Gallery $gallery_model)
    {
        $data = $this->selectAllTagsWithClass($gallery_model);

        foreach ($data as &$row) {
            $row['pictures'] = $gallery_model->selectPicsInStringByTag($row['tag']);
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
    public function selectAllTagsWithPics(Gallery $gallery_model)
    {
        $data = $this->selectAllTags();

        foreach ($data as &$row) {
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
    public function selectTagsByPicID($picture_id)
    {
        return $this->database->selectMany('
            SELECT t.id, t.tag FROM "pictures_tags" AS "pt"
            LEFT JOIN "tags" AS "t"
            ON pt.tags_id = t.id
            WHERE pt.pictures_id = ?
        ', [$picture_id]);
    }

    /**
     * @return string
     */
    // one string of tags
    public function selectAllTagsInString()
    {
        $data = $this->selectAllTags();

        $tags = [];
        foreach ($data as $item) {
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
    public function selectTagsInStringByPicID($picture_id)
    {
        $data = $this->selectTagsByPicID($picture_id);

        $tags = [];
        foreach ($data as $item) {
            $tags[] = $item['tag'];
        }

        return arrayToString($tags);
    }

    /**
     * @param Gallery $gallery_model
     * @param string $tag
     * @param array $pictures
     *
     * @return bool
     * @throws \LogicException
     */
    public function addTag(Gallery $gallery_model, $tag, $pictures)
    {
        $data = $this->database->selectOne('SELECT COUNT(*) AS "count" FROM "tags" WHERE "tag" = ?', [$tag]);

        if ($data['count'] == 0) {
            $this->database->beginTransaction();

            $tag = standardizeString(trim($tag));
            $tag_id = $this->database->execute(
                'INSERT INTO "tags" ("tag", "post_date", "modify_date") VALUES (?, ?, ?)',
                [$tag, date(POSTGRES), date(POSTGRES)]
            );
            $this->setLastModifyDate();

            foreach ($pictures as $picture) {
                $pic = $this->database->selectOne('SELECT "id" FROM "pictures" WHERE "title" = ?', [$picture]);

                if ($pic) {
                    $this->database->execute(
                        'INSERT INTO "pictures_tags" ("pictures_id", "tags_id") VALUES (?, ?)',
                        [$pic['id'], $tag_id]
                    );
                }
            }

            if ($pictures) {
                $gallery_model->setLastModifyDate();
            }

            $this->database->commit();
            return true;
        } else {
            throw new \LogicException('Tag already exist', 1);
        }
    }

    /**
     * @param integer $tag_id
     * @param array $pictures
     */
    public function UpdateTag($tag_id, $pictures)
    {
        $this->database->beginTransaction();

        $this->database->execute('UPDATE "tags" SET "modify_date" = ? WHERE "id" = ?', [date(POSTGRES), $tag_id]);
        $this->database->execute('DELETE FROM "pictures_tags" WHERE "tags_id" = ?', [$tag_id]);
        $this->setLastModifyDate();

        foreach ($pictures as $picture) {
            $pic = $this->database->selectOne('SELECT "id" FROM "pictures" WHERE "title" = ?', [$picture]);

            if ($pic) {
                $this->database->execute(
                    'INSERT INTO "pictures_tags" ("pictures_id", "tags_id") VALUES (?, ?)',
                    [$pic['id'], $tag_id]
                );
            }
        }

        $this->database->commit();
    }

    /**
     * @param Gallery $gallery_model
     * @param integer $id
     */
    public function deleteTag(Gallery $gallery_model, $id)
    {
        $this->database->beginTransaction();

        $this->database->execute('DELETE FROM "tags" WHERE "id" = ?', [$id]);
        $this->setLastModifyDate();

        $deleted_pic = $this->database->execute('DELETE FROM "pictures_tags" WHERE "tags_id" = ?', [$id]);

        if ((int)$deleted_pic > 0) {
            $gallery_model->setLastModifyDate();
        }

        $this->database->commit();
    }

    /**
     * @param integer $count
     *
     * @return string
     */
    public function tagClass($count)
    {
        switch ($count) {
            case 0:
                $result = 'tag0';
                break;
            case 1:
            case 2:
                $result = 'tag1';
                break;
            case 3:
            case 4:
                $result = 'tag2';
                break;
            case 5:
            case 6:
                $result = 'tag3';
                break;
            case 7:
            case 8:
                $result = 'tag4';
                break;
            case 9:
            case 10:
                $result = 'tag5';
                break;
            default:
                $result = 'tag6';
                break;
        }
        return $result;
    }

    /**
     * @return integer
     */
    // Устанавливаем время последнего изменения таблицы
    public function setLastModifyDate()
    {
        return $this->database->execute(
            'UPDATE "last_modify" SET "modify_date" = ? WHERE "table" = \'tags\'',
            [date(POSTGRES)]
        );
    }

    /**
     * @return \DateTime
     */
    // Получаем время последнего изменения таблицы
    public function getLastModifyDate()
    {
        $data = $this->database->selectOne(
            'SELECT "modify_date" FROM "last_modify" WHERE "table" = \'tags\''
        );

        return \DateTime::createFromFormat(POSTGRES, $data['modify_date']);
    }
}