<?php

namespace Heyday\SilverStripe\WkHtml\Input;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;

use ReflectionMethod;
use SilverStripe\Control\Session;

/**
 * Takes a SS_HTTPRequest and produces html input for PDF
 */
class Request implements InputInterface
{

    /**
     * @var HTTPRequest
     */
    protected $request;
    /**
     * @var Session
     */
    protected $session;
    /**
     * @var ReflectionMethod
     */
    protected $handleMethod;

    /**
     * @param HTTPRequest $request
     * @param array $session
     */
    public function __construct(HTTPRequest $request, $session = array())
    {
        $this->request = $request;
        $this->setSession($session);
    }

    /**
     * @param $session
     * @throws \RuntimeException
     */
    public function setSession($session)
    {
        if ($session instanceof Session) {
            $this->session = $session;
        } elseif (is_array($session)) {
            $this->session = new Session($session);
        } else {
            throw new \RuntimeException('Session argument must be an array or a Session object');
        }
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return string
     * @throws RuntimeException
     * @throws \ReflectionException
     */
    public function process()
    {
        $result = $this->getHandleMethod()->invoke(
            null,
            $this->request,
            $this->session,
            \DataModel::inst()
        );
        if ($result instanceof HTTPResponse) {
            ob_start();
            $result->output();

            return ob_get_clean();
        } elseif (is_string($result)) {
            return $result;
        } else {
            throw new \RuntimeException('Can\'t handle output from request');
        }
    }

    /**
     * @param ReflectionMethod $handleMethod
     */
    public function setHandleMethod(ReflectionMethod $handleMethod)
    {
        $this->handleMethod = $handleMethod;
    }

    /**
     * @return ReflectionMethod
     * @throws \ReflectionException
     */
    protected function getHandleMethod()
    {
        if (!$this->handleMethod) {
            $this->handleMethod = new ReflectionMethod('Director', 'handleRequest');
            $this->handleMethod->setAccessible(true);
        }

        return $this->handleMethod;
    }
}
