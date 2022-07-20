<?php

namespace App\Models;

use App\Models\Database;

class FormValidation {
    private array $formInput;
    private array $rules;
    private array $errors = [];
    private Database $db;

    public function __construct(array $formInput, Database $db)
    {
        $this->formInput = $formInput;
        $this->db = $db;
    }

    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function validate(): void
    {
        $errorMessage = $this->csrfToken();
        if ($errorMessage) {
            $this->errors['root'] = $errorMessage;
            return;
        }

        foreach ($this->rules as $field => $fieldRules) {
            $fieldRules = explode('|', $fieldRules);

            // Don't check nonrequired fields if they are not set
            if (!in_array('required', $fieldRules) && !$this->exists($field)) {
                continue;
            }

            $this->validateField($field, $fieldRules);
        }
    }

    private function validateField(string $field, array $fieldRules): void
    {
        usort($fieldRules, function ($firstRule, $secondRule) {
            if ($firstRule === 'required') {
                return -1;
            }

            return 1;
        });

        foreach ($fieldRules as $fieldRule) {
            $ruleSegments = explode(':', $fieldRule);
            $fieldRule = $ruleSegments[0];

            if (isset($ruleSegments[1])) {
                $satisfier = $ruleSegments[1];
            } else {
                $satisfier = null;
            }

            //$satisfier = isset($ruleSegments[1]) ? $ruleSegments[1] : null;
            //$satisfier = $ruleSegments[1] ?? null;

            if (!method_exists(FormValidation::class, $fieldRule)) {
                continue;
            }

            $errorMessage = $this->{$fieldRule}($field, $satisfier);

            if ($errorMessage !== null) {
                $this->errors[$field][] = $errorMessage;
                //break;
            }
        }
    }

    private function csrfToken(): ?string
    {
        if (!isset($this->formInput['csrfToken']) || ($this->formInput['csrfToken'] !== $_SESSION['csrfToken'])) {
            return "The form request could not be validated.";
        }

        return null;
    }

    private function exists(string $field): bool
    {
        return isset($this->formInput[$field]) && !empty($this->formInput[$field]);
    }

    private function required(string $field): string|null
    {
        if (!isset($this->formInput[$field]) || empty($this->formInput[$field])) {
            return "The {$field} field is required.";
        }

        return null;
    }

    private function min(string $field, string $satisfier): string|null
    {
        if (strlen($this->formInput[$field]) < (int) $satisfier) {
            return "The {$field} field must be a least {$satisfier} characters.";
        }

        return null;
    }

    private function max(string $field, string $satisfier): string|null
    {
        if (strlen($this->formInput[$field]) > (int) $satisfier) {
            return "The {$field} field must not be more than {$satisfier} characters.";
        }

        return null;
    }

    private function email(string $field): string|null
    {
        if (!filter_var($this->formInput[$field], FILTER_VALIDATE_EMAIL)) {
            return "The {$field} field must be a valid email address.";
        }

        return null;
    }

    private function matches(string $field, string $satisfier): string|null
    {
        if ($this->formInput[$field] !== $this->formInput[$satisfier]) {
            return "Looks like you didn't repeat {$satisfier} correctly.";
        }

        return null;
    }

    private function alnum(string $field)
    {
        if (!ctype_alnum($this->formInput[$field])) {
            return "The {$field} field may only contain letters and numbers.";
        }

        return null;
    }

    private function between(string $field, string $satisfier)
    {
        $bounds = explode(',', $satisfier);
        // Array destructuring
        [ $lowerBound, $upperBound ] = $bounds;

        if ($this->formInput[$field] < $lowerBound || $this->formInput[$field] > $upperBound) {
            return "Please choose a {$field} between {$lowerBound} and {$upperBound}";
        }

        return null;
    }

    private function available(string $field, string $satisfier)
    {
        $value = $this->formInput[$field];

        $this->db->query("SELECT 1 FROM {$satisfier} WHERE {$field} = ?", [$value]);

        if ($this->db->count() > 0) {
            return "The {$field} is already taken.";
        }
    }
}
