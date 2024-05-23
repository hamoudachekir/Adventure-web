<?php

namespace App\Form;

use App\Entity\HostService;
use App\Entity\Hebergement;
use App\Entity\TypeHebergement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HebergementFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeHebergement', EntityType::class, [
                'class' => TypeHebergement::class,
                'choice_label' => 'name', // Adjust this according to your TypeHebergement entity
                'required' => false,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('HostService', EntityType::class, [
                'class' => HostService::class,
                'choice_label' => 'name', // Adjust this according to your HostService entity
                'required' => false,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('prixMin', NumberType::class, [
                'required' => false,
            ])
            ->add('prixMax', NumberType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [ // Add the SubmitType field
                'label' => 'Filter', // You can customize the label
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hebergement::class,
        ]);
    }
}
