<?php

namespace App\Validation;


use Symfony\Component\Console\Question\Question;

class StringValidation implements ValidationInterface
{
    public function getName(): string
    {
        return 'string';
    }

    public function addValidation(Question $question, string $name): Question
    {
        $question->setValidator(function ($answer) {
            if (!is_string($answer) || strlen($answer) < 3) {
                throw new \RuntimeException(
                    'Minimum length is 3'
                );
            }

            return $answer;
        });

        return $question;
    }
}