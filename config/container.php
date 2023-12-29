<?php

declare(strict_types=1);

use App\Repository\DbUrlCheckRepository;
use App\Repository\DbUrlRepository;
use App\Repository\UrlCheckRepositoryInterface;
use App\Repository\UrlRepositoryInterface;
use App\Service\DbConnection;
use Slim\Flash\Messages;
use Slim\Views\Twig;

return [
    Twig::class                        => fn() => Twig::create(__DIR__.'/../templates'),
    Messages::class                    => fn() => new Messages(),
    PDO::class                         => fn() => DbConnection::get()->connect(),
    UrlRepositoryInterface::class      => DI\autowire(DbUrlRepository::class),
    UrlCheckRepositoryInterface::class => DI\autowire(DbUrlCheckRepository::class),
];
