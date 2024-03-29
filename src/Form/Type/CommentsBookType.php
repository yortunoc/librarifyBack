<?php

namespace App\Form\Type;

use App\Form\Model\CommentsBookDto;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentsBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class)
            ->add('comment', TextareaType::class)
            ->add('id_book', TextType::class)
            ->add('id_user', TextType::class)
        ;
        $builder->get('id')->addModelTransformer(new CallbackTransformer(
            function ($id) {
                if ($id === null) {
                    return '';
                }
                return $id->toString();
            },
            function ($id) {
                return $id === null ? null : Uuid::fromString($id);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentsBookDto::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function getName()
    {
        return '';
    }
}