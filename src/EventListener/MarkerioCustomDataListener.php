<?php

/*
 * This file is part of Monsieur Biz's  for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMarkerioPlugin\EventListener;

use MonsieurBiz\SyliusMarkerioPlugin\Event\MarkerioCustomDataEvent;
use MonsieurBiz\SyliusMarkerioPlugin\Event\MarkerioCustomDataEventInterface;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

#[AsEventListener(MarkerioCustomDataEvent::class, 'appendCustomData', 100)]
final class MarkerioCustomDataListener
{
    private mixed $adminToken = null;

    public function __construct(
        private readonly ShopperContextInterface $shopperContext,
        private readonly CartContextInterface $cartContext,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function appendCustomData(MarkerioCustomDataEventInterface $event): void
    {
        if ($this->isAdminUserLoggedIn()) {
            $event->mergeData([
                'admin' => true,
                'shop' => false,
                'admin_user' => [
                    'id' => $this->getAdminToken()?->getUser()?->getId(),
                    'username' => $this->getAdminToken()?->getUser()?->getUsername(),
                    'roles' => $this->getAdminToken()?->getUser()?->getRoles(),
                ],
            ]);
        } else {
            $event->mergeData([
                'admin' => false,
                'shop' => true,
                'channel' => [
                    'code' => $this->shopperContext->getChannel()?->getCode(),
                    'name' => $this->shopperContext->getChannel()?->getName(),
                ],
                'customer' => [
                    'id' => $this->shopperContext->getCustomer()?->getId(),
                    'email' => $this->shopperContext->getCustomer()?->getEmail(),
                ],
                'currency' => $this->shopperContext->getCurrencyCode(),
                'cart' => $this->getCartData(),
            ]);
        }
        $event->mergeData([
            'locale' => $this->shopperContext->getLocaleCode(),
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

    private function isAdminUserLoggedIn(): bool
    {
        return null !== $this->getAdminToken() && null !== $this->getAdminToken()->getUser();
    }

    private function getAdminToken(): ?AbstractToken
    {
        $session = $this->requestStack->getSession();
        if (!$session->has('_security_admin')) {
            return null;
        }

        if (null === $this->adminToken) {
            $this->adminToken = unserialize($session->get('_security_admin'));
        }

        return $this->adminToken;
    }
}
