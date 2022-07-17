<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;

class CustomException extends \Exception
{
    /**
     * The status code for generated response.
     *
     * @var int
     */
    public $status_code;

    /**
     * The error tag for generated response
     *
     * @var string
     */
    public $error_tag;

    /**
     * The message bag instance.
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $messages;

    /**
     * Create a new exception instance.
     *
     * @param  string $message
     * @param  string $error_tag
     * @param  \Illuminate\Support\MessageBag|array $errors
     * @return void
     */
    public function __construct($message = 'Unknown Error', $error_tag = 'not_implemented', $errors = [])
    {
        parent::__construct($message);

        if (is_array($errors)) {
            $this->messages = new MessageBag($errors);
        } else {
            $this->messages = $errors;
        }

        $this->error_tag   = $error_tag;
        $this->status_code = JsonResponse::HTTP_BAD_REQUEST;
    }

    /**
     * Returns status code.
     *
     * @return int Status code
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;

        return $this;
    }

    public function getErrorTag()
    {
        return $this->error_tag;
    }

    public function setErrorTag($error_tag)
    {
        $this->error_tag = $error_tag;

        return $this;
    }

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders()
    {
        return [];
    }

    /**
     * An alternative more semantic shortcut to the message container.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function errors()
    {
        return $this->messages;
    }

    /**
     * Add a message to the message bag.
     *
     * @param  string  $key
     * @param  string  $message
     * @return $this
     */
    public function add_error($key, $message)
    {
        $this->messages->add($key, $message);

        return $this;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'error'   => $this->error_tag,
            'message' => $this->errors()->first()
        ], $this->getStatusCode());
    }
}
