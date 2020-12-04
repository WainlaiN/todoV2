<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\DBAL\Types\BooleanType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('createdAt')
            ->add('title',
            TextType::class,
                [ 'label' => 'Titre'
                ]
            )
            ->add('content',
            TextareaType::class,
                ['label' => 'Contenu']
            )
            ->add(
                'user',
                TextType::class,
                [
                    'attr' => [
                        'readonly' => true,
                    ],
                        'label' => 'Auteur',

                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Task::class,
            ]
        );
    }
}
