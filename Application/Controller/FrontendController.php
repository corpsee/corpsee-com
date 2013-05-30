<?php

namespace Application\Controller;

use Framework\Controller;
use Assetic\Asset\AssetCollection;
use Assetic\Filter\Yui\CssCompressorFilter;
use Assetic\Filter\LessphpFilter;
use Assetic\Filter\Yui\JsCompressorFilter;
use Assetic\Asset\FileAsset;

class FrontendController extends Controller
{
	protected function generateAssets ($name, array $assets, $type = 'css')
	{

		$less_template = '<link href="%s" rel="stylesheet/less" type="text/css" />';
		$css_template  = '<link href="%s" rel="stylesheet" type="text/css" />';
		$js_template   = '<script src="%s" type="text/javascript"></script>';

		if ($this->container->minify_assets === FALSE)
		{
			$result_assets = '';
			switch ($type)
			{
				case 'js':
					$assets[] = SCRIPT_PATH_URL . 'less-1.3.3.min.js';
					foreach ($assets as $asset)
					{
						$result_assets .= sprintf($js_template, $asset);
					}
					break;
				case 'less':
					foreach ($assets as $asset)
					{
						$result_assets .= sprintf($less_template, $asset);
					}
					break;
				case 'css':
				default:
					foreach ($assets as $asset)
					{
						$result_assets .= sprintf($css_template, $asset);
					}
			}
			return $result_assets;
		}

		$hash = '';
		foreach ($assets as $asset)
		{
			$hash .= md5_file(URLToPath($asset));
		}

		switch ($type)
		{
			case 'js':
				$result_path = SCRIPT_PATH . $name . '.' . $type;
				$hash_path   = $this->container->cache_path . $name . '-' . $type;
				break;
			case 'css':
			case 'less':
			default:
				$result_path = STYLE_PATH . $name . '.css';
				$hash_path   = $this->container->cache_path . $name . '-css';
		}

		$canonical_hash = '';
		if (file_exists($hash_path))
		{
			$canonical_hash = trim(file_get_contents($hash_path));
		}

		if ($canonical_hash !== $hash || !file_exists($result_path))
		{
			$assets_array = array();
			$file_filters = array();

			if ($type = 'less')
			{
				$file_filters[] = new LessphpFilter();
			}

			foreach ($assets as $asset)
			{
				$assets_array[] = new FileAsset(URLToPath($asset), $file_filters);
			}

			$filters = array();
			switch ($type)
			{
				case 'js':
					$filters[] = new JsCompressorFilter($this->container->yuicompressor_path, $this->container->java_path);
					break;
				case 'css':
				case 'less':
				default:
					$filters[] = new CssCompressorFilter($this->container->yuicompressor_path, $this->container->java_path);
			}

			$collection = new AssetCollection($assets_array, $filters);

			file_put_contents($result_path, $collection->dump());
			file_put_contents($hash_path, $hash);
		}

		switch ($type)
		{
			case 'js':
				$result_assets = sprintf($js_template, pathToURL($result_path));
				break;
			case 'less':
			case 'css':
			default:
				$result_assets = sprintf($css_template, pathToURL($result_path));
		}
		return $result_assets;
	}
}