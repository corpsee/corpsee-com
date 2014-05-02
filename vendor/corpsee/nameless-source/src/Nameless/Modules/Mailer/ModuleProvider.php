<?php

/**
 * This file is part of the Nameless framework.
 * For the full copyright and license information, please view the LICENSE
 *
 * @package    Nameless
 * @author     Corpsee <poisoncorpsee@gmail.com>
 * @copyright  2012 - 2014. Corpsee <poisoncorpsee@gmail.com>
 * @link       https://github.com/corpsee/Nameless
 */

namespace Nameless\Modules\Mailer;

use Nameless\Core\ModuleProvider as BaseModuleProvider;

/**
 * Mailer ModuleProvider class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ModuleProvider extends BaseModuleProvider
{
	public function register ($module_path = NULL)
	{
		$module_path = __DIR__ . DS;
		parent::register($module_path);

		$this->container['mailer.mailer'] = $this->container->share(function ($c)
		{
			return new \Swift_Mailer($c['mailer.transport']);
		});

		$this->container['mailer.transport'] = $this->container->share(function ($c)
		{
			return new \Swift_Transport_MailTransport($c['mailer.transport_invoker'], $c['mailer.transport_eventdispatcher']);
		});

		$this->container['mailer.transport_invoker'] = $this->container->share(function ()
		{
			return new \Swift_Transport_SimpleMailInvoker();
		});

		$this->container['mailer.transport_eventdispatcher'] = $this->container->share(function ()
		{
			return new \Swift_Events_SimpleEventDispatcher();
		});
	}

	public function boot () {}
}