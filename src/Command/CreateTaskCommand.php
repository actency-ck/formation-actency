<?php

namespace App\Command;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateTaskCommand extends Command
{
    protected static $defaultName = 'app:create:task';
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Command for task creating')
            ->addArgument('taskNumber', InputArgument::REQUIRED, 'Number of task to create')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $taskNumber = $input->getArgument('taskNumber');
        $names = [];

        if ($taskNumber) {
            $io->warning(sprintf('Hey ! You will create %s Tasks', $taskNumber));
        }

        for($i = 0; $i < $taskNumber; $i++){
            $task = new Task("task_$i");
            $names[] = [$task->getName()];
            $this->manager->persist($task);
        }

        $this->manager->flush();

        $io->success(sprintf('Wooow You created %s Task !', $taskNumber));

        $table = new Table($output);

        $table->setHeaders(['Name']);
        $table->setRows($names);
        $table->render();
    }
}
