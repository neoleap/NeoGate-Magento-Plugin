<?php

namespace Alrajhi\iPay\Controller\Standard;

class Response extends \Alrajhi\iPay\Controller\iPayAbstract {

    public function execute() {
        
		try {
			$returnUrl = $this->getCheckoutHelper()->getUrl('checkout');

        
            $paymentMethod = $this->getPaymentMethod();
            $params = $this->getRequest()->getParams();
			
			$response_status = $paymentMethod->validateResponse($params);
			
            if ($response_status == 'validated') {
                $returnUrl = $this->getCheckoutHelper()->getUrl('checkout/onepage/success');
                $order = $this->getOrder();
                $payment = $order->getPayment();
                $paymentMethod->postProcessing($order, $payment, $params);
            } else {
                $this->messageManager->addErrorMessage(__($response_status));
                $returnUrl = $this->getCheckoutHelper()->getUrl('checkout/onepage/failure');
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addExceptionMessage($e, $e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('We can\'t place the order.'));
        }

        $this->getResponse()->setRedirect($returnUrl);
    }

	
}
