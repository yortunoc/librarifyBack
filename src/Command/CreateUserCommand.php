<?php

namespace App\Command;


use App\Service\User\CreateUser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:user:create-user';

    private CreateUser $createUser;

    public function __construct(CreateUser $createUser)
    {
        $this->createUser = $createUser;
        parent::__construct();

    }

    protected function configure()
    {
        $this
            ->setDescription('Command to create user')
            ->addOption('email', '-u', InputOption::VALUE_OPTIONAL, 'username to create user')
            ->addOption('password', '-p', InputOption::VALUE_OPTIONAL, 'password of user')
            ->addOption('roles', '-r', InputOption::VALUE_REQUIRED, 'roles for user separated by ,')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getOption('email');
        $password = $input->getOption('password');
        $roles = $input->getOption('roles');
        ($this->createUser)($username, $password, $roles);
        $output->writeln('User with username: ' . $username . 'and password: ' . $password . 'was created');
        return Command::SUCCESS;
    }
}