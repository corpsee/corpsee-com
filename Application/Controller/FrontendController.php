<?php

namespace Application\Controller;

use Symfony\Component\HttpFoundation\Response;
use Nameless\Core\Controller;

/**
 * Base FrontendController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class FrontendController extends Controller
{
	/**
	 * @return array
	 */
	protected function getScripts()
	{
		return array
		(
			FILE_PATH_URL . 'lib/jquery/1.10.2/jquery.js',
			FILE_PATH_URL . 'lib/lightbox/2.6-custom/lightbox.js',
			FILE_PATH_URL . 'scripts/frontend.js'
		);
	}

	/**
	 * @return array
	 */
	protected function getStyles()
	{
		return array
		(
			FILE_PATH_URL . 'lib/lightbox/2.6-custom/lightbox.css',
			FILE_PATH_URL . 'lib/normalize/1.1.2/normalize.css',
			FILE_PATH_URL . 'styles/frontend.less',
		);
	}

	/**
	 * @return string
	 */
	protected function getLanguage ()
	{
		$language        = $this->container['language'];
		$accept_language = $this->getHeaders('Accept-Language');

		$accept_languages = array();
		if (preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', $accept_language[0], $accept_language))
		{
			$accept_languages = array_combine($accept_language[1], $accept_language[2]);
			foreach ($accept_languages as $n => $v)
			{
				$accept_languages[$n] = $v ? $v : 1;
			}
			arsort($accept_languages, SORT_NUMERIC);
		}

		$languages = array();
		foreach ($this->container['isset_languages'] as $isset_language => $alias)
		{
			foreach ($alias as $alias_lang)
			{
				$languages[strtolower($alias_lang)] = strtolower($isset_language);
			}
		}
		foreach ($accept_languages as $l => $v)
		{
			$s = strtok($l, '-'); // убираем то что идет после тире в языках вида "en-us, ru-ru"
			if (isset($languages[$s]))
			{
				$language = $languages[$s];
				break;
			}
		}
		return $language;
	}
}