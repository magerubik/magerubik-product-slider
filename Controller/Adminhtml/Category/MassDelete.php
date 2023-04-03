<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Controller\Adminhtml\Category;
class MassDelete extends AbstractMassAction
{
	/**
     * @param CategoryInterface $category
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function itemAction($category)
    {
        try {
			$this->getRepository()->deleteById($category->getCategoryId());
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
     *
     * @return \Magento\Framework\Phrase
     */
    protected function getSuccessMessage($collectionSize = 0)
    {
        return $collectionSize
            ? __('A total of %1 record(s) have been deleted.', $collectionSize)
            : __('No records have been deleted.');
    }
}