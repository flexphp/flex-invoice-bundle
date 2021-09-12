<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Numeration;

use App\Form\Type\DatepickerType;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\BillType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class NumerationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('type', InputType\ChoiceType::class, [
            'label' => 'label.type',
            'required' => true,
            'choices' => [
                BillType::INVOICE => BillType::INVOICE,
                BillType::CREDIT => BillType::CREDIT,
                BillType::DEBIT => BillType::DEBIT,
            ],
        ]);

        $builder->add('resolution', InputType\TextType::class, [
            'label' => 'label.resolution',
            'required' => true,
            'attr' => [
                'maxlength' => 255,
            ],
        ]);

        $builder->add('startAt', DatepickerType::class, [
            'label' => 'label.startAt',
            'required' => true,
        ]);

        $builder->add('finishAt', DatepickerType::class, [
            'label' => 'label.finishAt',
            'required' => true,
        ]);

        $builder->add('prefix', InputType\TextType::class, [
            'label' => 'label.prefix',
            'required' => true,
            'attr' => [
                'maxlength' => 255,
            ],
        ]);

        $builder->add('fromNumber', InputType\IntegerType::class, [
            'label' => 'label.fromNumber',
            'required' => true,
        ]);

        $builder->add('toNumber', InputType\IntegerType::class, [
            'label' => 'label.toNumber',
            'required' => true,
        ]);

        $builder->add('currentNumber', InputType\IntegerType::class, [
            'label' => 'label.currentNumber',
            'required' => true,
        ]);

        $builder->add('isActive', InputType\CheckboxType::class, [
            'label' => 'label.isActive',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'numeration',
        ]);
    }
}
