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
     * @param FormBuilderInterface $builder
     * @param array                $options
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
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Link::class,
            'inherit_data' => true,
        ]);
    }
}
