<?php


namespace App\Form;


use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{

    public function __construct(\Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $storage)
    {
        $this->user = $storage->getToken()->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
        ;


        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $task = $event->getData();
            $form = $event->getForm();

            $month = $task->getDuDate()->format('m');

            if($month == 'april') {
                $task->setPriority('top prio');
            }
        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $form = $event->getForm();

            if($this->user->isNotAdmin) {
                $form->remove('username');
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Author::class
        ]);
    }
}
