<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Model\Source;
use Magento\Framework\Option\ArrayInterface;
/**
 * Class
 */
class Type implements ArrayInterface
{
    const BESTSELL = 1;
    const TOPRATE = 2;
    const MOSTVIEW = 3;
    const LASTET = 4;
    const NEWPRODUCT = 5;
    const SALEPRODUCT = 6;
    const TOPDOWN = 7;
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::LASTET, 'label' => __('Latest Products')],
            ['value' => self::BESTSELL, 'label' => __('Best Sellers')],
            ['value' => self::TOPRATE, 'label' => __('Top Rated')],
            ['value' => self::MOSTVIEW, 'label' => __('Most Viewed')],
            ['value' => self::TOPDOWN, 'label' => __('Top Download Products')],
            ['value' => self::NEWPRODUCT, 'label' => __('New Products (Set the [New from/to date] attr in Product manage)')],
            ['value' => self::SALEPRODUCT, 'label' => __('Special Price (Set the [Special Price & from/to date] attr in Product manage)')]
        ];
    }
}