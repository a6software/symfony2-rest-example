<?php

namespace CodeReview\RestBundle\Form\Handler;

use CodeReview\RestBundle\Exception\InvalidFormException;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;

class FormHandler
{
    private $em;
    private $formFactory;
    private $formType;

    public function __construct(ObjectManager $objectManager, FormFactoryInterface $formFactory, FormTypeInterface $formType)
    {
        $this->em = $objectManager;
        $this->formFactory = $formFactory;
        $this->formType = $formType;
    }

    public function processForm($object, array $parameters, $method)
    {
        $form = $this->formFactory->create($this->formType, $object, array(
            'method'            => $method,
            'csrf_protection'   => false,
        ));

        $form->submit($parameters, 'PATCH' !== $method);

        if ( ! $form->isValid()) {
            throw new InvalidFormException($form);
        }

        $data = $form->getData();
        $this->em->persist($data);
        $this->em->flush();

        return $data;
    }

    public function delete($object)
    {
        $this->em->remove($object);
        $this->em->flush();

        return true;
    }
}