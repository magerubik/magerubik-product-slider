<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Controller\Adminhtml;
use Magento\Framework\App\Request\DataPersistorInterface;
abstract class Slider extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;
    /**
     * @var \Magerubik\Productslider\Api\SliderRepositoryInterface
     */
    private $sliderRepository;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Magerubik\Productslider\Helper\Url
     */
    private $urlHelper;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var \Magento\Framework\Registry
     */
    private $_registry;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magerubik\Productslider\Api\SliderRepositoryInterface $sliderRepository,
		\Magerubik\All\Helper\Url $urlHelper,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Registry $_registry,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->sliderRepository = $sliderRepository;
        $this->logger = $logger;
        $this->urlHelper = $urlHelper;
        $this->dataPersistor = $dataPersistor;
        $this->_registry = $_registry;
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magerubik_Productslider::slider');
    }
    public function getLogger()
    {
        return $this->logger;
    }
    public function getDataPersistor()
    {
        return $this->dataPersistor;
    }
    public function getRegistry()
    {
        return $this->_registry;
    }
    public function getUrlHelper()
    {
        return $this->urlHelper;
    }
    public function getSliderRepository()
    {
        return $this->sliderRepository;
    }
    public function getPageFactory()
    {
        return $this->resultPageFactory;
    }
}