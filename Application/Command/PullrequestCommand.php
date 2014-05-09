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
		$output->writeln('Start get pull requests from GitHub');

		$container          = $this->getApplication()->getContainer();
		$pull_request_model = new PullRequest($container['database.database']);

		$client       = new Client();
		$repositories = $client->api('user');
		$paginator    = new ResultPager($client);
		$events       = $paginator->fetch($repositories, 'publicEvents', array('corpsee'));
		foreach ($events as $event)
		{
			if ($event['type'] == 'PullRequestEvent')
			{
				$repo = explode('/', $event['repo']['name']);
				$data = $client->api('pull_request')->show($repo[0], $repo[1], $event['payload']['number']);

				if (!$pull_request_model->isIssetPullRequest($event['repo']['name'], $event['payload']['number']))
				{
					$pull_request_model->insertPullRequest
					(
						$event['repo']['name'],
						(integer)$event['payload']['number'],
						$data['body'],
						$data['title'],
						TRUE === (boolean)$data['merged'] ? 'merged' : $data['state'],
						$data['commits'],
						$data['additions'],
						$data['deletions'],
						$data['changed_files'],
						(integer)\DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $data['created_at'])->format('U')
					);
					$output->writeln("\tPull request {$event['repo']['name']}/{$event['payload']['number']} inserted");
				}
				else
				{
					$pull_request_model->updatePullRequest
					(
						$event['repo']['name'],
						(integer)$event['payload']['number'],
						$data['body'],
						$data['title'],
						TRUE === (boolean)$data['merged'] ? 'merged' : $data['state'],
						$data['commits'],
						$data['additions'],
						$data['deletions'],
						$data['changed_files'],
						(integer)\DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $data['created_at'])->format('U')
					);
					$output->writeln("\tPull request {$event['repo']['name']}/{$event['payload']['number']} updated");
				}
			}
		}

		$output->writeln("End get pull requests from GitHub\n");
	}
}