<?php

namespace Alrajhi\iPay\Model;

class ConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    protected $methodCode = \Alrajhi\iPay\Model\iPay::PAYMENT_IPAY_CODE;
    
    
    protected $method;
	

    public function __construct(\Magento\Payment\Helper\Data $paymenthelper){
        $this->method = $paymenthelper->getMethodInstance($this->methodCode);
    }

    public function getConfig(){

        return $this->method->isAvailable() ? [
            'payment'=>['ipay'=>[
                'redirectUrl'=>$this->method->getRedirectUrl()  
            ]
        ]
        ]:[];
    }
}
