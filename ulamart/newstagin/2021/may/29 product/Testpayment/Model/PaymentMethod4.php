<?php

namespace Test\Testpayment\Model;

/**
 * Pay In Store payment method model
 */
class PaymentMethod4 extends \Magento\Payment\Model\Method\AbstractMethod
{

    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = 'bankdeposit';
}