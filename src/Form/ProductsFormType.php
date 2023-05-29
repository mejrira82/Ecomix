<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Products;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Positive;

class ProductsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: [
                'label' => 'Name',
                'required' => false
            ])
            ->add('description')
            ->add('price',MoneyType::class, options: [
                'label' => 'Price',
                'divisor'=>100,
                'constraints' =>[
                    new Positive(
                        message:'Price cant be negative'
                    )
                ]
            ])
            ->add('stock', options: [
                'label' => 'Stock',
                'required' => false
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'group_by' => 'parent.name',
                'query_builder' => function (CategoriesRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->where('c.parent IS NOT NULL')
                        ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new All(
                        new Image([
                            'maxWidth' => 1500,
                            'maxWidthMessage' => 'Max imag width is {{ max_width }} px'
                        ])
                    )
                ]
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}