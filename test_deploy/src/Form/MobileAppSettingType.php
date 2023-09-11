<?php

declare(strict_types=1);

namespace GinCms\Bundle\MobileAppSettingsBundle\Form;

use Ds\Set;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Enum\OperationSystemEnum;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Factory\MobileAppSettingFactoryInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\Generator\MobileAppSettingIdGeneratorInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSetting;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\MobileAppSettingInterface;
use GinCms\Bundle\MobileAppSettingsBundle\MobileAppSetting\RedisSearch\Search\Service\MobileAppSettingSearchServiceInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MobileAppSettingType extends AbstractType implements DataMapperInterface
{
    private const FIELD_ID = 'id';

    private const FIELD_NAME = 'name';

    private const FIELD_OPERATION_SYSTEM = 'operation_system';

    private const FIELD_APP_LINK = 'app_link';

    private const FIELD_DEEPLINK = 'deeplink';

    private const FIELD_BANNED_REF_CODES = 'banned_ref_codes';

    private const FIELD_IS_ENABLED = 'is_enabled';

    private const FIELD_RATE = 'app_rate';

    private bool $isNewSetting;

    public function __construct(
        private readonly MobileAppSettingFactoryInterface $factory,
        private readonly MobileAppSettingIdGeneratorInterface $idGenerator,
        private readonly MobileAppSettingSearchServiceInterface $searchService,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->isNewSetting = $options['is_new_setting'];

        $builder->add(
            self::FIELD_ID,
            HiddenType::class,
            ['required' => true]
        );

        $builder
            ->add(
                self::FIELD_NAME,
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                self::FIELD_APP_LINK,
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                self::FIELD_DEEPLINK,
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                self::FIELD_OPERATION_SYSTEM,
                EnumType::class,
                [
                    'class' => OperationSystemEnum::class,
                ]
            )
            ->add(self::FIELD_RATE, NumberType::class, ['required' => false])
            ->add(
                self::FIELD_BANNED_REF_CODES,
                CollectionType::class,
                [
                    'entry_type' => TextType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'required' => false,
                ]
            )
            ->add(
                self::FIELD_IS_ENABLED,
                CheckboxType::class,
                [
                    'required' => false,
                ]
            )
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => MobileAppSetting::class,
                'empty_data' => null,
                'csrf_protection' => false,
                'allow_extra_fields' => true,
                'constraints' => [
                    new Callback([$this, 'validateUniqueName']),
                    new Callback([$this, 'validateActiveAppPerOperationSystem']),
                ],
                'is_new_setting' => false,
            ]
        );

        $resolver->addAllowedTypes('is_new_setting', 'bool');
    }

    public function mapDataToForms($viewData, $forms): void
    {
        /** @var MobileAppSettingInterface|null $viewData */
        $formsMap = \iterator_to_array($forms);

        $formsMap[self::FIELD_ID]->setData($viewData ? $viewData->getId() : '');
        $formsMap[self::FIELD_NAME]->setData($viewData ? $viewData->getName() : '');
        $formsMap[self::FIELD_APP_LINK]->setData($viewData ? $viewData->getAppLink() : '');
        $formsMap[self::FIELD_DEEPLINK]->setData($viewData ? $viewData->getDeeplink() : '');
        $formsMap[self::FIELD_OPERATION_SYSTEM]->setData($viewData?->getOperationSystem());
        $formsMap[self::FIELD_RATE]->setData($viewData ? $viewData->getAppRate() : 0);
        $formsMap[self::FIELD_BANNED_REF_CODES]->setData($viewData ? $viewData->getBannedRefCodes()->toArray() : []);
        $formsMap[self::FIELD_IS_ENABLED]->setData($viewData ? $viewData->isEnabled() : false);
    }

    public function mapFormsToData($forms, &$viewData): void
    {
        /** @var MobileAppSettingInterface|null $viewData */
        $formsMap = \iterator_to_array($forms);

        $refCodesData = $formsMap[self::FIELD_BANNED_REF_CODES]->getData();
        $refCodesData = new Set($refCodesData);

        $viewData = $this->factory->createNew(
            $formsMap[self::FIELD_ID]->getData() ?: $this->idGenerator->generateEntityId(),
            (string) $formsMap[self::FIELD_NAME]->getData(),
            $formsMap[self::FIELD_OPERATION_SYSTEM]->getData(),
            (string) $formsMap[self::FIELD_APP_LINK]->getData(),
            (string) $formsMap[self::FIELD_DEEPLINK]->getData(),
            $refCodesData,
            (bool) $formsMap[self::FIELD_IS_ENABLED]->getData(),
            (float) $formsMap[self::FIELD_RATE]->getData(),
        );
    }

    public function validateActiveAppPerOperationSystem(
        MobileAppSettingInterface $appSetting,
        ExecutionContextInterface $context,
    ): void {
        if (false === $appSetting->isEnabled()) {
            return;
        }

        $searchResult = $this->searchService->findBy(
            operationSystem: $appSetting->getOperationSystem()->value,
            limit: 1,
            isEnabled: true
        );

        if ($searchResult->getCount() > 0) {
            $message = \sprintf(
                'Active mobile settings with `%s` operation system already exists',
                $appSetting->getOperationSystem()->value
            );

            $context->buildViolation($message)
                ->atPath(self::FIELD_OPERATION_SYSTEM)
                ->addViolation();
        }
    }

    public function validateUniqueName(
        MobileAppSettingInterface $appSetting,
        ExecutionContextInterface $context,
    ): void {
        // validate only for create form
        if (false === $this->isNewSetting) {
            return;
        }

        $searchResult = $this->searchService->findBy(
            name: $appSetting->getName(),
            limit: 1
        );

        if ($searchResult->getCount() > 0) {
            $message = \sprintf('Mobile settings with name `%s` already exists', $appSetting->getName());

            $context->buildViolation($message)
                ->atPath(self::FIELD_NAME)
                ->addViolation();
        }
    }
}
