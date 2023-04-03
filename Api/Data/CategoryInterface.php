<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */

namespace Magerubik\Productslider\Api\Data;


interface CategoryInterface {

    const CATEGORY_ID = 'category_id';
    const TITLE = 'title';
    const LAYOUT = 'layout';
    const INFO = 'info';
    const STATUS = 'status';
    const CREATE_TIME = 'create_time';
    const UPDATE_TIME = 'update_time';

    /**
     * setters
     */
    public function getCategoryId();
    public function getTitle();
    public function getLayout();
    public function getInfo();
    public function getStatus();
    public function getCreateTime();
    public function getUpdateTime();
    /**
     * Setters
     */
    public function setCategoryId($id);
    public function setTitle($title);
    public function setLayout($layout);
    public function setInfo($info);
    public function setStatus($status);
    public function setCreateTime($create_time);
    public function setUpdateTime($update_time);
}