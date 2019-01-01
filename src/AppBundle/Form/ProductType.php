<?php

namespace AppBundle\Form;


use AppBundle\Entity\Author;
use AppBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('size', TextType::class)
            ->add('material', TextType::class)
            ->add('note', TextType::class)
            ->add('price', NumberType::class)
            ->add('picture', TextType::class)
            ->add('category', EntityType::class,
                [
                    'expanded' => true,
                    'class' => Category::class,
                    'choice_label' => 'name'
                ])
        ->add('author', EntityType::class,
        [
            'expanded' => true,
            'class' => Author::class,
            'choice_label' => 'name'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

}
