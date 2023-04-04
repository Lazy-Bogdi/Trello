<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TaskList;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('task_name')
            ->add('description')
            ->add('users', EntityType::class, [
                // looks for choices from this entity
                'class' => User::class,
            
                // uses the User.email property as the visible option string
                'choice_label' => 'email',
                'mapped' => true,
            
                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('task_list', EntityType::class, [
                // looks for choices from this entity
                'class' => TaskList::class,
                'empty_data' => null,
                'placeholder' => 'Tasklist Actuelle',
                'required'=>false,
            
                // uses the User.email property as the visible option string
                'choice_label' => 'id',
                'mapped' => true,
            
                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,
            ])            

            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
