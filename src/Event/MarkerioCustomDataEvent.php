<?php

/*
 * This file is part of Monsieur Biz's  for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMarkerioPlugin\Event;

class MarkerioCustomDataEvent implements MarkerioCustomDataEventInterface
{
    private array $data = [];

    public function setData($key, $value): static
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function mergeData(array $data): static
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
