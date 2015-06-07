<?php

namespace Application\Command;

use Application\Model\Project;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ProjectsCommand
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class ProjectsCommand extends Command
{
    protected function configure()
    {
        $this->setName('projects:migrate')->setDescription('Add projects to DB');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projects = [
            [
                'title'       => 'PHP-UTF-8',
                'description' => 'A library for UTF-8 in PHP providing UTF-8 aware functions to mirror PHP\'s own string functions. Fork of FSX/php-utf8.',
                'link'        => 'https://github.com/corpsee/php-utf-8',
                'role'        => 'Developer',
            ], [
                'title'       => 'PHPell',
                'description' => 'Vagrant VM with bash(shell) provision scripts for PHP development.',
                'link'        => 'https://github.com/corpsee/phpell',
                'role'        => 'Developer',
            ], [
                'title'       => 'Nameless debug package',
                'description' => 'Simple and independent debug component compliant with PSR-1, PSR-2, PSR-4 and Composer for PHP 5.4+.',
                'link'        => 'https://github.com/corpsee/nameless-debug',
                'role'        => 'Developer',
            ], [
                'title'       => 'PHP application sample',
                'description' => 'Sample of PHP application/library compliant with PSR-1, PSR-2, PSR-4 and Composer for PHP 5.4+.',
                'link'        => 'https://github.com/corpsee/php-application-sample',
                'role'        => 'Developer',
            ], [
                'title'       => 'PHPCI',
                'description' => 'PHPCI is a free and open source continuous integration tool specifically designed for PHP.',
                'link'        => 'https://www.phptesting.org',
                'role'        => 'Contributor',
            ],
        ];

        $output->writeln('Start: ' . date('Y-m-d H:i:s'));

        /** @var \Nameless\Core\Container $container */
        $container = $this->getApplication()->getContainer();
        $model     = new Project($container['database.database']);

        foreach ($projects as $project) {
            $id = $model->create($project['title'], $project['description'], $project['link'], $project['role']);

            $output->writeln("\tAdded project: " . $project['title'] . ' (' . $id . ')');
        }

        $output->writeln("End\n");

        return 0;
    }
}
