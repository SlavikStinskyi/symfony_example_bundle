<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\EventListener;

use GinCms\Bundle\AdminBundle\Menu\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    public function onMenuConfigure(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu();
        $mobileMenu = $menu->addChild('Mobile App');
        $mobileMenu->setAttribute('dropdown', true);
        $mobileMenu->addChild('App List', ['route' => 'mobile_app_settings_backend']);
//        $mobileMenu->addChild('Promo Settings', ['route' => 'mobile_app_settings_bundle_promo_list']);
    }
}
