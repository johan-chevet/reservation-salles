<?php

namespace Core;

class Validator
{

    private array $to_validate;

    private array $keys = [];

    private string $current;

    private array $validations = [];

    private array $errors_values = [];
    private array $errors = [];

    public function __construct(array $to_validate)
    {
        $this->to_validate = $to_validate;
    }

    public function add(string $key): static
    {
        $this->keys[] = $key;
        $this->current = $key;
        return $this;
    }

    public function required(?string $message = null)
    {
        $this->validations[$this->current][] = [
            'callback' => fn() => isset($this->to_validate[$this->current]),
            'message' => $message ?? "Field is required"
        ];
        return $this;
    }

    public function is_int(?string $message = null)
    {
        $this->validations[$this->current][] = [
            'callback' => fn() => int_validation($this->to_validate[$this->current]),
            'message' => $message ?? "Must be a valid integer"
        ];
        return $this;
    }

    public function min_length(int $len, ?string $message = null)
    {
        $this->validations[$this->current][] = [
            'callback' => fn() => strlen($this->to_validate[$this->current]) >= $len,
            'message' => $message ?? "Must have at least $len characters"
        ];
        return $this;
    }

    public function max_length(int $len, ?string $message = null)
    {
        $this->validations[$this->current][] = [
            'callback' => fn() => strlen($this->to_validate[$this->current]) <= $len,
            'message' => $message ?? "Must have $len characters maximum"
        ];
        return $this;
    }

    public function less_than(int $nb, ?string $message = null)
    {
        $this->validations[$this->current][] = [
            'callback' => fn() => $this->to_validate[$this->current] < $nb,
            'message' => $message ?? "Must be less than $nb"
        ];
        return $this;
    }

    public function greater_than(int $nb, ?string $message = null)
    {
        $this->validations[$this->current][] = [
            'callback' => fn() => $this->to_validate[$this->current] > $nb,
            'message' => $message ?? "Must be greater than $nb"
        ];
        return $this;
    }

    public function custom(callable $fn, string $message)
    {
        $this->validations[$this->current][] =  [
            'callback' => $fn,
            'message' => $message
        ];
        return $this;
    }

    /**
     * Executes validation functions for all keys
     * @return array of errors
     */
    public function validate(): array
    {
        foreach ($this->validations as $key => $rules) {
            $this->current = $key;
            foreach ($rules as $rule) {
                if (!$rule['callback']($this->to_validate[$key], $this->to_validate)) {
                    // var_dump($rule);
                    $this->errors[$key] = $rule['message'];
                    break;
                }
            }
        }
        return $this->errors;
    }

    public function get_errors()
    {
        return $this->errors;
    }
}
