<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\CategoriesSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriesSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Categories',EntityType::class,['class' => Categories::class,
        'choice_label' => 'nom' ,
         'label' => 'Catégorie' ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoriesSearch::class,
        ]);
    }
}
