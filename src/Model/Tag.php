<?php

namespace Application\Model;

use Nameless\Modules\Database\Model;

/**
 * Tag model class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Tag extends Model
{
    /**
     * @param integer $id
     *
     * @return array
     */
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
    public function selectAllTags()
    {
        return $this->database->selectMany('SELECT * FROM "tags"');
    }

    /**
     * @param Gallery $gallery_model
     *
     * @return array
     */
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
    public function selectTagsByPicID($picture_id)
    {
        return $this->database->selectMany('
            SELECT t.id, t.tag FROM "pictures_tags" AS "pt"
            LEFT JOIN "tags" AS "t"
            ON pt.tag_id = t.id
            WHERE pt.picture_id = ?
        ', [$picture_id]);
    }

    /**
     * @return string
     */
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
     */
    public function updateTag(Gallery $gallery_model, $tag, $pictures)
    {
        $data = $this->database->selectOne('SELECT "id" FROM "tags" WHERE "tag" = ? LIMIT 1', [$tag]);

        if (!$data) {
            $tag    = standardizeString(trim($tag));
            $tag_id = $this->database->execute(
                'INSERT INTO "tags" ("tag", "post_date", "modify_date") VALUES (?, ?, ?)',
                [$tag, date(POSTGRES), date(POSTGRES)]
            );
            $this->setLastModifyDate();

            foreach ($pictures as $picture) {
                $pic = $this->database->selectOne('SELECT "id" FROM "pictures" WHERE "title" = ?', [$picture]);

                if ($pic) {
                    $this->database->execute(
                        'INSERT INTO "pictures_tags" ("picture_id", "tag_id") VALUES (?, ?)',
                        [$pic['id'],
                        $tag_id]
                    );
                }
            }
        } else {
            $this->database->execute(
                'UPDATE "tags" SET "modify_date" = ? WHERE "id" = ?',
                [date(POSTGRES),
                $data['id']]
            );
            $this->database->execute('DELETE FROM "pictures_tags" WHERE "tag_id" = ?', [$data['id']]);
            $this->setLastModifyDate();

            foreach ($pictures as $picture) {
                $pic = $this->database->selectOne('SELECT "id" FROM "pictures" WHERE "title" = ?', [$picture]);

                if ($pic) {
                    $this->database->execute(
                        'INSERT INTO "pictures_tags" ("picture_id", "tag_id") VALUES (?, ?)',
                        [$pic['id'],
                        $data['id']]
                    );
                }
            }
        }

        if ($pictures) {
            $gallery_model->setLastModifyDate();
        }
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

        $deleted_pic = $this->database->execute('DELETE FROM "pictures_tags" WHERE "tag_id" = ?', [$id]);

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
    public function getLastModifyDate()
    {
        $data = $this->database->selectOne(
            'SELECT "modify_date" FROM "last_modify" WHERE "table" = \'tags\''
        );

        return \DateTime::createFromFormat(POSTGRES, $data['modify_date']);
    }
}
