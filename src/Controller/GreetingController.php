<?php

declare(strict_types=1);

namespace Hubertinio\SyliusKeyValuePlugin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final class GreetingController extends AbstractController
{
    public function staticallyGreetAction(?string $name): Response
    {
        return $this->render('@HubertinioSyliuskKeyValuePlugin/static_greeting.html.twig', ['greeting' => $this->getGreeting($name)]);
    }

    public function dynamicallyGreetAction(?string $name): Response
    {
        return $this->render('@HubertinioSyliuskKeyValuePlugin/dynamic_greeting.html.twig', ['greeting' => $this->getGreeting($name)]);
    }

    private function getGreeting(?string $name): string
    {
        return match ($name) {
            'Lionel-Richie' => 'Hello, is it me you\'re looking for?',
            null => 'Hello!',
            default => sprintf('Hello, %s!', $name),
        };
    }
}
