<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */

namespace Magerubik\Productslider\Controller\Adminhtml\Category;
class MassStatus extends AbstractMassAction
{
	/**
     * @param $category
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function itemAction($category)
    {
        try {
            $status = $this->getRequest()->getParam('status');
			$category->setStatus($status)->save();
        } catch (\Exception $e) {
            $this->getMessageManager()->addErrorMessage($e->getMessage());
        }
        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}