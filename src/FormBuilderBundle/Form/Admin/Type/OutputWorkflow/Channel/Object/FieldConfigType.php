<?php

namespace FormBuilderBundle\Form\Admin\Type\OutputWorkflow\Channel\Object;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FormBuilderBundle\Form\Admin\Type\OutputWorkflow\Channel\Object\Worker\FieldCollectionWorkerType;

class FieldConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('worker', ChoiceType::class, ['choices' => ['fieldCollectionWorker' => 'fieldCollectionWorker']]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if (isset($data['worker'])) {
                $this->addWorker($data['worker'], $form);
            }
        });
    }

    protected function addWorker(string $workerType, FormInterface $form): void
    {
        if ($workerType === 'fieldCollectionWorker') {
            $form->add('workerData', FieldCollectionWorkerType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'config_type'       => null,
            'field_config_type' => null,
        ]);
    }
}
