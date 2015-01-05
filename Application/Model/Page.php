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
     * @return array|false
     */
    public function getPage($alias, $language = 'ru')
    {
        return $this->database->selectOne('
            SELECT * FROM "pages" AS "p"
            LEFT JOIN "pages_content" AS "pc"
                ON pc.page_id = p.id
                WHERE p.alias = ? AND pc.language = ?
        ', [$alias, $language]);
    }
}