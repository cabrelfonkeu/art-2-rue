<?php

namespace App\Form;

use App\Entity\Oeuvre;
use App\Entity\Photo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fichier', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => $options['fichier_obligatoire'],
                'constraints' => [
                    new File(
                        maxSize: '5M',
                        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                        mimeTypesMessage: 'Formats acceptés : JPEG, PNG, WebP',
                    ),
                ],
            ])
            ->add('dateprisevue', DateTimeType::class, [
                'label' => 'Date de prise de vue',
                'widget' => 'single_text',
            ])
            ->add('etatoeuvre', TextType::class, [
                'label' => 'État de l\'œuvre',
                'required' => false,
            ])
            ->add('oeuvre', EntityType::class, [
                'label' => 'Œuvre',
                'class' => Oeuvre::class,
                'choice_label' => 'titre',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
            'fichier_obligatoire' => true,
        ]);
    }
}
