<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\Graphql\Controller;

use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Dictionary\ErrorDictionary;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Storage\MobileAppSettingStorageInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TheCodingMachine\GraphQLite\Annotations\Query;

class GetMobileSettingsController
{
    public function __construct(
        private readonly MobileAppSettingStorageInterface $storage,
    ) {
    }

    #[Query(name: 'mobileSettings')]
    public function __invoke(Request $request)
    {
        if (null === $setting = $this->storage->getById($id)) {
            return $this->apiResponseFactory->createError(
                ErrorDictionary::API_NOT_FOUND,
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        return $this->apiResponseFactory->createSuccess($setting);
    }
}
