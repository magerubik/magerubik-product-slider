<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Block;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ActionInterface;
use Magento\Catalog\Block\Product\ListProduct;
class Ajax  extends \Magento\Catalog\Block\Product\AbstractProduct
{
 /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;
    /**
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_objectManager;
    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;
    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
    protected $_limit; // Limit Product
    /**
     * @param Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
		\Magerubik\Productslider\Helper\ConfigHelper $configHelper,
		\Magento\Catalog\Model\CategoryFactory $categoryFactory,
		ListProduct $abstractProduct,
		\Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurable,
		\Magento\GroupedProduct\Model\Product\Type\Grouped $grouped,
		\Magento\Framework\Data\Form\FormKey $formKey,
        array $data = []
    ) {
        $this->urlHelper = $urlHelper;
        $this->_objectManager = $objectManager;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
		$this->_categoryFactory = $categoryFactory;
		$this->_configHelper = $configHelper;
		$this->tempSetting = $this->_configHelper->getTemplateSettings();
		$this->absblock = $abstractProduct;
		$this->configurable = $configurable;
		$this->grouped = $grouped;
		$this->formKey = $formKey;
        parent::__construct( $context, $data );
    }
	public function getParentId($childId){
		/* for simple product of configurable product */
        $product = $this->configurable->getParentIdsByChild($childId);
        if(isset($product[0])){
         return $product[0];
       }
 
		/* for simple product of Group product */
       $parentIds = $this->grouped->getParentIdsByChild($childId);
	   if(isset($parentIds[0])){
         return $parentIds[0];
       }
       return false;
    }
	public function getFormKey()
	{
		 return $this->formKey->getFormKey();
	}	
	public function getItemByType($category = null,$type=1)
    {
        if($type == 7) return $this->getTopDownloadProducts($category);
        if($type == 6) return $this->getSaleProducts($category);
        if($type == 5) return $this->getNewProducts($category);
        if($type == 3) return $this->getMostviewProducts($category);
        if($type == 4) return $this->getLastetProducts($category);
        if($type == 2) return $this->getTopRatedProducts($category);
        return $this->getBestSellerProducts($category);
    }
	public function getTopDownloadProducts($categoryID = null)
    {
        $orderfeild='rating_summary';
        $order='desc';
		$collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection->addStoreFilter()
                    ->addAttributeToSelect('*')
                    ->addMinimalPrice()
                    ->addFinalPrice()
                    ->addTaxPercents()->setOrder('downloads');     
        if($categoryID && $categoryID != 0){
            /**
             * @var \Magento\Catalog\Model\Category $catModel
             */
            $catModel = $this->_categoryFactory->create();
            $catCollection = $catModel->load($categoryID);
            $collection->addCategoryFilter($catCollection);
        }
		$collection->setPageSize($this->tempSetting->maxproduct);
        return $collection;
    }
	public function getSaleProducts($categoryID = null)
    {
        $todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices(
            $collection
        )->addStoreFilter()->addAttributeToFilter(
            'special_from_date',
            [
                'or' => [
                    0 => ['date' => true, 'to' => $todayEndOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )->addAttributeToFilter(
            'special_to_date',
            [
                'or' => [
                    0 => ['date' => true, 'from' => $todayStartOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )->addAttributeToFilter(
            [
                ['attribute' => 'special_from_date', 'is' => new \Zend_Db_Expr('not null')],
                ['attribute' => 'special_to_date', 'is' => new \Zend_Db_Expr('not null')],
            ]
        )->addAttributeToSort('special_to_date', 'desc');
		if($categoryID && $categoryID != 0){
            /**
             * @var \Magento\Catalog\Model\Category $catModel
             */
            $catModel = $this->_categoryFactory->create();
            $catCollection = $catModel->load($categoryID);
            $collection->addCategoryFilter($catCollection);
        }
		$collection->setPageSize($this->tempSetting->maxproduct);
        return $collection;
    }
	public function getNewProducts ($categoryID = null)
    {
        $todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices(
            $collection
        )->addStoreFilter()->addAttributeToFilter(
            'news_from_date',
            [
                'or' => [
                    0 => ['date' => true, 'to' => $todayEndOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )->addAttributeToFilter(
            'news_to_date',
            [
                'or' => [
                    0 => ['date' => true, 'from' => $todayStartOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )->addAttributeToFilter(
            [
                ['attribute' => 'news_from_date', 'is' => new \Zend_Db_Expr('not null')],
                ['attribute' => 'news_to_date', 'is' => new \Zend_Db_Expr('not null')],
            ]
        )->addAttributeToSort('news_from_date', 'desc');
		if($categoryID && $categoryID != 0){
            /**
             * @var \Magento\Catalog\Model\Category $catModel
             */
            $catModel = $this->_categoryFactory->create();
            $catCollection = $catModel->load($categoryID);
            $collection->addCategoryFilter($catCollection);
        }
		$collection->setPageSize($this->tempSetting->maxproduct);
        return $collection;
    }
	public function getMostviewProducts ($categoryID = null)
    {
        $collection = $this->_objectManager->get('\Magento\Reports\Model\ResourceModel\Report\Product\Viewed\CollectionFactory')->create()->setModel('Magento\Catalog\Model\Product');
		$producIds = array();
        foreach ($collection as $product) {
            $producIds[] = $product->getProductId();
        }
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices(
            $collection
        )->addStoreFilter()->addAttributeToFilter('entity_id', array('in' => $producIds));
		if($categoryID && $categoryID != 0){
            /**
             * @var \Magento\Catalog\Model\Category $catModel
             */
            $catModel = $this->_categoryFactory->create();
            $catCollection = $catModel->load($categoryID);
            $collection->addCategoryFilter($catCollection);
        }
		$collection->setPageSize($this->tempSetting->maxproduct);
        return $collection;
    }
	public function getLastetProducts($categoryID = null)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices(
            $collection
        )->addStoreFilter()
        ->addAttributeToSort('created_at', 'desc');
		if($categoryID && $categoryID != 0){
            /**
             * @var \Magento\Catalog\Model\Category $catModel
             */
            $catModel = $this->_categoryFactory->create();
            $catCollection = $catModel->load($categoryID);
            $collection->addCategoryFilter($catCollection);
        }
		$collection->setPageSize($this->tempSetting->maxproduct);
        return $collection; 
    }
	public function getTopRatedProducts($categoryID = null)
    {
        $orderfeild='rating_summary';
        $order='desc';
		$collection = $this->_productCollectionFactory->create();
		$storeId = $collection->getStoreId();
		$collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection->addStoreFilter()
                    ->addAttributeToSelect('*')
                    ->addMinimalPrice()
                    ->addFinalPrice()
                    ->addTaxPercents();
		$collection->getSelect()->joinLeft(
            array('_reviewed_order_table'=>$collection->getTable('review_entity_summary')),
            "_reviewed_order_table.store_id=$storeId AND _reviewed_order_table.entity_pk_value=e.entity_id",
            array()
        );
		$collection->getSelect()->order("_reviewed_order_table.$orderfeild $order");
        $collection->getSelect()->group('e.entity_id');       
        if($categoryID && $categoryID != 0){
            /**
             * @var \Magento\Catalog\Model\Category $catModel
             */
            $catModel = $this->_categoryFactory->create();
            $catCollection = $catModel->load($categoryID);
            $collection->addCategoryFilter($catCollection);
        }
		$collection->setPageSize($this->tempSetting->maxproduct);
        return $collection;
    }
	public function getBestSellerProducts($categoryID = null)
    {
        $collection = $this->_objectManager->get('\Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory')->create()->setPeriod('month');
        $producIds = array();
        foreach ($collection as $product) {
			if(!$this->getParentId($product->getProductId())){
				$producIds[] = $product->getProductId();
			} else {
				$producIds[] = $this->getParentId($product->getProductId());
			}
            
        }
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices(
            $collection
        )->addStoreFilter()->addAttributeToFilter('entity_id', array('in' => $producIds));
		if($categoryID && $categoryID != 0){
            /**
             * @var \Magento\Catalog\Model\Category $catModel
             */
            $catModel = $this->_categoryFactory->create();
            $catCollection = $catModel->load($categoryID);
            $collection->addCategoryFilter($catCollection);
        }
		$collection->setPageSize($this->tempSetting->maxproduct);
		return $collection;
    }
	/**
     * Get post parameters
     *
     * @param Product $product
     * @return array
     */
    public function getAddToCartPostParams(Product $product)
    {
        $url = $this->getAddToCartUrl($product, ['_escape' => false]);
        return [
            'action' => $url,
            'data' => [
                'product' => (int) $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }
	public function getProductPriceHtml(
        \Magento\Catalog\Model\Product $product,
        $priceType,
        $renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }
        /** @var \Magento\Framework\Pricing\Render $priceRender */
        $priceRender = $this->getLayout()->getBlock('product.price.render.default');
		if (!$priceRender) {
        $priceRender = $this->getLayout()->createBlock(
				\Magento\Framework\Pricing\Render::class,
				'product.price.render.default',
				['data' => ['price_render_handle' => 'catalog_product_prices']]
			);
		}
        $price = '';
        if ($priceRender) {
        $price = $priceRender->render(
				\Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
				$product,
				[
					'display_minimal_price'  => true,
					'use_link_for_as_low_as' => true,
					'zone' => \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST
				]
			);
		}
        return $price;
    }
}