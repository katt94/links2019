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
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
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
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Link::class,
        ]);
    }
}
