<?php

namespace Hubertinio\SyliusKeyValuePlugin\Command;

use Hubertinio\SyliusKeyValuePlugin\Storage\KeyValueStorageInterface;
use Hubertinio\SyliusKeyValuePlugin\Storage\UserKeyValueStorageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    public function __construct(
        private KeyValueStorageInterface $keyValue,
        private KeyValueStorageInterface $keyValueCacheable,
        private UserKeyValueStorageInterface $userKeyValue,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setName('hubertinio:key-value:storage');
        $this->setDescription('Debug storage');
    }

    public function execute(InputInterface $input, OutputInterface $output): ?int
    {



        return Command::SUCCESS;
    }
}
