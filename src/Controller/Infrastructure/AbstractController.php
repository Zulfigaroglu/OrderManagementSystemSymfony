<?php

namespace App\Controller\Infrastructure;

use App\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as AbstractControllerBase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractController extends AbstractControllerBase
{
    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $entity
     * @return void
     * @throws ValidationException
     * @throws \Exception
     */
    protected function validate($entity)
    {
        $validationResult = $this->validator->validate($entity);
        $errors = array_map(function (ConstraintViolation $violation) {
            return (string)$violation->getMessage();
        }, $validationResult->getIterator()->getArrayCopy());


        if(count($errors) > 0){
            throw new ValidationException($errors);
        }
    }
}