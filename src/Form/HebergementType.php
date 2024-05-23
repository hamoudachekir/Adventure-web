<?php

namespace App\Form;

use App\Entity\HostService;
use App\Entity\Hebergement;
use App\Entity\TypeHebergement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HebergementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
            ])
            ->add('description', TextareaType::class, [
            ])
            ->add('prixAdulte', TextType::class, [
            ])
            ->add('prixJeune', TextType::class, [
            ])
            ->add('prixEnfant', TextType::class, [
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Address',

            ])
            ->add('nbrPlace', NumberType::class, [
            ])
            ->add('TypeHebergement', EntityType::class, [
                'class' => TypeHebergement::class, // Classe de l'entité associée
                'choice_label' => 'nom', // Propriété de l'entité à afficher dans le champ
                'placeholder' => 'Choose the accommodation type',
            ])
            ->add('HostService', EntityType::class, [
                'class' => HostService::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choose the HostService',
            ])
            ->add('image',FileType::class,[
                'required' => false,
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hebergement::class,
        ]);
    }
}
