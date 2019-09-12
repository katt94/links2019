<?php
/**
 * User type file.
*/

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserType.
 */
class UserType extends AbstractType
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
            ->add('firstName', TextType::class, [
                'label' => 'form.user.first_name',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.user.email',
                'required' => true,
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'form.user.password',
                'required' => false,
                'mapped' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'form.user.roles',
                'required' => true,
                'choices' => [
                    'form.user.roles.ROLE_USER' => User::ROLE_USER,
                    'form.user.roles.ROLE_LINK_EDITOR' => User::ROLE_LINK_EDITOR,
                    'form.user.roles.ROLE_ADMIN' => User::ROLE_ADMIN,
                ],
                'expanded' => true,
                'multiple' => true,
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
            'data_class' => User::class,
        ]);
    }
}
