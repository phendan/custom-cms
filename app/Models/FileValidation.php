<?php

namespace App\Models;
use App\Interfaces\ValidationInterface;

class FileValidation implements ValidationInterface {
    private array $inputFiles;
    private array $rules;
    private array $errors = [];
    private $allowedTypes = [
        'image' => [
            'jpg' => IMAGETYPE_JPEG,
            'jpeg' => IMAGETYPE_JPEG,
            'png' => IMAGETYPE_PNG
        ]
    ];

    public function __construct(array $files)
    {
        $this->inputFiles = $files;
    }

    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    public function validate(): void
    {
        foreach ($this->rules as $field => $fieldRules) {
            $fieldRules = explode('|', $fieldRules);
            $this->validateField($field, $fieldRules);
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function validateField(string $field, array $fieldRules): void
    {
        foreach ($fieldRules as $fieldRule)
        {
            $ruleSegments = explode(':', $fieldRule);
            $fieldRule = $ruleSegments[0];
            $satisfier = $ruleSegments[1] ?? null;

            if (!method_exists(FileValidation::class, $fieldRule)) {
                continue;
            }

            $errorMessage = $this->{$fieldRule}($field, $satisfier);

            if ($errorMessage !== null) {
                $this->errors[$field][] = $errorMessage;
            }
        }
    }

    private function required($field): ?string
    {
        if (!isset($this->inputFiles[$field]) || $this->inputFiles[$field]['size'] === 0)
        {
            return "The {$field} field must not be empty.";
        }

        return null;
    }

    private function type($field, $satisfier): ?string
    {
        $allowedExtensions = array_keys($this->allowedTypes[$satisfier]);
        $extension = strtolower(pathinfo($this->inputFiles[$field]['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions)) {
            return "The {$field} field must be of type {$satisfier}";
        }

        $currentLocation = $this->inputFiles[$field]['tmp_name'];
        $detectedMimeType = @exif_imagetype($currentLocation);
        $allowedMimeType = $this->allowedTypes[$satisfier][$extension];

        if ($detectedMimeType !== $allowedMimeType) {
            return "The {$field} field must be of type {$satisfier}";
        }

        return null;
    }

    private function maxsize($field, $satisfier): ?string
    {
        if ($this->inputFiles[$field]['size'] > (int) $satisfier)
        {
            return "The {$field} must not exceed {$satisfier} bytes.";
        }

        return null;
    }
}
