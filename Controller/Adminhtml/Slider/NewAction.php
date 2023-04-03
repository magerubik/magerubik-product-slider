<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Controller\Adminhtml\Slider;


use Magerubik\Productslider\Controller\Adminhtml\Slider;


class NewAction extends Slider{
	public function execute()
	{
		$this->_forward('edit');
	}
}