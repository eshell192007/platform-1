<?php

namespace Oro\Bundle\SegmentBundle\Form\Type;

use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SegmentNameChoiceType extends AbstractType
{
    /** @var ManagerRegistry */
    protected $registry;

    /** @var string */
    protected $entityClass;

    /**
     * SegmentNameChoiceType constructor.
     * @param ManagerRegistry $registry
     * @param string          $entityClass
     */
    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        $this->registry = $registry;
        $this->entityClass = $entityClass;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('placeholder', 'oro.segment.form.segment_name_choice.placeholder');
        $resolver->setRequired('entityClass');
        $resolver->setNormalizer(
            'choices',
            function (OptionsResolver $options) {
                $repo = $this->registry->getManagerForClass($this->entityClass)->getRepository($this->entityClass);

                return $repo->findByEntity($options['entityClass']);
            }
        );
        $resolver->setAllowedTypes('entityClass', ['null', 'string']);
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
