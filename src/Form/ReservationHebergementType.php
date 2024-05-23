<?php

namespace App\Form;

use App\Entity\ReservationHebergement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationHebergementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDeb', TextType::class, [
            ])
            ->add('dateFin', TextType::class, [
            ])
            ->add('nbrAdulte', NumberType::class, [
            ])
            ->add('nbrJeune', NumberType::class, [
            ])
            ->add('nbrEnfant', NumberType::class, [
            ])
            ;


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationHebergement::class,
        ]);
    }
}
