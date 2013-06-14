<?php
namespace Avro\SupportBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/*
 * Question Form Type
 *
 * @author Joris de Wit <joris.w.dewit@gmail.com>
 */
class QuestionFormType extends AbstractType
{
    protected $context;
    protected $class;
    protected $categoryClass;

    public function __construct($context, $class, $categoryClass)
    {
        $this->context = $context;
        $this->class = $class;
        $this->categoryClass = $categoryClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$this->context->isGranted("ROLE_USER")) {
           $builder
                ->add('authorName', 'purified_text', array(
                    'label' => 'Name',
                    'attr' => array(
                        'class' => 'pascal input-large',
                        'placeholder' => 'Your name...'
                    )
                ))
                ->add('authorEmail', 'email', array(
                    'label' => 'Email',
                    'attr' => array(
                        'class' => 'input-large',
                        'placeholder' => 'Your email address...'
                    )
                ))
            ;
        }

        $builder
            ->add('title', 'purified_text', array(
                'label' => 'Title',
                'attr' => array(
                    'title' => 'Enter the title',
                    'class' => 'title input-max',
                    'placeholder' => 'Question title...'
                )
            ))
            ->add('body', 'purified_textarea', array(
                'label' => 'Question Details',
                'attr' => array(
                    'title' => 'Enter your message',
                    'class' => 'title input-max',
                    'style' => 'height: 125px;',
                    'placeholder' => 'Question details. Please provide as much context as possible...'
                )
            ))
            //->add('categorys', 'document', array(
                //'label' => 'Categories',
                //'class' => $this->categoryClass,
                //'multiple' => true,
                //'required' => false
            //))
            ->add('isPublic', 'checkbox', array(
                'label' => 'Show in public questions',
                'required' => false
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
        return 'avro_support_question';
    }
}
