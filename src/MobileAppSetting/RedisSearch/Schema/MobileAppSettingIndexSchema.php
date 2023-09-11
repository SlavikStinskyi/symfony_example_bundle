<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Schema;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;
use GinCms\Bundle\RedisSearchBundle\Index\Document\Field\Collection\DocumentFieldCollection;
use GinCms\Bundle\RedisSearchBundle\Index\Document\Field\DocumentField;
use GinCms\Bundle\RedisSearchBundle\Index\Document\IndexDocument;
use GinCms\Bundle\RedisSearchBundle\Index\Document\IndexDocumentInterface;
use GinCms\Bundle\RedisSearchBundle\Index\Schema\Field\SchemaFieldTypeDictionary;
use GinCms\Bundle\RedisSearchBundle\StringNormalizer\StringNormalizerInterface;

class MobileAppSettingIndexSchema implements MobileAppSettingIndexSchemaInterface
{
    public function __construct(
        private readonly StringNormalizerInterface $stringNormalizer,
        private readonly string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSchemaDefinition(): array
    {
        return [
            $this->getFieldName() => SchemaFieldTypeDictionary::TYPE_TEXT,
            $this->getFieldOperationSystem() => SchemaFieldTypeDictionary::TYPE_TEXT,
            $this->getFieldBannedRefCodes() => SchemaFieldTypeDictionary::TYPE_TAGS,
            $this->getFieldIsEnabled() => SchemaFieldTypeDictionary::TYPE_TAGS,
            $this->getFieldRate() => SchemaFieldTypeDictionary::TYPE_NUMERIC,
        ];
    }

    public function getFieldName(): string
    {
        return self::FIELD_NAME;
    }

    public function getFieldOperationSystem(): string
    {
        return self::FIELD_OPERATION_SYSTEM;
    }

    public function getFieldBannedRefCodes(): string
    {
        return self::FIELD_BANNED_REF_CODES;
    }

    public function getFieldIsEnabled(): string
    {
        return self::FIELD_IS_ENABLED;
    }

    public function getFieldRate(): string
    {
        return self::FIELD_RATE;
    }

    public function extractDocument(MobileAppSettingInterface $mobileAppSetting): IndexDocumentInterface
    {
        $definition = $this->getSchemaDefinition();

        $fields = [
            new DocumentField(
                $this->getFieldName(),
                $definition[self::FIELD_NAME],
                $this->stringNormalizer->normalize($mobileAppSetting->getName())
            ),
            new DocumentField(
                $this->getFieldOperationSystem(),
                $definition[self::FIELD_OPERATION_SYSTEM],
                $mobileAppSetting->getOperationSystem()->value
            ),
            new DocumentField(
                $this->getFieldBannedRefCodes(),
                $definition[self::FIELD_BANNED_REF_CODES],
                $mobileAppSetting->getBannedRefCodes()->toArray()
            ),
            new DocumentField(
                $this->getFieldIsEnabled(),
                $definition[self::FIELD_IS_ENABLED],
                [$mobileAppSetting->isEnabled() ? '1' : '0']
            ),
            new DocumentField(
                $this->getFieldRate(),
                $definition[self::FIELD_RATE],
                $mobileAppSetting->getAppRate()
            ),
        ];

        return new IndexDocument($this, $mobileAppSetting->getId(), new DocumentFieldCollection($fields));
    }
}
