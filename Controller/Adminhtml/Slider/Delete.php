<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Controller\Adminhtml\Slider;
use Magerubik\Productslider\Controller\Adminhtml\Slider;
class Delete extends Slider
{
	/**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = (int)$this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->getSliderRepository()->deleteById($id);
                $this->getMessageManager()->addSuccessMessage(__('You have deleted the slider.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->getMessageManager()->addErrorMessage(__('We can\'t find a slider to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}