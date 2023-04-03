<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Controller\Adminhtml\Slider;
class MassStatus extends AbstractMassAction
{
	/**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function itemAction($slider)
    {
        try {
            $status = $this->getRequest()->getParam('status');
			$slider->setStatus($status)->save();
        } catch (\Exception $e) {
            $this->getMessageManager()->addErrorMessage($e->getMessage());
        }
        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}