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
    protected $minRole;

    public function __construct($context, $class, $categoryClass, $minRole)
    {
        $this->context = $context;
        $this->class = $class;
        $this->categoryClass = $categoryClass;
        $this->minRole = $minRole;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$this->context->isGranted($this->minRole)) {
           $builder
                ->add('authorName', 'purified_text', array(
                    'label' => 'Name',
                    'attr' => array(
                        'class' => 'pascal form-control',
                        'placeholder' => 'Your name...'
                    )
                ))
                ->add('authorEmail', 'email', array(
                    'label' => 'Email',
                    'attr' => array(
                        'class' => 'form-control',
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
                    'class' => 'title form-control',
                    'placeholder' => 'Question title...'
                )
            ))
            ->add('body', 'purified_textarea', array(
                'label' => 'Question Details',
                'attr' => array(
                    'title' => 'Enter your message',
                    'class' => 'title form-control',
                    'style' => 'height: 125px;',
                    'placeholder' => 'Question details. Please provide as much detail as possible...'
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
