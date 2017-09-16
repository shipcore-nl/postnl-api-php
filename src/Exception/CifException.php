<?php

namespace ThirtyBees\PostNL\Exception;

/**
 * Class CifException
 *
 * @package ThirtyBees\PostNL\Exception
 */
class CifException extends \Exception
{
    /** @var array $messages */
    protected $messages;

    /**
     * CifException constructor.
     *
     * @param string|string[] $message  In case of multiple errors, the format looks like:
     *                                  [
     *                                    'description' => string <The description>,
     *                                    'message'     => string <The error message>,
     *                                    'code'        => int <The error code>
     *                                  ]
     *                                  The code param will be discarded if `$message` is an array
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, $previous = null)
    {
        if (is_array($message)) {
            $this->messages = $message;

            $message = $this->messages[0]['message'];
            $code = $this->messages[0]['code'];
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * Get error messages and codes
     *
     * @return array|string|string[]
     */
    public function getMessagesDescriptionsAndCodes()
    {
        return $this->messages;
    }
}
