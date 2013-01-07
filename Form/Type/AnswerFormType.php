<?php
namespace Avro\SupportBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/*
 * Answer Form Type
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class AnswerFormType extends AbstractType
{
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body', 'purified_textarea', array(
                'label' => 'Reply',
                'attr' => array(
                    'title' => 'Respond to this question',
                    'placeholder' => 'Respond to this question...',
                    'class' => '',
                    'style' => 'width: 98%; min-height: 150px;'
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class
        ));
    }

    public function getName()
    {
        return 'avro_support_answer';
    }
}
