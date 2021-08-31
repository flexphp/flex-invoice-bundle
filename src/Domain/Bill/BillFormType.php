<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill;

use App\Form\Type\DatetimepickerType;
use App\Form\Type\Select2Type;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\ReadBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\UseCase\ReadBillStatusUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Request\ReadBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\UseCase\ReadBillTypeUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\ReadBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\UseCase\ReadBillUseCase;
use Domain\Order\Request\ReadOrderRequest;
use Domain\Order\UseCase\ReadOrderUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\Request\ReadProviderRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Provider\UseCase\ReadProviderUseCase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class BillFormType extends AbstractType
{
    private ReadOrderUseCase $readOrderUseCase;

    private ReadProviderUseCase $readProviderUseCase;

    private ReadBillStatusUseCase $readBillStatusUseCase;

    private ReadBillTypeUseCase $readBillTypeUseCase;

    private ReadBillUseCase $readBillUseCase;

    private UrlGeneratorInterface $router;

    public function __construct(
        ReadOrderUseCase $readOrderUseCase,
        ReadProviderUseCase $readProviderUseCase,
        ReadBillStatusUseCase $readBillStatusUseCase,
        ReadBillTypeUseCase $readBillTypeUseCase,
        ReadBillUseCase $readBillUseCase,
        UrlGeneratorInterface $router
    ) {
        $this->readOrderUseCase = $readOrderUseCase;
        $this->readProviderUseCase = $readProviderUseCase;
        $this->readBillStatusUseCase = $readBillStatusUseCase;
        $this->readBillTypeUseCase = $readBillTypeUseCase;
        $this->readBillUseCase = $readBillUseCase;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $orderIdModifier = function (FormInterface $form, ?int $value): void {
            $choices = null;

            if (!empty($value)) {
                $response = $this->readOrderUseCase->execute(new ReadOrderRequest($value));

                if ($response->order->id()) {
                    $choices = [$response->order->id() => $value];
                }
            }

            $form->add('orderId', Select2Type::class, [
                'label' => 'label.orderId',
                'required' => true,
                'attr' => [
                    'data-autocomplete-url' => $this->router->generate('bills.find.orders'),
                ],
                'choices' => $choices,
                'data' => $value,
            ]);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($orderIdModifier) {
            if (!$event->getData()) {
                return null;
            }

            $orderIdModifier($event->getForm(), $event->getData()->orderId());
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($orderIdModifier): void {
            $orderIdModifier($event->getForm(), (int)$event->getData()['orderId'] ?: null);
        });

        $providerModifier = function (FormInterface $form, ?string $value): void {
            $choices = null;

            if (!empty($value)) {
                $response = $this->readProviderUseCase->execute(new ReadProviderRequest($value));

                if ($response->provider->id()) {
                    $choices = [$response->provider->name() => $value];
                }
            }

            $form->add('provider', Select2Type::class, [
                'label' => 'label.provider',
                'required' => true,
                'attr' => [
                    'data-autocomplete-url' => $this->router->generate('bills.find.providers'),
                ],
                'choices' => $choices,
                'data' => $value,
            ]);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($providerModifier) {
            if (!$event->getData()) {
                return null;
            }

            $providerModifier($event->getForm(), $event->getData()->provider());
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($providerModifier): void {
            $providerModifier($event->getForm(), (string)$event->getData()['provider'] ?: null);
        });

        $statusModifier = function (FormInterface $form, ?string $value): void {
            $choices = null;

            if (!empty($value)) {
                $response = $this->readBillStatusUseCase->execute(new ReadBillStatusRequest($value));

                if ($response->billStatus->id()) {
                    $choices = [$response->billStatus->name() => $value];
                }
            }

            $form->add('status', Select2Type::class, [
                'label' => 'label.status',
                'required' => true,
                'attr' => [
                    'data-autocomplete-url' => $this->router->generate('bills.find.bill-status'),
                ],
                'choices' => $choices,
                'data' => $value,
            ]);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($statusModifier) {
            if (!$event->getData()) {
                return null;
            }

            $statusModifier($event->getForm(), $event->getData()->status());
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($statusModifier): void {
            $statusModifier($event->getForm(), (string)$event->getData()['status'] ?: null);
        });

        $typeModifier = function (FormInterface $form, ?string $value): void {
            $choices = null;

            if (!empty($value)) {
                $response = $this->readBillTypeUseCase->execute(new ReadBillTypeRequest($value));

                if ($response->billType->id()) {
                    $choices = [$response->billType->name() => $value];
                }
            }

            $form->add('type', Select2Type::class, [
                'label' => 'label.type',
                'required' => true,
                'attr' => [
                    'data-autocomplete-url' => $this->router->generate('bills.find.bill-types'),
                ],
                'choices' => $choices,
                'data' => $value,
            ]);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($typeModifier) {
            if (!$event->getData()) {
                return null;
            }

            $typeModifier($event->getForm(), $event->getData()->type());
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($typeModifier): void {
            $typeModifier($event->getForm(), (string)$event->getData()['type'] ?: null);
        });

        $parentIdModifier = function (FormInterface $form, ?int $value): void {
            $choices = null;

            if (!empty($value)) {
                $response = $this->readBillUseCase->execute(new ReadBillRequest($value));

                if ($response->bill->id()) {
                    $choices = [$response->bill->number() => $value];
                }
            }

            $form->add('parentId', Select2Type::class, [
                'label' => 'label.parentId',
                'required' => false,
                'attr' => [
                    'data-autocomplete-url' => $this->router->generate('bills.find.bills'),
                ],
                'choices' => $choices,
                'data' => $value,
            ]);
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($parentIdModifier) {
            if (!$event->getData()) {
                return null;
            }

            $parentIdModifier($event->getForm(), $event->getData()->parentId());
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($parentIdModifier): void {
            $parentIdModifier($event->getForm(), (int)$event->getData()['parentId'] ?: null);
        });

        $builder->add('number', InputType\TextType::class, [
            'label' => 'label.number',
            'required' => true,
        ]);
        $builder->add('orderId', Select2Type::class, [
            'label' => 'label.orderId',
            'required' => true,
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('bills.find.orders'),
            ],
        ]);
        $builder->add('provider', Select2Type::class, [
            'label' => 'label.provider',
            'required' => true,
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('bills.find.providers'),
            ],
        ]);
        $builder->add('status', Select2Type::class, [
            'label' => 'label.status',
            'required' => true,
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('bills.find.bill-status'),
                'maxlength' => 2,
            ],
        ]);
        $builder->add('type', Select2Type::class, [
            'label' => 'label.type',
            'required' => true,
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('bills.find.bill-types'),
                'maxlength' => 3,
            ],
        ]);
        $builder->add('traceId', InputType\TextType::class, [
            'label' => 'label.traceId',
            'required' => false,
        ]);
        $builder->add('hash', InputType\TextType::class, [
            'label' => 'label.hash',
            'required' => false,
        ]);
        $builder->add('hashType', InputType\TextType::class, [
            'label' => 'label.hashType',
            'required' => false,
            'attr' => [
                'maxlength' => 20,
            ],
        ]);
        $builder->add('message', InputType\TextType::class, [
            'label' => 'label.message',
            'required' => false,
            'attr' => [
                'maxlength' => 1024,
            ],
        ]);
        $builder->add('pdfPath', InputType\TextType::class, [
            'label' => 'label.pdfPath',
            'required' => false,
            'attr' => [
                'maxlength' => 1024,
            ],
        ]);
        $builder->add('xmlPath', InputType\TextType::class, [
            'label' => 'label.xmlPath',
            'required' => false,
            'attr' => [
                'maxlength' => 1024,
            ],
        ]);
        $builder->add('parentId', Select2Type::class, [
            'label' => 'label.parentId',
            'required' => false,
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('bills.find.bills'),
            ],
        ]);
        $builder->add('downloadedAt', DatetimepickerType::class, [
            'label' => 'label.downloadedAt',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'bill',
        ]);
    }
}
