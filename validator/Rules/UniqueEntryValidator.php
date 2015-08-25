<?php
namespace Rules;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Doctrine\ORM\EntityManager;
use Constraints\UniqueEntry;

/**
 * Created by PhpStorm.
 * User: Nguyen
 * Date: 04/08/2015
 * Time: 09:27
 */
class UniqueEntryValidator extends ConstraintValidator {
    protected $entity;
    public function validate($value, Constraint $constraint) {
        if(!$constraint instanceof UniqueEntry) {
            throw new UnexpectedTypeException($constraint, 'Constraints\UniqueEntry');
        }
        if(!preg_match('/^[a-zA-Z0-9]+$/', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }
}