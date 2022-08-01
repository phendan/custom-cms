<?php

namespace App\Interfaces;

interface ValidationInterface {
    public function setRules(array $rules);
    public function validate();
    public function fails(): bool;
    public function getErrors(): array;
}
