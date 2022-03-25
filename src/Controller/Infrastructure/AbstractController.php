<?php

namespace App\Controller\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as AbstractControllerBase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractController extends AbstractControllerBase
{
    protected ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $entity
     * @return string[]
     */
    protected function validate($entity): array
    {
        $validationResult = $this->validator->validate($entity);
        $errors = array_map(function (ConstraintViolation $violation) {
            return (string)$violation->getMessage();
        }, $validationResult->getIterator()->getArrayCopy());
        return $errors;
    }
}