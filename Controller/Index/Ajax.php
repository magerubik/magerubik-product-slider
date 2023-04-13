<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Ajax extends \Magento\Framework\App\Action\Action
{
	protected $resultPageFactory;
	 /**
     * Result page factory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory;
     */
	protected $_resultJsonFactory;
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
		JsonFactory $resultJsonFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
		$this->_resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
		$resultJson = $this->_resultJsonFactory->create();
	    $catId = $this->getRequest()->getParam('catId');
	    $type = $this->getRequest()->getParam('type');
	    $tabId = $this->getRequest()->getParam('tabId');
		if($this->getRequest()->getParam('vertcal')){
			$products = $this->_view->getLayout()->createBlock('Magerubik\Productslider\Block\Ajax')->setType($type)->setCatId($catId)->setTabId($tabId)->setTemplate('Magerubik_Productslider::vertcal_ajax.phtml')->toHtml();
		} else if($this->getRequest()->getParam('list')){
			$products = $this->_view->getLayout()->createBlock('Magerubik\Productslider\Block\Ajax')->setType($type)->setCatId($catId)->setTabId($tabId)->setTemplate('Magerubik_Productslider::list_ajax.phtml')->toHtml();
		} else {
			$products = $this->_view->getLayout()->createBlock('Magerubik\Productslider\Block\Ajax')->setType($type)->setCatId($catId)->setTabId($tabId)->setTemplate('Magerubik_Productslider::ajax.phtml')->toHtml();
		}		
		$response = array('html_result'=>$products);
		return $resultJson->setData($response);			
    }
}