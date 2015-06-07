<?php

namespace Application\Model;

use Nameless\Modules\Database\Model;

/**
 * Project model class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Project extends Model
{
    /**
     * @return array|false
     */
    public function getAll()
    {
        return $this->database->selectMany('SELECT * FROM "projects"');
    }

    /**
     * @param integer $id
     *
     * @return array|false
     */
    public function get($id)
    {
        return $this->database->selectOne('SELECT * FROM "projects" WHERE "id" = ?', [$id]);
    }

    /**
     * @param integer $id
     *
     * @return integer
     */
    public function delete($id)
    {
        return $this->database->execute('DELETE FROM "projects" WHERE "id" = ?', [$id]);
    }

    /**
     * @param string $title
     * @param string $description
     * @param string $link
     * @param string $role
     * @param string $image_tmp
     * @param string $type
     *
     * @return integer
     */
    public function create($title, $description, $link, $role, $image_tmp = null, $type = null)
    {
        $imagename = '';
        if ($image_tmp) {
            $imagename = standardizeFilename($title);

            switch ($type) {
                case 'image/gif':
                    $path = FILE_PATH . 'projects/' . $imagename . '.gif';
                    break;
                case 'image/png':
                    $path = FILE_PATH . 'projects/' . $imagename . '.png';
                    break;
                case 'image/jpeg': default:
                    $path = FILE_PATH . 'projects/' . $imagename . '.jpg';
            }
            move_uploaded_file($image_tmp, $path);
        }

        return $this->database->execute('
            INSERT INTO "projects" ("title", "description", "link", "role", "image", "post_date", "modify_date")
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ', [$title, $description, $link, $role, $imagename, date(POSTGRES), date(POSTGRES)]);
    }

    /**
     * @param integer $id
     * @param string  $title
     * @param string  $description
     * @param string  $link
     * @param string  $role
     * @param string  $image_tmp
     * @param string  $type
     *
     * @return integer
     */
    public function update($id, $title, $description, $link, $role, $image_tmp = null, $type = null)
    {
        $imagename = standardizeFilename($title);
        if ($image_tmp) {
            switch ($type) {
                case 'image/gif':
                    $path = FILE_PATH . 'projects/' . $imagename . '.gif';
                    break;
                case 'image/png':
                    $path = FILE_PATH . 'projects/' . $imagename . '.png';
                    break;
                case 'image/jpeg':
                default:
                    $path = FILE_PATH . 'projects/' . $imagename . '.jpg';
            }
            unlink($path);
            move_uploaded_file($image_tmp, $path);
        }

        return $this->database->execute('
            UPDATE "projects" SET "title" = ?, "description" = ?, "link" = ?, "role" = ?, "image" = ?, "modify_date" = ? WHERE "id" = ?
        ', [$title, $description, $link, $role, $imagename, date(POSTGRES), (integer)$id]);
    }
}
