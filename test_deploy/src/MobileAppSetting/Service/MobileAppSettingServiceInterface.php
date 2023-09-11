<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Service;

use GameInspire\Result\ResultInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;

interface MobileAppSettingServiceInterface
{
    public function getById(string $id): ?MobileAppSettingInterface;

    /**
     * @return \Iterator<MobileAppSettingInterface>
     */
    public function getAll(): \Traversable;

    public function create(MobileAppSettingInterface $appSetting): ResultInterface;

    public function replace(
        MobileAppSettingInterface $currentEntity,
        MobileAppSettingInterface $newEntity,
    ): ResultInterface;

    public function remove(MobileAppSettingInterface $appSetting): ResultInterface;

    public function copy(MobileAppSettingInterface $appSetting): ResultInterface;
}
