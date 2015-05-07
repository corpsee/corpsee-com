<?php

namespace Application\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Github\Client;
use Github\ResultPager;
use Application\Model\PullRequest;

/**
 * PullRequestCommand
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class PullRequestCommand extends Command
{
    protected function configure()
    {
        $this->setName('pullrequests:get')->setDescription('Get pull requests from GitHub and store it in DB');
    }

    //TODO: Add try-catch Github\Exception\RuntimeException
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start get pull requests from GitHub: ' . date('Y-m-d H:i:s'));

        $container = $this->getApplication()->getContainer();
        $pull_request_model = new PullRequest($container['database.database']);

        $client = new Client();
        $repositories = $client->api('user');
        $paginator = new ResultPager($client);
        $events = $paginator->fetchAll($repositories, 'publicEvents', ['corpsee']);

        $output->writeln("\tpublicEvents: " . sizeof($events));

        $pull_requests = [];
        foreach ($events as $event) {
            if ($event['type'] == 'PullRequestEvent') {
                $pull_requests[] = $event;
            }
        }

        $output->writeln("\tPullRequestEvent: " . sizeof($pull_requests) . "\n");

        $inserted = 0;
        $updated  = 0;
        foreach ($pull_requests as $pull_request) {
            $repo = explode('/', $pull_request['repo']['name']);
            $data = $client->api('pull_request')->show($repo[0], $repo[1], $pull_request['payload']['number']);

            if ($data['user']['login'] !== 'corpsee') {
                continue;
            }

            if (!$pull_request_model->isIssetPullRequest(
                $pull_request['repo']['name'],
                $pull_request['payload']['number']
            )
            ) {
                $pull_request_model->insertPullRequest(
                    $pull_request['repo']['name'],
                    (integer)$pull_request['payload']['number'],
                    $data['body'],
                    $data['title'],
                    (true === (boolean)$data['merged']) ? 'merged' : $data['state'],
                    $data['commits'],
                    $data['additions'],
                    $data['deletions'],
                    $data['changed_files'],
                    date('Y-m-d H:i:sP', strtotime($data['created_at']))
                );
                $output->writeln("\tPull request {$pull_request['repo']['name']}/{$pull_request['payload']['number']} inserted");
                $inserted++;
            } else {
                $pull_request_model->updatePullRequest(
                    $pull_request['repo']['name'],
                    (integer)$pull_request['payload']['number'],
                    $data['body'],
                    $data['title'],
                    (true === (boolean)$data['merged']) ? 'merged' : $data['state'],
                    $data['commits'],
                    $data['additions'],
                    $data['deletions'],
                    $data['changed_files'],
                    date('Y-m-d H:i:sP', strtotime($data['created_at']))
                );
                $output->writeln("\tPull request {$pull_request['repo']['name']}/{$pull_request['payload']['number']} updated");
                $updated++;
            }
        }
        $output->writeln("\tInserted: " . $inserted);
        $output->writeln("\tUpdated: " . $updated);

        $output->writeln("End get pull requests from GitHub\n");

        return 0;
    }
}
