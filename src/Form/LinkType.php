<?php
/**
 * Link type file.
 */

namespace App\Form;

use App\Entity\Link;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LinkType.
 */
class LinkType extends AbstractType
{
    /**
     * Tags data transformer.
     *
     * @var TagsDataTransformer|null
     */
    private $tagsDataTransformer = null;

    /**
     * TaskType constructor.
     *
     * @param TagsDataTransformer $tagsDataTransformer Tags data transformer
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
    }

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
            ->add('url', TextType::class, [
                'required' => true,
                'label' => 'form.tag.url',
            ])
            ->add(
                'tags',
                TextType::class,
                [
                    'by_reference' => false,
                    'label' => 'form.tags.name',
                    'required' => false,
                    'attr' => [
                        'max_length' => 255,
                    ],
                ]
            )
        ;

        $builder->get('tags')->addModelTransformer(
            $this->tagsDataTransformer
        );
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
            'inherit_data' => true,
        ]);
    }
}
