<?php
namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType; // Import UrlType
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libArt', TextType::class, [
                'label' => 'Article Name :',
            ])
            ->add('prixArt', MoneyType::class, [
                'label' => 'Price ',
            ])
            ->add('catArt', ChoiceType::class, [
                'label' => 'Category :',
                'choices' => [
                    'Electronics' => 'Electronics',
                    'Clothing' => 'Clothing',
                    'Books' => 'Books',
                    'Sports' => 'Sports',
                    'Home & Garden' => 'Home & Garden',
                ],
            ])
            ->add('imageUrl', UrlType::class, [ 
                'label' => 'Image URL:',
                'required' => false, 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
