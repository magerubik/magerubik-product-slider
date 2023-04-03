<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Model\ResourceModel\Slider;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection{

    protected $_idFieldName;

    protected function _construct ()
    {
        parent::_construct (); // TODO: Change the autogenerated stub
        $this->_init('Magerubik\Productslider\Model\Slider', 'Magerubik\Productslider\Model\ResourceModel\Slider');
    }
}