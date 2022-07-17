<?php

namespace App\Validators;

use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Validator;

abstract class BaseValidator
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $rules;

    /**
     * @var array
     */
    protected $errors;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $tag;


    public function __construct(array $data = null, array $rules = null, string $message = null, string $tag = null)
    {
        $this->data = $data ?? [];
        $this->rules = $rules ?? [];
        $this->errors = [];
        $this->message = $message ?? 'Invalid Input';
        $this->tag = $tag ?? 'invalid_input';
    }

    public static function make(array $data = null, array $rules = null, string $message = null, string $tag = null)
    {
        return new static($data, $rules, $message, $tag);
    }

    public function validate()
    {
        $validator = Validator::make($this->data, $this->rules);

        if ($validator->fails()) {
            throw new CustomException($this->message, $this->tag, $validator->errors());
        }
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    public function setTag(string $tag)
    {
        $this->tag = $tag;
        return $this;
    }

    public function setRules(array $rules)
    {
        $this->rules = $rules;
        return $this;
    }

    public function addRules(array $rules)
    {
        $this->rules = array_merge($this->rules, $rules);
        return $this;
    }

    /**
     * @param string $key
     * @param array|string $error
     */
    protected function addError(string $key, $error)
    {
        array_push($this->errors[$key], $error);
    }

    public function errors()
    {
        return $this->errors();
    }
}
