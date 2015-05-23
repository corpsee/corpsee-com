<?php

namespace Application\Controller;

use Nameless\Core\Controller;

/**
 * Base FrontendController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class FrontendController extends Controller
{
    /**
     * @var array
     */
    protected $asset_libs = null;

    /**
     * @param string $language_prefix
     * @param string $route
     */
    protected function languageRedirect($language_prefix, $route)
    {
        if (!$language_prefix) {
            $language_prefix = $this->getLanguage();

            $this->redirect($this->generateURL($route, ['language_prefix' => $language_prefix]));
        }
    }

    /**
     * @return array
     */
    protected function getAssetLibs()
    {
        if (is_null($this->asset_libs)) {
            $assets = $this->container['assets'];
            $this->asset_libs = $assets['libs'];
        }

        return $this->asset_libs;
    }

    /**
     * @return array
     */
    protected function getScripts()
    {
        $asset_libs = $this->getAssetLibs();

        return [
            $asset_libs['jquery']['js'],
            $asset_libs['lightbox']['js'],
            FILE_PATH_URL . 'js/frontend.js'
        ];
    }

    /**
     * @return array
     */
    protected function getStyles()
    {
        $asset_libs = $this->getAssetLibs();

        return [
            $asset_libs['lightbox']['css'],
            $asset_libs['normalize']['css'],
            FILE_PATH_URL . 'css/frontend.less',
        ];
    }

    /**
     * @return string
     */
    protected function getLanguage()
    {
        $language        = $this->container['language'];
        $accept_language = $this->getHeaders('Accept-Language');

        $accept_languages = [];
        if (preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', $accept_language[0], $accept_language)) {
            $accept_languages = array_combine($accept_language[1], $accept_language[2]);
            foreach ($accept_languages as $n => $v) {
                $accept_languages[$n] = $v ? $v : 1;
            }
            arsort($accept_languages, SORT_NUMERIC);
        }

        $languages = [];
        foreach ($this->container['isset_languages'] as $isset_language => $alias) {
            foreach ($alias as $alias_lang) {
                $languages[strtolower($alias_lang)] = strtolower($isset_language);
            }
        }
        foreach ($accept_languages as $l => $v) {
            $s = strtok($l, '-'); // убираем то что идет после тире в языках вида "en-us, ru-ru"
            if (isset($languages[$s])) {
                $language = $languages[$s];
                break;
            }
        }
        return $language;
    }
}
