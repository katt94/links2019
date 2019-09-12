<?php
/**
 * Link with user type file.
 */

namespace App\Form;

use App\Entity\Link;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LinkWithUserType.
 */
class LinkWithUserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, [
                'label' => 'form.tag.user',
                'class' => User::class,
                'expanded' => false,
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles NOT LIKE :roles')
                        ->setParameter('roles', '%ROLE_ADMIN%')
                        ->orderBy('u.email', 'ASC');
                },
                'placeholder' => 'form.user.select',
            ])
            ->add('link', LinkType::class, [
                'data_class' => Link::class,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Link::class,
        ]);
    }
}
