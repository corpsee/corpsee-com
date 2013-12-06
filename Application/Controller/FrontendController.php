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
	protected $asset_packages = NULL;

	/**
	 * @return array
	 */
	protected function  getAssetPackages ()
	{
		if (is_null($this->asset_packages))
		{
			$this->asset_packages = $this->container['asset.packages'];
		}
		return $this->asset_packages;
	}

	/**
	 * @return array
	 */
	protected function getScripts()
	{
		$asset_packages = $this->getAssetPackages();

		return array
		(
			$asset_packages['jquery']['js'],
			$asset_packages['lightbox']['js'],

			FILE_PATH_URL . 'js/frontend.js'
		);
	}

	/**
	 * @return array
	 */
	protected function getStyles()
	{
		$asset_packages = $this->getAssetPackages();

		return array
		(
			$asset_packages['lightbox']['css'],
			$asset_packages['normalize']['css'],

			FILE_PATH_URL . 'css/frontend.less',
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