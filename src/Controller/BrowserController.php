<?php

/*
 * This file is part of Monsieur Biz' Menu plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusMenuPlugin\Controller;

use MonsieurBiz\SyliusMenuPlugin\Provider\BrowsableObjectProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class BrowserController extends AbstractController
{
    public function __construct(
        private BrowsableObjectProviderInterface $browsableObjectProvider
    ) {
    }

    public function listAction(
        Request $request,
    ): ?Response {
        $inputName = (string) $request->query->get('inputName', '');
        $inputValue = (string) $request->query->get('inputValue', '');
        $locale = (string) $request->query->get('locale', '');

        return $this->render('@MonsieurBizSyliusMenuPlugin/Admin/Browser/_modal.html.twig', [
            'urlProviders' => $this->browsableObjectProvider->getUrlProviders(),
            'inputName' => $inputName,
            'inputValue' => $inputValue,
            'locale' => $locale,
        ]);
    }

    public function listItemsAction(
        Request $request,
    ): ?Response {
        $providerCode = (string) $request->query->get('providerCode', '');
        $inputName = (string) $request->query->get('inputName', '');
        $inputValue = (string) $request->query->get('inputValue', '');
        $locale = (string) $request->query->get('locale', '');

        $urlProvider = $this->browsableObjectProvider->findProviderByCode($providerCode);
        if (null === $urlProvider) {
            return new JsonResponse(['error' => 'URL Provider not found'], 404);
        }

        return $this->render('@MonsieurBizSyliusMenuPlugin/Admin/Browser/_modal.html.twig', [
            'urlProvider' => $urlProvider,
            'inputName' => $inputName,
            'inputValue' => $inputValue,
            'locale' => $locale,
        ]);
    }
}
