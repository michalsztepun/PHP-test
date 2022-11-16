<?php

namespace App\Validation;


use Symfony\Component\Console\Question\Question;

class NumericValidation implements ValidationInterface
{
    public function getName(): string
    {
        return 'numeric';
    }

    public function addValidation(Question $question, string $name): Question
    {
        $question->setValidator(function ($answer) {
            if (!is_numeric($answer)) {
                throw new \RuntimeException(
                    'Please provide numeric value'
                );
            }

            return $answer;
        });

        return $question;
    }
}