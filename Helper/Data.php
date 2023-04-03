<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function installGuide( $groupId , $layout = 1 )
    {
        $html = '1, To embed Menu Group in CMS/Static Block:
{{block class="Magerubik\Productslider\Block\IndexBlock" name="productslider_' . $groupId . '" group_id="' . $groupId . '"}}
2, To reference in custom xml:
<referenceContainer name="content">
    <block class="Magerubik\Productslider\Block\IndexBlock" name="productslider_' . $groupId . '">
        <arguments>
            <argument name="group_id" xsi:type="number">' . $groupId . '</argument>
        </arguments>
    </block>
</referenceContainer>
';
        return $html;
    }
}