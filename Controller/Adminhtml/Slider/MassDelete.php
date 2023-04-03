<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Controller\Adminhtml\Slider;
class MassDelete extends AbstractMassAction
{
	/**
     * @param CategoryInterface $slider
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function itemAction($slider)
    {
        try {
			$this->getRepository()->deleteById($slider->getProductsliderId());
        } catch (\Exception $e) {
            $this->getMessageManager()->addErrorMessage($e->getMessage());
        }
        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
    /**
     * @return \Magento\Framework\Phrase
     */
    protected function getErrorMessage()
    {
        return __('We can\'t delete item right now. Please review the log and try again.');
    }
    /**
     * @param int $collectionSize
     * @return \Magento\Framework\Phrase
     */
    protected function getSuccessMessage($collectionSize = 0)
    {
        return $collectionSize
            ? __('A total of %1 record(s) have been deleted.', $collectionSize)
            : __('No records have been deleted.');
    }
}