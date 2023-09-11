<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Service\Result;

class MobileAppSettingSearchServiceResult implements MobileAppSettingSearchServiceResultInterface
{
    public function __construct(
        private readonly array $mobileAppSettings,
        private readonly int $count,
    ) {
    }

    public function getMobileAppSettings(): array
    {
        return $this->mobileAppSettings;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function jsonSerialize(): array
    {
        return [
            self::FIELD_MOBILE_APP_SETTINGS => $this->mobileAppSettings,
            self::FIELD_COUNT => $this->count,
        ];
    }
}
