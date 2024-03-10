<?php

/*
 * This file is part of Monsieur Biz's  for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMarkerioPlugin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MarkerioController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('@MonsieurBizSyliusMarkerioPlugin/markerio_metadata.html.twig');
    }
}
