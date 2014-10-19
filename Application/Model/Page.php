<?php

namespace Application\Model;

use Nameless\Modules\Database\Model;

/**
 * Page model class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class Page extends Model
{
    /**
     * @param string $alias
     * @param string $language
     *
     * @return array|FALSE
     */
    public function getPage($alias, $language = 'ru')
    {
        return $this->database->selectOne("
            SELECT * FROM `tbl_pages` AS p
            LEFT JOIN `tbl_pages_content` AS pc
                ON pc.page_id = p.id
                WHERE p.alias = ? AND pc.language = ?
        ", [$alias, $language]);
    }
}