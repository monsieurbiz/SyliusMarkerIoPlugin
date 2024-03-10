<?php

/*
 * This file is part of Monsieur Biz's  for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMarkerioPlugin\EventListener;

use MonsieurBiz\SyliusMarkerioPlugin\Event\MarkerioCustomDataEventInterface;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;

class MarkerioCustomDataListener
{
    public function __construct(
        private ShopperContextInterface $shopperContext,
        private CartContextInterface $cartContext,
    ) {
    }

    public function appendCustomData(MarkerioCustomDataEventInterface $event): void
    {
        $event->mergeData([
            'channel' => [
                'code' => $this->shopperContext->getChannel()?->getCode(),
                'name' => $this->shopperContext->getChannel()?->getName(),
            ],
            'customer' => [
                'id' => $this->shopperContext->getCustomer()?->getId(),
                'email' => $this->shopperContext->getCustomer()?->getEmail(),
            ],
            'locale' => $this->shopperContext->getLocaleCode(),
            'currency' => $this->shopperContext->getCurrencyCode(),
            'cart' => $this->getCartData(),
        ]);
    }

    private function getCartData(): ?array
    {
        $cart = $this->cartContext->getCart();
        $items = [];
        foreach ($cart->getItems() as $item) {
            $items[] = [
                'id' => $item->getId(),
                'variant' => [
                    'id' => $item->getVariant()?->getId(),
                    'code' => $item->getVariant()?->getCode(),
                    'name' => $item->getVariant()?->getName(),
                    'on_hand' => $item->getVariant()?->getOnHand(),
                    'on_hold' => $item->getVariant()?->getOnHold(),
                ],
                'quantity' => $item->getQuantity(),
                'unit_price' => $item->getUnitPrice(),
                'original_unit_price' => $item->getOriginalUnitPrice(),
                'total' => $item->getTotal(),
            ];
        }

        return [
            'total' => $cart->getTotal(),
            'items' => $items,
        ];
    }
}
