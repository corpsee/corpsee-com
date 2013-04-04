<!doctype html>
<meta charset="utf-8">
<style>
	html, body
	{
		font-family : monospace;
	}
</style>

<?php

/**
 * Example 2.
 * Get access token via OAuth and usage VK API.
 * @link http://vk.com/developers.php VK API
 */

error_reporting(-1);
$uid = '2513506';

require_once('vkapi.php');

$vk_config = array
(
	'app_id'       => '3490287',
	'api_secret'   => '5NQ2WA9QgFZ5dAE8rR2e',
	'callback_url' => 'http://vk.corpsee.com/vk.php',
	'api_settings' => 'offline,friends,photos,likes,audio' // In this example use 'friends'.
	// If you need infinite token use key 'offline'.
);

try
{
	$vk = new VK($vk_config['app_id'], $vk_config['api_secret']);

	if (!isset($_REQUEST['code']))
	{
		/**
		 * If you need switch the application in test mode,
		 * add another parameter "true". Default value "false".
		 * Ex. $vk->getAuthorizeURL($api_settings, $callback_url, true);
		 */
		$authorize_url = $vk->getAuthorizeURL($vk_config['api_settings'], $vk_config['callback_url']);

		echo '<a href="' . $authorize_url . '">Sign in with VK</a>';
	}
	else
	{
		$access_token = $vk->getAccessToken($_REQUEST['code'], $vk_config['callback_url']);

		//echo 'access token: ' . $access_token['access_token'] . '<br>expires: ' . $access_token['expires_in'] . ' sec.' . '<br>user id: ' . $access_token['user_id'] . '<br>';

		$response = $vk->api('audio.get', array('uid'   => $uid));

		echo '<pre>'; print_r($response);
	}
}
catch (VKException $error)
{
	echo $error->getMessage();
}