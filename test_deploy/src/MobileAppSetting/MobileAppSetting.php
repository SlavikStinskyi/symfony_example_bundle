<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting;

use Ds\Set;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Enum\OperationSystemEnum;

class MobileAppSetting implements MobileAppSettingInterface
{
    public function __construct(
        private readonly string $version,
        private readonly string $id,
        private readonly string $name,
        private readonly OperationSystemEnum $operationSystem,
        private readonly string $appLink,
        private readonly string $deeplink,
        private readonly Set $bannedRefCodes,
        private readonly \DateTimeImmutable $updatedAt,
        private readonly bool $isEnabled = false,
        private readonly ?float $appRate = null,
    ) {
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOperationSystem(): OperationSystemEnum
    {
        return $this->operationSystem;
    }

    public function getAppRate(): ?float
    {
        return $this->appRate;
    }

    public function getApplink(): string
    {
        return $this->appLink;
    }

    public function getDeeplink(): string
    {
        return $this->deeplink;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getBannedRefCodes(): Set
    {
        return $this->bannedRefCodes;
    }

    public function getReferencesTo(): Set
    {
        return new Set();
    }
}
