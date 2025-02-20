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
        private KeyValueStorageInterface $keyValueStorage,
        private KeyValueStorageInterface $keyValueStorageCacheable,
        private UserKeyValueStorageInterface $userKeyValueStorage,
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
        $arr = [
            'theme' => 'light',
            'language' => 'en',
            'currency' => 'USD',
        ];

        $arr2 = [
            'dark',
            'pl',
            'PLN',
        ];

        $this->keyValueStorage->set('arr', $arr2, 'user_123');
        $this->keyValueStorage->set('arr', $arr);
        $theme = $this->keyValueStorage->get('theme', null, 'user_123');

        $this->keyValueStorage->rename('theme', 'user_theme', 'user_123');

//        $this->keyValueStorage->deleteAll('user_123');

        $systemSettings = $this->keyValueStorage->getAll('system');


        return Command::SUCCESS;
    }
}
