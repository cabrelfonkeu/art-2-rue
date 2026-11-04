<?php

namespace App\Form;

use App\Entity\Artiste;
use App\Entity\Lieu;
use App\Entity\Oeuvre;
use App\Entity\Technique;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class OeuvreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, ['label' => 'Titre'])
            ->add('datecreation', DateType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['rows' => 4],
            ])
            ->add('datedestruction', DateType::class, [
                'label' => 'Date de destruction',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'required' => false,
            ])
            ->add('lieu', EntityType::class, [
                'label' => 'Lieu',
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Choisir un lieu existant',
            ])
            ->add('artistes', EntityType::class, [
                'label' => 'Artistes',
                'class' => Artiste::class,
                'choice_label' => 'pseudonyme',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('techniques', EntityType::class, [
                'label' => 'Techniques',
                'class' => Technique::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('oeuvreRecouverte', EntityType::class, [
                'label' => 'Œuvre recouverte',
                'class' => Oeuvre::class,
                'choice_label' => 'titre',
                'required' => false,
                'placeholder' => 'Aucune',
            ]);

        if ($options['avec_photo']) {
            $builder
                ->add('fichier_photo', FileType::class, [
                    'label' => 'Photo de l\'œuvre (facultatif)',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File(
                            maxSize: '5M',
                            mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                            mimeTypesMessage: 'Formats acceptés : JPEG, PNG, WebP',
                        ),
                    ],
                ])
                ->add('etat_photo', TextType::class, [
                    'label' => 'État de l\'œuvre sur la photo',
                    'mapped' => false,
                    'required' => false,
                ])
                ->add('nouveau_lieu_nom', TextType::class, [
                    'label' => 'Ou nouveau lieu : nom',
                    'mapped' => false,
                    'required' => false,
                ])
                ->add('nouveau_lieu_adresse', TextType::class, [
                    'label' => 'Nouveau lieu : adresse',
                    'mapped' => false,
                    'required' => false,
                ])
                ->add('nouveau_lieu_quartier', TextType::class, [
                    'label' => 'Nouveau lieu : quartier',
                    'mapped' => false,
                    'required' => false,
                ])
                ->add('nouveau_lieu_latitude', TextType::class, [
                    'label' => 'Nouveau lieu : latitude',
                    'mapped' => false,
                    'required' => false,
                ])
                ->add('nouveau_lieu_longitude', TextType::class, [
                    'label' => 'Nouveau lieu : longitude',
                    'mapped' => false,
                    'required' => false,
                ])
                ->add('nouveaux_artistes', TextType::class, [
                    'label' => 'Nouveaux artistes (pseudonymes séparés par des virgules)',
                    'mapped' => false,
                    'required' => false,
                ])
                ->add('nouvelles_techniques', TextType::class, [
                    'label' => 'Nouvelles techniques (séparées par des virgules)',
                    'mapped' => false,
                    'required' => false,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Oeuvre::class,
            'avec_photo' => false,
        ]);
    }
}
