<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\CentimesTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'required'   => false
                // 'constraints' => new NotBlank(['message' => 'Veuillez entrer un nom de produit'])
            ])  
            ->add('shortDecription', TextareaType::class,
            ['label' => 'Description courte',
            'required'   => false])

            ->add('price', MoneyType::class, [
                'label' => 'Prix du produit',
                'divisor' => 100,
                'required'   => false
                ])
            ->add('mainPicture', UrlType::class,
             [
            'label' => 'Image principale',
            'required'   => false,
            ])

            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'placeholder' => '--Choisir une catégorie--',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return strtoupper($category->getName());
                },
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
