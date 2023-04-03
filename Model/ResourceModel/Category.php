<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */

namespace Magerubik\Productslider\Model\ResourceModel;


use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Category extends AbstractDb{
	/**
	 * @var \Magento\Framework\Stdlib\DateTime\DateTime
	 */
	protected $_date;

	public function __construct (Context $context,DateTime $dateTime,$rescourcePrefix = null)
	{
		parent::__construct ($context,$rescourcePrefix);
		$this->_date = $dateTime;
	}

	protected function _construct()
	{
		$this->_init('magerubik_productslider_category','category_id');
	}
	public function load (\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
	{
		if (!is_numeric($value) && is_null($field)) {
			$field = 'productslider_id';
		}

		return parent::load($object, $value, $field);
	}
}