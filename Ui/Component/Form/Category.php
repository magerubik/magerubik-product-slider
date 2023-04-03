<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Ui\Component\Form;
use Magento\Framework\Data\OptionSourceInterface;
use Magerubik\Productslider\Model\CategoryFactory as ProductsliderCategoryFactory;
class Category implements OptionSourceInterface
{
    /**
     * @var ProductsliderCategoryFactory
     */
    private $productsliderCategoryFactory;
    /**
	  * @var \Magento\Framework\Registry
	 */
	protected $_registry;
    /**
     * @var array
     */
    private $categoryTree;
    public function __construct(
        ProductsliderCategoryFactory $productsliderCategoryFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->_productsliderCategoryFactory = $productsliderCategoryFactory;
        $this->_registry = $registry;
    }
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getCategoryTree();
    }
    /**
     * Retrieve category tree
     *
     * @param bool $displayRoot
     * @return array
     */
    public function getCategoryTree($displayRoot = false)
    {
		$model = $this->_productsliderCategoryFactory->create();
		$collection = $model->getCollection()->load();
		$optionsArr[] = ['value' => '', 'label' => ' ------ Select Group ------'];
		foreach ($collection as $item) {
			$optionsArr[] = ['value' => $item->getCategoryId(), 'label' => $item->getTitle()];
		}
        return $optionsArr;
    }

}