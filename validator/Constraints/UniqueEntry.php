<?php
namespace Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;


class UniqueEntry extends Constraint
{
    public $message = 'Tai khoan chi duoc chua cac ki tu chu va so';

    public function validatedBy()
    {

        return 'Rules\UniqueEntryValidator';
    }
}