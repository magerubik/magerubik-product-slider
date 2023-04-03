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
class Layout implements ArrayInterface
{
    const TAB_SLIDER = 1;
    const lIST_SLIDER = 2;
    const TAB_VERTICAL = 3;
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::TAB_SLIDER, 'label' => __('Tab Slider')],
            ['value' => self::lIST_SLIDER, 'label' => __('List Slider')],
            ['value' => self::TAB_VERTICAL, 'label' => __('Tab Vertical')]
        ];
    }
}