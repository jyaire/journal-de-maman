<?php

namespace App\Form;

use App\Entity\Journaux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('datedebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Jour de début',
            ])
            ->add('datefin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Jour de fin',
            ])
            ->add('couleur', ChoiceType::class, [
                'choices'  => [
                    'noire' => 'noir',
                    'bleue' => 'bleu',
                    'rouge' => 'rouge',
                    'verte' => 'vert',
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'Couverture',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Journaux::class,
        ]);
    }
}
