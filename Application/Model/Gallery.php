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
     * @param array $data
     *
     * @return array
     */
    private function formatDate(array $data)
    {
        $create_date = \DateTime::createFromFormat('U', $data['create_date']);
        $create_date->setTimezone($this->timezone);
        $data['create_date'] = $create_date->format('d.m.Y');

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
    // id, title, filename, description, create_date
    public function selectPicByID($id)
    {
        $data = $this->database->selectOne("SELECT * FROM `tbl_pictures` WHERE `id` = ?", array($id));
        return $this->formatDate($data);
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
        $data = $this->database->selectMany("SELECT * FROM `tbl_pictures`");

        foreach ($data as &$row) {
            $row = $this->formatDate($row);
        }
        unset($row);

        return $data;
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
            $data = $this->database->selectMany(
                "SELECT * FROM `tbl_pictures` ORDER BY create_date DESC LIMIT ?",
                array($limit)
            );
        } else {
            $data = $this->database->selectMany("SELECT * FROM `tbl_pictures` ORDER BY create_date DESC");
        }

        foreach ($data as &$row) {
            $row = $this->formatDate($row);
        }
        unset($row);

        return $data;
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
            $date = \DateTime::createFromFormat('d.m.Y:H.i.s', $row['create_date'] . ':00.00.00');
            $date->setTimezone($this->timezone);
            $pictures[$date->format('Y')][] = $row;
        }
        unset($row);

        $timezone = $this->timezone;

        $pictures_sort = function ($first, $second) use ($timezone) {
            $first_date = \DateTime::createFromFormat('d.m.Y:H.i.s', $first['create_date'] . ':00.00.00');
            $first_date->setTimezone($timezone);

            $second_date = \DateTime::createFromFormat('d.m.Y:H.i.s', $second['create_date'] . ':00.00.00');
            $second_date->setTimezone($timezone);

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
        $data = $this->database->selectMany("
            SELECT p.id, p.title, p.image, p.description, p.create_date, p.post_date, p.modify_date
            FROM `tbl_tags` AS t
            LEFT JOIN `tbl_pictures_tags` AS pt
            ON t.id = pt.tags_id
            LEFT JOIN `tbl_pictures` AS p
            ON pt.pictures_id = p.id
            WHERE t.tag = ?
        ", array($tag));

        foreach ($data as &$row) {
            $row = $this->formatDate($row);
        }
        unset($row);

        return $data;
    }

    /**
     * @param string $tag
     *
     * @return array
     */
    // array: id, title, filename, description, create_date
    public function selectPicsByTag2($tag)
    {
        $data = $this->database->selectMany("
            SELECT p.id, p.title, p.image, p.description, p.create_date, p.post_date, p.modify_date
            FROM `tbl_tags` AS t
            LEFT JOIN `tbl_pictures_tags` AS pt
            ON t.id = pt.tags_id
            LEFT JOIN `tbl_pictures` AS p
            ON pt.pictures_id = p.id
            WHERE t.tag = ?
        ", array($tag));

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
        $data = $this->database->selectOne("
            SELECT COUNT(*) AS `count`
            FROM `tbl_tags` AS t
            LEFT JOIN `tbl_pictures_tags` AS pt
            ON t.id = pt.tags_id
            LEFT JOIN `tbl_pictures` AS p
            ON pt.pictures_id = p.id
            WHERE t.tag = ?
        ", array($tag));

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

        $create_date = \DateTime::createFromFormat('d.m.Y:H.i.s', $create_date . ':00.00.00');
        $create_date->setTimezone($this->timezone);

        // запись данных в базу
        $this->database->beginTransaction();

        $picture_id = $this->database->execute("
            INSERT INTO `tbl_pictures` (`title`, `image`, `description`, `create_date`, `post_date`, `modify_date`)
            VALUES (?, ?, ?, ?, ?, ?)
        ", array($title, $filename, $description, $create_date->format('U'), time(), time()));
        $this->setLastModifyDate();

        // теги
        $tags_array = stringToArray($tags);

        //TODO: вынести в модель Tag
        foreach ($tags_array as $key => $tag) {
            $tags_arr[$key] = standardizeString($tag);
        }

        foreach ($tags_array as $tag) {
            $data = $this->database->selectOne(
                "SELECT COUNT(*) AS `count`, `id` FROM `tbl_tags` WHERE `tag` = ?",
                array($tag)
            );

            // если тега не существует
            if ($data['count'] == 0) {
                $tag_id = $this->database->execute(
                    "INSERT INTO `tbl_tags` (`tag`, `post_date`, `modify_date`) VALUES (?, ?, ?)",
                    array($tag, time(), time())
                );
                $this->database->execute(
                    "INSERT INTO `tbl_pictures_tags` (`pictures_id`, `tags_id`) VALUES (?, ?)",
                    array($picture_id, $tag_id)
                );
            } else {
                $this->database->execute(
                    "INSERT INTO `tbl_pictures_tags` (`pictures_id`, `tags_id`) VALUES (?, ?)",
                    array($picture_id, $data['id'])
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
        $path = array
        (
            'x' => FILE_PATH . 'pictures/x/' . $image . '.jpg',
            'xmin' => FILE_PATH . 'pictures/xmin/' . $image . '-min.jpg',
            'xgray' => FILE_PATH . 'pictures/xgray/' . $image . '-gray.jpg',
        );

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
        $create_date = \DateTime::createFromFormat('d.m.Y:H.i.s', $create_date . ':00.00.00');
        $create_date->setTimezone($this->timezone);

        $this->database->beginTransaction();

        $this->database->execute("
            UPDATE `tbl_pictures` SET `title` = ?, `description` = ?, `create_date` = ?, `modify_date` = ? WHERE `id` = ?
        ", array($title, $description, $create_date->format('U'), time(), $picture_id));

        $tags_array = stringToArray($tags);

        $this->setLastModifyDate();

        foreach ($tags_array as $key => $tag) {
            $tags_array[$key] = standardizeString(trim($tag));
        }

        $this->database->execute("DELETE FROM `tbl_pictures_tags` WHERE `pictures_id` = ?", array($picture_id));
        foreach ($tags_array as $tag) {
            $data = $this->database->selectOne(
                "SELECT COUNT(*) AS `count`, `id` FROM `tbl_tags` WHERE `tag` = ?",
                array($tag)
            );

            if ($data['count'] == 0) {
                $tag_id = $this->database->execute(
                    "INSERT INTO `tbl_tags` (`tag`, `post_date`, `modify_date`) VALUES (?, ?, ?)",
                    array($tag, time(), time())
                );
                $this->database->execute(
                    "INSERT INTO `tbl_pictures_tags` (`pictures_id`, `tags_id`) VALUES (?, ?)",
                    array($picture_id, $tag_id)
                );
            } else {
                $this->database->execute(
                    "INSERT INTO `tbl_pictures_tags` (`pictures_id`, `tags_id`) VALUES (?, ?)",
                    array($picture_id, $data['id'])
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
            "UPDATE `tbl_pictures` SET `modify_date` = ?, `image` = ? WHERE `id` = ?",
            array(time(), $filename, $id)
        );
        $this->setLastModifyDate();
    }

    /**
     * @param integer $id
     */
    public function deletePicture($id)
    {
        $data = $this->database->selectOne("SELECT `image` FROM `tbl_pictures` WHERE `id` = ?", array($id));

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

        $this->database->execute("DELETE FROM `tbl_pictures` WHERE `id` = ?", array($id));
        $this->database->execute("DELETE FROM `tbl_pictures_tags` WHERE `pictures_id` = ?", array($id));

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
            "UPDATE `tbl_last_modify` SET `modify_date` = ? WHERE `table` = 'tbl_pictures'",
            array(time())
        );
    }

    /**
     * @return \DateTime
     */
    // Получаем время последнего изменения таблицы
    public function getLastModifyDate()
    {
        $data = $this->database->selectOne(
            "SELECT `modify_date` FROM `tbl_last_modify` WHERE `table` = 'tbl_pictures'"
        );

        $modify_date = \DateTime::createFromFormat('U', $data['modify_date']);
        $modify_date->setTimezone($this->timezone);

        return $modify_date;
    }
}