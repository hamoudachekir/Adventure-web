<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductCat;
use App\Repository\ProductCatRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('quantity')
            ->add('category', EntityType::class, [
                'class' => ProductCat::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'query_builder' => function(ProductCatRepository $cr){
                    return $cr->createQueryBuilder('c');
                }
                ])
            ->add('image',FileType::class,array('data_class' => null) , [
                'label' => 'Image',
                'required' => false,
                'multiple' => false,
                'constraints' => [
                    new All(
                        new Image([
                            'maxWidth' => 1280,
                            'maxWidthMessage' => 'L\'image doit faire {{ max_width }} pixels de large au maximum'
                        ])
                        ),
                        NotBlank::class
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
