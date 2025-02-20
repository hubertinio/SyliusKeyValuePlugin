<?php

namespace Hubertinio\SyliusKeyValuePlugin\Command;

use Hubertinio\SyliusKeyValuePlugin\Storage\KeyValueStorageInterface;
use Hubertinio\SyliusKeyValuePlugin\Storage\UserKeyValueStorageInterface;
use Sylius\Component\User\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExampleCommand extends Command
{
    public function __construct(
        private KeyValueStorageInterface $keyValueStorage,
        private KeyValueStorageInterface $keyValueStorageCacheable,
        private UserKeyValueStorageInterface $userKeyValueStorage,
        private UserRepositoryInterface $adminRepository,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setName('hubertinio:key-value:example');
        $this->setDescription('Example usage');
    }

    public function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $user = $this->adminRepository->findOneByEmail('sylius@example.com');

        $this->userKeyValueStorage->set('theme', 'light', $user);

        return Command::SUCCESS;


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
