<?php

/**
 * Knlv\Zf2\Authentication\Adapter\Cas
 *
 * @link https://github.com/kanellov/zf2-authentication
 * @copyright Copyright (c) 2015 Vassilis Kanellopoulos - contact@kanellov.com
 * @license https://raw.githubusercontent.com/kanellov/zf2-authentication/master/LICENSE
 */

namespace Knlv\Zf2\Authentication\Adapter;

use CAS_Client;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

class Cas implements AdapterInterface
{

    /**
     * @var CAS_Client
     */
    protected $client;

    public function __construct(CAS_Client $client)
    {
        $this->setClient($client);
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface If authentication cannot be performed
     */
    public function authenticate()
    {
        $client = $this->getClient();
        if ($client->forceAuthentication()) {
            $identity = $client->getUser();

            return new Result(Result::SUCCESS, $identity, array('Authentication success'));
        }

        return new Result(Result::FAILURE, null, array('Authentication failure'));
    }

    /**
     * Gets the value of client.
     *
     * @return CAS_Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Sets the value of client.
     *
     * @param CAS_Client $client the client
     *
     * @return self
     */
    protected function setClient(CAS_Client $client)
    {
        $this->client = $client;

        return $this;
    }
}
