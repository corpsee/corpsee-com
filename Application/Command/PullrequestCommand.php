<?php

namespace Application\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Github\Client;
use Github\ResultPager;
use Application\Model\PullRequest;

/**
 * Base AdminController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class PullrequestCommand extends Command
{
	protected function configure ()
	{
		$this->setName('pullrequests:get')->setDescription('Get pull requests from GitHub and store it in DB');
	}

	protected function execute (InputInterface $input, OutputInterface $output)
	{
		$output->writeln('Start get pull requests from GitHub...');

		$container          = $this->getApplication()->getContainer();
		$pull_request_model = new PullRequest($container['database.database']);

		/*$pull_requests = array
		(
			array('Block8/PHPCI', 309),
			array('Block8/PHPCI', 308),
			array('Block8/PHPCI', 300),
			array('Block8/PHPCI', 299),
			array('Block8/PHPCI', 298),
			array('Block8/PHPCI', 295),
			array('Block8/PHPCI', 293),
			array('slava-vishnyakov/gerar-php', 4),
			array('slava-vishnyakov/gerar-php', 3),
			array('agat/css-framework', 13),
			array('agat/css-framework', 12),
			array('lokesh/lightbox2', 57),
			array('yiisoft/yii', 3180),
			array('vhf/free-programming-books', 632),
			array('morrisonlevi/Ardent', 18),
			array('imagecms/ImageCMS', 74),
			array('imagecms/ImageCMS', 71),
			array('pyrocms/pyrocms', 3010),
			array('spekkionu/htmlpurifier', 1),
			array('less/old-lesscss.org', 94),
			array('fuel/core', 1484),
			array('vladkens/VK', 6),
			array('vladkens/VK', 5),
			array('vladkens/VK', 3),
			array('fabpot/Pimple', 75),
			array('DandyDev/gapi-php', 2),
		);
		$pull_requests = array_reverse($pull_requests);*/

		$client = new Client();

		/*foreach ($pull_requests as $pull_request)
		{
			$repo = explode('/', $pull_request[0]);

			$data = $client->api('pull_request')->show($repo[0], $repo[1], $pull_request[1]);
			$pull_request_model->insertPullRequest($pull_request[0], (integer)$pull_request[1], serialize($data));

			$output->writeln("\tPull request {($pull_request[0]}/{($pull_request[1]} inserted");
		}*/

		$repositories = $client->api('user');
		$paginator  = new ResultPager($client);
		$events     = $paginator->fetch($repositories, 'publicEvents', array('corpsee'));
		//var_dump($events); exit;
		foreach ($events as $event)
		{
			if ($event['type'] == 'PullRequestEvent' && !$pull_request_model->isIssetPullRequest($event['repo']['name'], $event['payload']['number']))
			{
				$repo = explode('/', $event['repo']['name']);

				$data = $client->api('pull_request')->show($repo[0], $repo[1], $event['payload']['number']);
				$pull_request_model->insertPullRequest($event['repo']['name'], (integer)$event['payload']['number'], serialize($data));

				$output->writeln("\tPull request {$event['repo']['name']}/{$event['payload']['number']} inserted");
			}
		}

		$output->writeln('End get pull requests from GitHub...');
	}
}