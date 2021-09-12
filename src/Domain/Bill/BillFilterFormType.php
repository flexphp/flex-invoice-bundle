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

use App\Form\Type\DatefinishpickerType;
use App\Form\Type\DatestartpickerType;
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

final class BillFilterFormType extends AbstractType
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
        $builder->add('numeration', InputType\TextType::class, [
            'required' => false,
            'label' => 'label.numeration',
        ]);

        $builder->add('orderId', Select2Type::class, [
            'required' => false,
            'label' => 'label.orderId',
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('flexphp.invoice.bills.find.orders'),
            ],
        ]);

        $builder->add('provider', Select2Type::class, [
            'required' => false,
            'label' => 'label.provider',
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('flexphp.invoice.bills.find.providers'),
            ],
        ]);

        $builder->add('status', Select2Type::class, [
            'required' => false,
            'label' => 'label.status',
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('flexphp.invoice.bills.find.bill-status'),
                'maxlength' => 2,
            ],
        ]);

        $builder->add('type', Select2Type::class, [
            'required' => false,
            'label' => 'label.type',
            'attr' => [
                'data-autocomplete-url' => $this->router->generate('flexphp.invoice.bills.find.bill-types'),
                'maxlength' => 3,
            ],
        ]);

        $builder->add('createdAt_START', DatestartpickerType::class, [
            'label' => 'filter.createdAtStart',
        ]);

        $builder->add('createdAt_END', DatefinishpickerType::class, [
            'label' => 'filter.createdAtEnd',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'bill',
        ]);
    }
}
