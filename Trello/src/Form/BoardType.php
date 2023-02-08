<?php

namespace App\Form;

use App\Entity\Board;
use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BoardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('board_name')
            ->add('user', EntityType::class, [
                // looks for choices from this entity
                'class' => User::class,
            
                // uses the User.email property as the visible option string
                'choice_label' => 'email',
                'mapped' => true,
            
                // used to render a select box, check boxes or radios
                'multiple' => true,
                // 'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Board::class,
        ]);
    }
}
