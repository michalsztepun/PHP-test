<?php

namespace App\Service;

use App\Validation\ValidationInterface;
use Symfony\Component\Console\Question\Question;

class DataValidator
{
    /**
     * @var ValidationInterface[]
     */
    private $validators;

    public function __construct(iterable $validators)
    {
        $this->validators = $validators;
    }

    public function getNames(): array
    {
        $names = array();
        foreach ($this->validators as $validators){
            $names[] = $validators->getName();
        }

        return $names;
    }

    public function addQuestionValidation(Question $question, string $validatorName): Question
    {
        if(!$validatorName) return $question;

        foreach ($this->validators as $validator){
            if($validatorName === $validator->getName()) return $validator->addValidation($question, $validatorName);
        }

        throw new \RuntimeException('Validator ' . $validatorName . ' missing.');
    }
}