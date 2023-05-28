<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Products;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options: [
                "attr" => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Name'
                ],
            ])
            ->add('description', options: [
                "attr" => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Description'
                ],
            ])
            ->add('price', options: [
                "attr" => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Price'
                ],
            ])
            ->add('stock', options: [
                "attr" => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Stock'
                ],
            ])
            ->add('categories', EntityType::class, options: [
                "attr" => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Stock',
                ],
                'class' => Categories::class,
                'choice_label' => 'name',
                'group_by' => 'parent.name',
                'query_builder' => function (CategoriesRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->where('c.parent IS NOT NULL')
                        ->OrderBy('c.name','ASC');
                }
            ])
            ->add('images',FileType::class,options:[
                "attr"=>[
                    'class'=>"form-control mb-3",
                    'placeholder' => 'No file selected',
                    'label' => 'Images',
                ],
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}