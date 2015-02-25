<?php
require __DIR__ . '/../vendor/autoload.php';

use \FUnit as fu;
use Knlv\Zf2\Authentication\Adapter\Callback as CallbackAdapter;
use Zend\Authentication\Result;

fu::test('Test authentication callback adapter with credentials', function () {
    $callback = function ($username, $password) {
        if ('testuser' === $username && 'testpass' === $password) {
            return $username;
        }

        return false;
    };

    $adapter = new CallbackAdapter($callback);
    $adapter->setIdentity('testuser');
    $adapter->setCredential('testpass');
    $result =  $adapter->authenticate();
    fu::ok($result instanceof Result, 'Assert Authentication result is instanceof Zend\Authentication\Result');
    fu::equal(Result::SUCCESS, $result->getCode(), 'Assert success code in result on success');
    fu::equal('testuser', $result->getIdentity(), 'Assert identity is returned on success');
    $adapter->setCredential('someotherpass');
    $result = $adapter->authenticate();
    fu::equal(Result::FAILURE, $result->getCode(), 'Assert failure code in result on failure');
    fu::equal(null, $result->getIdentity(), 'Assert null is returned on failure');
});

fu::test('Test authentication callback adapter without credentials', function () {
    $callbackSuccess = function () {
        return 'testuser';
    };

    $callbackFailure = function () {
        return false;
    };

    $adapter = new CallbackAdapter($callbackSuccess);
    $result =  $adapter->authenticate();
    fu::ok($result instanceof Result, 'Assert Authentication result is instanceof Zend\Authentication\Result');
    fu::equal(Result::SUCCESS, $result->getCode(), 'Assert success code in result on success');
    fu::equal('testuser', $result->getIdentity(), 'Assert identity is returned on success');

    $adapter = new CallbackAdapter($callbackFailure);
    $result = $adapter->authenticate();
    fu::equal(Result::FAILURE, $result->getCode(), 'Assert failure code in result on failure');
    fu::equal(null, $result->getIdentity(), 'Assert null is returned on failure');
});
