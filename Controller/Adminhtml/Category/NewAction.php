<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Controller\Adminhtml\Category;


use Magerubik\Productslider\Controller\Adminhtml\Category;

class NewAction extends Category
{
	public function execute ()
	{
		// TODO: Implement execute() method.
		$this->_forward('edit');
	}
}