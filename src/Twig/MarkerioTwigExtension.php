<?php

/*
 * This file is part of Monsieur Biz's  for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMarkerioPlugin\Twig;

use MonsieurBiz\SyliusMarkerioPlugin\Event\MarkerioCustomDataEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class MarkerioTwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('monsieurbiz_markerio_custom_data', [$this, 'getMarkerioCustomData']),
        ];
    }

    public function getMarkerioCustomData(): array
    {
        $this->eventDispatcher->dispatch($event = new MarkerioCustomDataEvent());

        return $event->getData();
    }
}
