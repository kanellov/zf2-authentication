<?php

/**
 * Knlv\Zf2\Authentication\Adapter\Callback
 *
 * @link https://github.com/kanellov/zf2-authentication
 * @copyright Copyright (c) 2015 Vassilis Kanellopoulos - contact@kanellov.com
 * @license https://raw.githubusercontent.com/kanellov/zf2-authentication/master/LICENSE
 */


namespace Knlv\Zf2\Authentication\Adapter;

use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Exception\InvalidArgumentException;
use Zend\Authentication\Result;

/**
 * Authentication Adapter authenticates using callback function.
 * The Callback function must return the identity on authentication success
 * and false on authentication failure.
 */
class Callback extends AbstractAdapter
{
    protected $callback;

    /**
     * Class constructor
     * @param callable $callback the autentication callback
     */
    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException(sprintf(
                'Expected callable. %s given',
                (is_object($callback) ? get_class($callback) : gettype($callback))
            ));
        }

        $this->callback = $callback;
    }

    /**
     * Authenticate
     * @return \Zend\Authentication\Result the authentication result
     */
    public function authenticate()
    {
        if (false !== ($identity = call_user_func($this->callback, $this->getIdentity(), $this->getCredential()))) {
            return new Result(Result::SUCCESS, $identity, array('Authentication success'));
        }

        return new Result(Result::FAILURE, null, array('Authentication failure'));
    }
}
