<?php

namespace Application\Model;

use Application\Model\Tag;

/**
 * Gallery model class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Gallery extends DatetimeModel
{
    /**
     * @param integer $id
     *
     * @return array
     */
    // id, title, filename, description, create_date
    public function selectPicByID($id)
    {
        $data = $this->database->selectOne('SELECT * FROM "tbl_pictures" WHERE "id" = ?', [$id]);
    }

    /**
     * @param integer $id
     * @param Tag $tag_model
     *
     * @return array
     */
    // id, title, filename, description, create_date
    public function selectPicByIDWithTagsInString($id, Tag $tag_model)
    {
        $data = $this->selectPicByID($id);
        $data['tags'] = $tag_model->selectTagsInStringByPicID($id);
        return $data;
    }

    /**
     * @return array
     */
    // array: id, title, filename, description, create_date
    public function selectAllPics()
    {
        return $this->database->selectMany('SELECT * FROM "tbl_pictures"');
    }

    /**
     * @param integer $limit
     *
     * @return array|false
     */
    // array: id, title, filename, description, create_date
    public function selectPics($limit = null)
    {
        if (is_integer($limit)) {
            return $this->database->selectMany(
                'SELECT * FROM "tbl_pictures" ORDER BY "create_date" DESC LIMIT ?',
                [$limit]
            );
        } else {
            return $this->database->selectMany('SELECT * FROM "tbl_pictures" ORDER BY "create_date" DESC');
        }
    }

    /**
     * @param Tag $tag_model
     *
     * @return array
     */
    // array: id, username, title, filename, description, create_date, post_date, tags
    public function selectAllPicsWithTags(Tag $tag_model)
    {
        $data = $this->selectAllPics();

        foreach ($data as &$row) {
            $row['tags'] = $tag_model->selectTagsInStringByPicID($row['id']);
        }
        unset($row);

        return $data;
    }

    /**
     * @return array
     */
    // array (by year): id, title, filename, description, create_date
    public function selectAllPicsSortByYear()
    {
        $data = $this->selectAllPics();

        foreach ($data as &$row) {
            $date = \DateTime::createFromFormat(POSTGRES, $row['create_date']);
            $pictures[$date->format('Y')][] = $row;
        }
        unset($row);

        $pictures_sort = function ($first, $second) {
            $first_date = \DateTime::createFromFormat(POSTGRES, $first['create_date']);
            $second_date = \DateTime::createFromFormat(POSTGRES, $second['create_date']);

            if ($first_date == $second_date) {
                return 0;
            }
            return ($first_date > $second_date) ? -1 : 1;
        };

        krsort($pictures, SORT_NUMERIC);
        foreach ($pictures as &$picture) {
            usort($picture, $pictures_sort);
        }
        unset($picture);
        return $pictures;
    }

    /**
     * @param string $tag
     *
     * @return array
     */
    // array: id, title, filename, description, create_date
    public function selectPicsByTag($tag)
    {
        return $this->database->selectMany('
            SELECT p.id, p.title, p.image, p.description, p.create_date, p.post_date, p.modify_date
            FROM "tbl_tags" AS "t"
            LEFT JOIN "tbl_pictures_tags" AS "pt"
            ON t.id = pt.tags_id
            LEFT JOIN "tbl_pictures" AS "p"
            ON pt.pictures_id = p.id
            WHERE t.tag = ?
        ', [$tag]);
    }

    /**
     * @param string $tag
     *
     * @return array
     */
    // array: id, title, filename, description, create_date
    public function selectPicsByTag2($tag)
    {
        $data = $this->database->selectMany('
            SELECT p.id, p.title, p.image, p.description, p.create_date, p.post_date, p.modify_date
            FROM "tbl_tags" AS "t"
            LEFT JOIN "tbl_pictures_tags" AS "pt"
            ON t.id = pt.tags_id
            LEFT JOIN "tbl_pictures" AS "p"
            ON pt.pictures_id = p.id
            WHERE t.tag = ?
        ', [$tag]);

        return $data;
    }

    /**
     * @param string $tag
     *
     * @return string
     */
    // one string of pictures
    public function selectPicsInStringByTag($tag)
    {
        $data = $this->selectPicsByTag2($tag);

        $pictures_string = '';
        $count = sizeof($data);

        for ($i = 0; $i < $count; $i++) {
            if ($i != $count - 1) {
                $pictures_string .= $data[$i]['title'] . ', ';
            } else {
                $pictures_string .= $data[$i]['title'];
            }
        }
        return $pictures_string;
    }

    /**
     * @param string $tag
     *
     * @return integer
     */
    public function countPicByTag($tag)
    {
        $data = $this->database->selectOne('
            SELECT COUNT(*) AS "count"
            FROM "tbl_tags" AS "t"
            LEFT JOIN "tbl_pictures_tags" AS "pt"
            ON t.id = pt.tags_id
            LEFT JOIN "tbl_pictures" AS "p"
            ON pt.pictures_id = p.id
            WHERE t.tag = ?
        ', [$tag]);

        return (integer)$data['count'];
    }

    /**
     * @param Tag $tag_model
     * @param string $title
     * @param string $filename_tmp
     * @param string $filename
     * @param string $description
     * @param string $tags
     * @param string $create_date
     * @param string $type
     */
    public function addPicture(
        Tag $tag_model,
        $title,
        $filename_tmp,
        $filename,
        $description,
        $tags,
        $create_date,
        $type
    ) {
        switch ($type) {
            case 'image/gif':
                $ext = '.gif';
                $path = FILE_PATH . 'pictures/x/' . $filename . $ext;
                move_uploaded_file($filename_tmp, $path);
                $source_img = imagecreatefromgif($path);
                break;
            case 'image/jpeg':
                $ext = '.jpg';
                $path = FILE_PATH . 'pictures/x/' . $filename . $ext;
                move_uploaded_file($filename_tmp, $path);
                $source_img = imagecreatefromjpeg($path);
                break;
            case 'image/png':
                $ext = '.png';
                $path = FILE_PATH . 'pictures/x/' . $filename . $ext;
                move_uploaded_file($filename_tmp, $path);
                $source_img = imagecreatefrompng($path);
                break;
        }

        // уменьшение картинки, если необходимо
        $width = imagesx($source_img);
        $height = imagesy($source_img);

        if (($width > $height) && ($width > 1024)) {
            $output_height = 1024 / ($width / $height);
            $output_img = imagecreatetruecolor(1024, $output_height);
            imagecopyresampled($output_img, $source_img, 0, 0, 0, 0, 1024, $output_height, $width, $height);
        } elseif (($width < $height) && ($width > 800)) {
            $output_height = 800 / ($width / $height);
            $output_img = imagecreatetruecolor(800, $output_height);
            imagecopyresampled($output_img, $source_img, 0, 0, 0, 0, 800, $output_height, $width, $height);
        } else {
            $output_img = $source_img;
        }

        unlink($path);
        imagejpeg($output_img, FILE_PATH . 'pictures/x/' . $filename . '.jpg', 100);

        // запись данных в базу
        $this->database->beginTransaction();

        $picture_id = $this->database->execute('
            INSERT INTO "tbl_pictures" ("title", "image", "description", "create_date", "post_date", "modify_date")
            VALUES (?, ?, ?, ?, ?, ?)
        ', [$title, $filename, $description, $create_date, date(POSTGRES), date(POSTGRES)]);
        $this->setLastModifyDate();

        // теги
        $tags_array = stringToArray($tags);

        //TODO: вынести в модель Tag
        foreach ($tags_array as $key => $tag) {
            $tags_arr[$key] = standardizeString($tag);
        }

        foreach ($tags_array as $tag) {
            $data = $this->database->selectOne(
                'SELECT COUNT(*) AS "count", "id" FROM "tbl_tags" WHERE "tag" = ?',
                [$tag]
            );

            // если тега не существует
            if ($data['count'] == 0) {
                $tag_id = $this->database->execute(
                    'INSERT INTO "tbl_tags" ("tag", "post_date", "modify_date") VALUES (?, ?, ?)',
                    [$tag, date(POSTGRES), date(POSTGRES)]
                );
                $this->database->execute(
                    'INSERT INTO "tbl_pictures_tags" ("pictures_id", "tags_id") VALUES (?, ?)',
                    [$picture_id, $tag_id]
                );
            } else {
                $this->database->execute(
                    'INSERT INTO "tbl_pictures_tags" ("pictures_id", "tags_id") VALUES (?, ?)',
                    [$picture_id, $data['id']]
                );
            }
        }

        if ($tags_array) {
            $tag_model->setLastModifyDate();
        }

        $this->database->commit();
    }

    /**
     * @param integer $width
     * @param integer $height
     * @param integer $x
     * @param integer $y
     * @param string $image
     */
    public function cropPicture($width, $height, $x, $y, $image)
    {
        $path = [
            'x'     => FILE_PATH . 'pictures/x/' . $image . '.jpg',
            'xmin'  => FILE_PATH . 'pictures/xmin/' . $image . '-min.jpg',
            'xgray' => FILE_PATH . 'pictures/xgray/' . $image . '-gray.jpg',
        ];

        $source_img = imagecreatefromjpeg($path['x']);
        $min_img = imagecreatetruecolor(200, 90);
        $gray_img = imagecreatetruecolor(200, 90);

        imagecopyresampled($min_img, $source_img, 0, 0, $x, $y, 200, 90, $width, $height);
        imagecopyresampled($gray_img, $source_img, 0, 0, $x, $y, 200, 90, $width, $height);
        imagefilter($gray_img, IMG_FILTER_COLORIZE, 255, 255, 255, 105);
        imagefilter($gray_img, IMG_FILTER_GRAYSCALE);

        if (file_exists($path['xmin'])) {
            unlink($path['xmin']);
        }
        if (file_exists($path['xgray'])) {
            unlink($path['xgray']);
        }

        imagejpeg($min_img, $path['xmin'], 70);
        imagejpeg($gray_img, $path['xgray'], 70);

        $this->setLastModifyDate();
    }

    /**
     * @param Tag $tag_model
     * @param integer $picture_id
     * @param string $title
     * @param string $description
     * @param string $tags
     * @param string $create_date
     */
    public function updatePicture(Tag $tag_model, $picture_id, $title, $description, $tags, $create_date)
    {
        $this->database->beginTransaction();

        $this->database->execute('
            UPDATE "tbl_pictures" SET "title" = ?, "description" = ?, "create_date" = ?, "modify_date" = ? WHERE "id" = ?
        ', [$title, $description, $create_date, date(POSTGRES), $picture_id]);

        $tags_array = stringToArray($tags);

        $this->setLastModifyDate();

        foreach ($tags_array as $key => $tag) {
            $tags_array[$key] = standardizeString(trim($tag));
        }

        $this->database->execute('DELETE FROM "tbl_pictures_tags" WHERE "pictures_id" = ?', [$picture_id]);
        foreach ($tags_array as $tag) {
            $data = $this->database->selectOne(
                'SELECT COUNT(*) AS "count", "id" FROM "tbl_tags" WHERE "tag" = ?',
                [$tag]
            );

            if ($data['count'] == 0) {
                $tag_id = $this->database->execute(
                    'INSERT INTO "tbl_tags" ("tag", "post_date", "modify_date") VALUES (?, ?, ?)',
                    [$tag, date(POSTGRES), date(POSTGRES)]
                );
                $this->database->execute(
                    'INSERT INTO "tbl_pictures_tags" ("pictures_id", "tags_id") VALUES (?, ?)',
                    [$picture_id, $tag_id]
                );
            } else {
                $this->database->execute(
                    'INSERT INTO "tbl_pictures_tags" ("pictures_id", "tags_id") VALUES (?, ?)',
                    [$picture_id, $data['id']]
                );
            }

        }

        if ($tags_array) {
            $tag_model->setLastModifyDate();
        }

        $this->database->commit();
    }

    /**
     * @param integer $id
     * @param string $filename_tmp
     * @param string $filename
     * @param string $type
     *
     * @throws \UnexpectedValueException
     */
    public function updatePictureImage($id, $filename_tmp, $filename, $type)
    {
        switch ($type) {
            case 'image/gif':
                $ext = '.gif';
                $path = FILE_PATH . 'pictures/x/' . $filename . $ext;
                move_uploaded_file($filename_tmp, $path);
                $source_img = imagecreatefromgif($path);
                break;
            case 'image/jpeg':
                $ext = '.jpg';
                $path = FILE_PATH . 'pictures/x/' . $filename . $ext;
                move_uploaded_file($filename_tmp, $path);
                $source_img = imagecreatefromjpeg($path);
                break;
            case 'image/png':
                $ext = '.png';
                $path = FILE_PATH . 'pictures/x/' . $filename . $ext;
                move_uploaded_file($filename_tmp, $path);
                $source_img = imagecreatefrompng($path);
                break;
            default:
                throw new \UnexpectedValueException();
        }

        $width = imagesx($source_img);
        $height = imagesy($source_img);

        if (($width > $height) && ($width > 1024)) {
            $output_height = 1024 / ($width / $height);
            $output_img = imagecreatetruecolor(1024, $output_height);
            imagecopyresampled($output_img, $source_img, 0, 0, 0, 0, 1024, $output_height, $width, $height);
        } elseif (($width < $height) && ($width > 800)) {
            $output_height = 800 / ($width / $height);
            $output_img = imagecreatetruecolor(800, $output_height);
            imagecopyresampled($output_img, $source_img, 0, 0, 0, 0, 800, $output_height, $width, $height);
        } else {
            $output_img = $source_img;
        }

        unlink($path);

        $path = FILE_PATH . 'pictures/x/' . $filename . '.jpg';

        if (is_file($path)) {
            unlink($path);
        }

        imagejpeg($output_img, $path, 100);

        $this->database->execute(
            'UPDATE "tbl_pictures" SET "modify_date" = ?, "image" = ? WHERE "id" = ?',
            [date(POSTGRES), $filename, $id]
        );
        $this->setLastModifyDate();
    }

    /**
     * @param integer $id
     */
    public function deletePicture($id)
    {
        $data = $this->database->selectOne('SELECT "image" FROM "tbl_pictures" WHERE "id" = ?', [$id]);

        if (file_exists(FILE_PATH . 'pictures/x/' . $data['image'] . '.jpg')) {
            unlink(FILE_PATH . 'pictures/x/' . $data['image'] . '.jpg');
        }
        if (file_exists(FILE_PATH . 'pictures/xgray/' . $data['image'] . '-gray.jpg')) {
            unlink(FILE_PATH . 'pictures/xgray/' . $data['image'] . '-gray.jpg');
        }
        if (file_exists(FILE_PATH . 'pictures/xmin/' . $data['image'] . '-min.jpg')) {
            unlink(FILE_PATH . 'pictures/xmin/' . $data['image'] . '-min.jpg');
        }

        $this->database->beginTransaction();

        $this->database->execute('DELETE FROM "tbl_pictures" WHERE "id" = ?', [$id]);
        $this->database->execute('DELETE FROM "tbl_pictures_tags" WHERE "pictures_id" = ?', [$id]);

        $this->database->commit();

        $this->setLastModifyDate();
    }

    /**
     * @return integer
     */
    // Устанавливаем время последнего изменения таблицы
    public function setLastModifyDate()
    {
        return $this->database->execute(
            'UPDATE "tbl_last_modify" SET "modify_date" = ? WHERE "table" = \'tbl_pictures\'',
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
            'SELECT "modify_date" FROM "tbl_last_modify" WHERE "table" = \'tbl_pictures\''
        );

        return \DateTime::createFromFormat(POSTGRES, $data['modify_date']);
    }
}