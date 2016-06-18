<?php

namespace Application\Model;

use Nameless\Modules\Database\Model;
use Nameless\Utilities\StringHelper;

/**
 * Project model class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Project extends Model
{
    /**
     * @param array $projects
     */
    protected function sortProjects(array &$projects)
    {
        usort($projects, function ($first, $second) {
            if ($first['order'] == $second['order']) {
                return 0;
            }
            return ($first['order'] < $second['order']) ? 1 : -1;
        });
    }

    /**
     * @return array|false
     */
    public function getAll()
    {
        $projects = $this->database->selectMany('SELECT * FROM "projects"');
        if ($projects) {
            $this->sortProjects($projects);
        }

        return $projects;
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
     * @param string  $title
     * @param string  $description
     * @param string  $link
     * @param string  $role
     * @param integer $order
     * @param string  $image_tmp
     * @param string  $type
     *
     * @return integer
     */
    public function create($title, $description, $link, $role, $order = 0, $image_tmp = null, $type = null)
    {
        $order = (integer)$order;

        $imagename = '';
        if ($image_tmp) {
            $imagename = StringHelper::standardize($title);

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
            INSERT INTO "projects" ("title", "description", "link", "role", "image", "post_date", "modify_date", "order")
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ', [$title, $description, $link, $role, $imagename, date(POSTGRES), date(POSTGRES), $order]);
    }

    /**
     * @param integer $id
     * @param string  $title
     * @param string  $description
     * @param string  $link
     * @param string  $role
     * @param integer $order
     * @param string  $image_tmp
     * @param string  $type
     *
     * @return integer
     */
    public function update($id, $title, $description, $link, $role, $order, $image_tmp = null, $type = null)
    {
        $order = (integer)$order;

        $imagename = StringHelper::standardize($title);
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
            UPDATE "projects" SET "title" = ?, "description" = ?, "link" = ?, "role" = ?, "image" = ?, "modify_date" = ?, "order" = ? WHERE "id" = ?
        ', [$title, $description, $link, $role, $imagename, date(POSTGRES), $order, (integer)$id]);
    }
}
