<?php

namespace Application\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Base AdminController controller class
 *
 * @author Corpsee <poisoncorpsee@gmail.com>
 */
class AssetsCommand extends Command
{
    protected function configure()
    {
        $this->setName('assets:compile')->setDescription('Compiled assets');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start compiling assets...');

        $container = $this->getApplication()->getContainer();
        $container['environment'] = 'test';

        $assets = $container['assets.packages'];
        $dispatcher = $container['assets.dispatcher'];

        $dispatcher->getAssets('frontend', [
            $assets['lightbox']['css'],
            $assets['normalize']['css'],
            FILE_PATH_URL . 'css/frontend.less'
        ]);

        $dispatcher->getAssets('frontend', [
            $assets['jquery']['js'],
            $assets['lightbox']['js'],
            FILE_PATH_URL . 'js/frontend.js'
        ]);

        $output->writeln('End compiling assets...');
    }
}
