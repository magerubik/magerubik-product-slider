<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Model\DataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magerubik\Productslider\Api\SliderRepositoryInterface;
use Magerubik\Productslider\Api\Data\SliderInterface;
use Magerubik\Productslider\Model\ResourceModel\Slider\CollectionFactory;
class SliderDataProvider extends AbstractDataProvider
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var SliderRepositoryInterface
     */
    private $sliderRepository;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        DataPersistorInterface $dataPersistor,
        CollectionFactory $collectionFactory,
        SliderRepositoryInterface $sliderRepository,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->sliderRepository = $sliderRepository;
        $this->collectionFactory = $collectionFactory;
    }
    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        $data = parent::getData();
        if ($data['totalRecords'] > 0) {
            $sliderId = (int)$data['items'][0]['productslider_id'];
            $model = $this->sliderRepository->getById($sliderId);
            if ($model) {
                $sliderData = $model->getData();
                $data[$sliderId] = $sliderData;
            }
        }
        if ($savedData = $this->dataPersistor->get('magerubik_productslider_slider')) {
            $savedSliderId = isset($savedData['productslider_id']) ? $savedData['productslider_id'] : null;
            $data[$savedSliderId] = isset($data[$savedSliderId])
                ? array_merge($data[$savedSliderId], $savedData)
                : $savedData;
            $this->dataPersistor->clear('magerubik_productslider_slider');
        }
        return $data;
    }

}