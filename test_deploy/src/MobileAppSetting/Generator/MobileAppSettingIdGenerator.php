<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Generator;

use GameInspire\IdGenerator\IdGeneratorInterface;

class MobileAppSettingIdGenerator implements MobileAppSettingIdGeneratorInterface
{
    private const ID_TEMPLATE = '%s:%s';

    public function __construct(
        private readonly IdGeneratorInterface $idGenerator,
        private readonly string $entityType,
    ) {
    }

    public function generateId(): string
    {
        return $this->idGenerator->generateId();
    }

    public function generateEntityId(): string
    {
        return \sprintf(self::ID_TEMPLATE, $this->entityType, $this->idGenerator->generateId());
    }
}
