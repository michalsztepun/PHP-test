<?php

namespace App\Validation;


use Symfony\Component\Console\Question\Question;

interface ValidationInterface
{
    /**
     * Add validation to question
     */
    public function addValidation(Question $question, string $name): Question;

    /**
     * @return string validation name
     */
    public function getName(): string;
}