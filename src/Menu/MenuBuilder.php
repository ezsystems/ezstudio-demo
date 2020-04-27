<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Menu;

use App\Menu\Voter\LocationVoter;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuBuilder implements TranslationContainerInterface
{
    private const MAISON_MAIN = 'maison_main';
    private const MAISON_HEADER = 'maison_top';
    private const MAISON_FOOTER = 'maison_footer';

    private const TRANSLATION_DOMAIN = 'menu';
    private const ITEM_INSPIRATIONS = 'inspirations';
    private const ITEM_FIND_PROFESSIONALS = 'find_professionals';
    private const ITEM_BLOG = 'blog';
    private const ITEM_STORE_LOCATORS = 'store_locators';
    private const ITEM_CONNECT_WITH_US = 'connect_with_us';

    private const MENU_ITEMS = [
        self::ITEM_INSPIRATIONS,
        self::ITEM_FIND_PROFESSIONALS,
        self::ITEM_BLOG,
        self::ITEM_STORE_LOCATORS,
        self::ITEM_CONNECT_WITH_US
    ];

    /** @var \Knp\Menu\FactoryInterface */
    private $factory;

    /** @var \Symfony\Component\Routing\RouterInterface */
    private $router;

    /** @var \Symfony\Contracts\Translation\TranslatorInterface */
    private $translator;

    /** @var array */
    private $menuIncludedItems;

    public function __construct(
        FactoryInterface $factory,
        RouterInterface $router,
        TranslatorInterface $translator,
        array $menuIncludedItems
    ) {
        $this->factory = $factory;
        $this->router = $router;
        $this->translator = $translator;
        $this->menuIncludedItems = $menuIncludedItems;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function createMaisonMainMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        if (isset($this->menuIncludedItems[self::MAISON_MAIN])) {
            $menu->addChild('Products');
            $this->addChildren(
                $menu,
                $this->menuIncludedItems[self::MAISON_MAIN]
            );
        }

        return $menu;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function createMaisonHeaderMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        if (isset($this->menuIncludedItems[self::MAISON_HEADER])) {
            $menu->addChild('Customer Services');
            $this->addChildren(
                $menu,
                $this->menuIncludedItems[self::MAISON_HEADER]
            );
        }

        return $menu;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function createMaisonFooterMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        if (isset($this->menuIncludedItems[self::MAISON_FOOTER])) {
            $menu->addChild('Company');
            $menu->addChild('Business services');
            $menu->addChild('Get help');
            $this->addChildren(
                $menu,
                $this->menuIncludedItems[self::MAISON_FOOTER]
            );
        }

        return $menu;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function createProfessionalsMainMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Become a Pro');
        $menu->addChild('Login');

        return $menu;
    }

    /**
     * @param \Knp\Menu\ItemInterface $menu
     * @param array $menuItems
     */
    private function addChildren(ItemInterface $menu, array $menuItems): void
    {
        foreach ($menuItems as $name => $parameters) {
            $key = array_search($name, self::MENU_ITEMS);
            $menu->addChild($name, [
                'label' => self::MENU_ITEMS[$key],
                'route' => 'ez_urlalias',
                'routeAbsolute' => true,
                'routeParameters' => [
                    'locationId' => $parameters['location_id'],
                    'siteaccess' => $parameters['siteaccess'] ?? null
                ],
                'extras' => [
                    'type' => LocationVoter::ITEM_TYPE,
                    'icon' => $parameters['icon'] ?? null,
                    'translation_domain' => self::TRANSLATION_DOMAIN
                ]
            ]);
        }
    }
    public static function getTranslationMessages()
    {
        return [
            (new Message(self::ITEM_INSPIRATIONS, self::TRANSLATION_DOMAIN))->setDesc('Inspirations'),
            (new Message(self::ITEM_FIND_PROFESSIONALS, self::TRANSLATION_DOMAIN))->setDesc('Find professionals'),
            (new Message(self::ITEM_BLOG, self::TRANSLATION_DOMAIN))->setDesc('Blog'),
            (new Message(self::ITEM_STORE_LOCATORS, self::TRANSLATION_DOMAIN))->setDesc('Store locators'),
            (new Message(self::ITEM_CONNECT_WITH_US, self::TRANSLATION_DOMAIN))->setDesc('Connect with us'),
        ];
    }
}
