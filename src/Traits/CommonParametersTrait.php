<?php

namespace Omnipay\Bpoint\Traits;

trait CommonParametersTrait
{
    /**
     * @return mixed
     */
    public function getCrn1()
    {
        return $this->getParameter('crn1');
    }

    /**
     * @param string $value
     *
     * @return \Omnipay\Bpoint\Message\AbstractRequest provides a fluent interface.
     */
    public function setCrn1($value)
    {
        return $this->setParameter('crn1', $value);
    }

    /**
     * @return mixed
     */
    public function getCrn2()
    {
        return $this->getParameter('crn2');
    }

    /**
     * @param string $value
     *
     * @return \Omnipay\Bpoint\Message\AbstractRequest provides a fluent interface.
     */
    public function setCrn2($value)
    {
        return $this->setParameter('crn2', $value);
    }

    /**
     * @return mixed
     */
    public function getCrn3()
    {
        return $this->getParameter('crn3');
    }

    /**
     * @param string $value
     *
     * @return \Omnipay\Bpoint\Message\AbstractRequest provides a fluent interface.
     */
    public function setCrn3($value)
    {
        return $this->setParameter('crn3', $value);
    }
}