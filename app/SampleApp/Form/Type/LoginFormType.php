<?php
namespace SampleApp\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Constraints\UniqueEntry;

use Symfony\Component\Translation\Translator;

use Symfony\Component\Validator\ConstraintValidator;

use Symfony\Component\Validator\Constraints;


class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                    'required' => false,
                    'trim' => false,
                    'constraints' => array(
                        new NotBlank(array('message' => 'Name should not be blank.')),
                        new Length(array('min' => '4')),
                        new UniqueEntry(),
                    ),
                )
            )
            ->add('password', 'password', array(
                    'required' => false,
                    'trim' => false,
                    'constraints' => array(
                        new NotBlank(array('message' => 'Password should not be blank.')),
                        new Length(array('min' => '4')),
                    ),
                )
            );
    }

//     public function setDefaultOptions(OptionsResolverInterface $resolver) {
//         $collectionConstraint = new Collection(array(
//             'name' => array(
//                 new NotBlank(array('message' => 'Name should not be blank.')),
//                 new Length(array('min' => '4'))
//             ),
//             'password' => array(
//                 new NotBlank(array('message' => 'Password should not be blank.')),
//                 new Length(array('min' => '4'))
//             ),
//         ));
//
//         $resolver->setDefaults(array(
//             'constraints' => $collectionConstraint
//         ));
//     }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
        ));
    }
    public function getName()
    {
        return 'LoginForm';
    }
}