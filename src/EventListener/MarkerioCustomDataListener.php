<?php

/*
 * This file is part of Monsieur Biz's  for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMarkerioPlugin\EventListener;

use App\Entity\Order\OrderItem;
use App\Entity\Product\ProductVariant;
use App\Entity\User\AdminUser;
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
    private ?AbstractToken $adminToken = null;

    public function __construct(
        private readonly ShopperContextInterface $shopperContext,
        private readonly CartContextInterface $cartContext,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function appendCustomData(MarkerioCustomDataEventInterface $event): void
    {
        if ($this->isAdminUserLoggedIn()) {
            /** @var AdminUser|null $adminUser */
            $adminUser = $this->getAdminToken()?->getUser();
            $event->mergeData([
                'admin' => [
                    'user' => [
                        'id' => $adminUser?->getId(),
                        'username' => $adminUser?->getUsername(),
                        'roles' => $adminUser?->getRoles(),
                    ],
                ],
            ]);
        }
        $event->mergeData([
            'front' => [
                'channel' => [
                    'code' => $this->shopperContext->getChannel()->getCode(),
                    'name' => $this->shopperContext->getChannel()->getName(),
                ],
                'customer' => [
                    'id' => $this->shopperContext->getCustomer()?->getId(),
                    'email' => $this->shopperContext->getCustomer()?->getEmail(),
                ],
                'currency' => $this->shopperContext->getCurrencyCode(),
                'cart' => $this->getCartData(),
            ],
            'locale' => $this->shopperContext->getLocaleCode(),
        ]);
    }

    private function getCartData(): array
    {
        $cart = $this->cartContext->getCart();
        $items = [];
        /** @var OrderItem $item */
        foreach ($cart->getItems() as $item) {
            /** @var ProductVariant|null $variant */
            $variant = $item->getVariant();
            $items[] = [
                'id' => $item->getId(),
                'variant' => [
                    'id' => $variant?->getId(),
                    'code' => $variant?->getCode(),
                    'name' => $variant?->getName(),
                    'on_hand' => $variant?->getOnHand(),
                    'on_hold' => $variant?->getOnHold(),
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
            // @phpstan-ignore-next-line
            $this->adminToken = unserialize((string) $session->get('_security_admin'));
        }

        // @phpstan-ignore-next-line
        return $this->adminToken;
    }
}
