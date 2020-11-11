<?php

namespace Hanson\Meituan\Application\Waimai\Dish;

use Hanson\Meituan\Core\Api;

class Dish extends Api
{

    const QUERY_BASE_BY_POI_ID_API = 'https://api-open-cater.meituan.com/waimai/dish/queryBaseListByEPoiId';
    const DISH_MAPPING_API = 'https://open-erp.meituan.com/waimai-dish-mapping';
    const MAPPING_API = 'https://api-open-cater.meituan.com/waimai/dish/mapping';
    const QUERY_BY_POI_ID_API = 'https://api-open-cater.meituan.com/waimai/dish/queryListByEPoiId';
    const QUERY_CAT_LIST_API = 'https://api-open-cater.meituan.com/waimai/dish/queryCatList';
    const BATCH_UPLOAD_API = 'https://api-open-cater.meituan.com/waimai/dish/batchUpload';
    const UPDATE_PRICE_API = 'https://api-open-cater.meituan.com/waimai/dish/updatePrice';
    const UPDATE_STOCK_API = 'https://api-open-cater.meituan.com/waimai/dish/updateStock';
    const UPDATE_CAT_API = 'https://api-open-cater.meituan.com/waimai/dish/updateCat';
    const UPLOAD_IMAGE_API = 'https://api-open-cater.meituan.com/waimai/image/upload';
    const DELETE_API = 'https://api-open-cater.meituan.com/waimai/dish/delete';
    const DELETE_SKU_API = 'https://api-open-cater.meituan.com/waimai/dish/deleteSku';
    const DELETE_CAT_API = 'https://api-open-cater.meituan.com/waimai/dish/deleteCat';
    const QUERY_PROPERTY_LIST_API = 'https://api-open-cater.meituan.com/waimai/dish/queryPropertyList';
    const UPDATE_PROPETY_API = 'https://api-open-cater.meituan.com/waimai/dish/updateProperty';
    const QUERY_BY_EDISH_CODES_API = 'https://api-open-cater.meituan.com/waimai/dish/queryListByEdishCodes';

    /**
     * 根据ERP的门店id查询门店下的菜品基础信息【包含美团的菜品Id】.
     *
     * @param $ePoiId
     * @return array
     */
    public function queryBaseListByEPoiId($ePoiId)
    {
        return $this->request('get', [self::QUERY_BASE_BY_POI_ID_API, ['ePoiId' => $ePoiId]]);
    }

    /**
     * 映射 UISDK URL
     *
     * @param int  $ePoiId
     * @param int  $businessId
     *
     * @return string
     */
    public function uiSdkUrl($ePoiId, $businessId = 2)
    {
        $params = [
            'developerId' => $this->accessToken->getDeveloperId(),
            'ePoiId' => $ePoiId,
            'businessId' => $businessId,
            'timestamp' => str_pad(time(), 13, 0)
        ];
        $params['sign'] = $this->accessToken->signature($params);

        return 'https://open-erp.meituan.com/storemap?'.http_build_query($params);
    }

    /**
     * 菜品映射链接.
     *
     * @param $ePoiId
     * @return string
     */
    public function getDishMapUrl($ePoiId)
    {
        $params = [
            'ePoiId' => $ePoiId,
            'appAuthToken' => $this->accessToken->getAuthToken(),
            'timestamp' => time(),
        ];

        $params['sign'] = $this->accessToken->signature($params);

        return self::DISH_MAPPING_API . '?' . http_build_query($params);
    }

    /**
     * 重定向至菜品映射链接.
     *
     * @param $ePoiId
     */
    public function redirectDishMap($ePoiId)
    {
        header('Location:' . $this->getDishMapUrl($ePoiId));
    }

    /**
     * API 形式对接菜品.
     *
     * @param $params
     * @return array
     */
    public function mapping($params)
    {
        return $this->request('post', [self::MAPPING_API, $params]);
    }

    /**
     * 根据ERP的门店id查询门店下的菜品【不包含美团的菜品Id】.
     *
     * @param $params
     * @return array
     */
    public function queryListByEPoiId($params)
    {
        return $this->request('get', [self::QUERY_BY_POI_ID_API, $params]);
    }

    /**
     * 查询菜品分类
     *
     * @return array
     */
    public function queryCatList()
    {
        return $this->request('get', [self::QUERY_CAT_LIST_API, []]);
    }

    /**
     * 批量上传／更新菜品
     *
     * @param $params
     * @return array
     */
    public function batchUpload($params)
    {
        return $this->request('post', [self::BATCH_UPLOAD_API, $params]);
    }

    /**
     * 更新菜品价格【sku的价格】
     *
     * @param $params
     * @return array
     */
    public function updatePrice($params)
    {
        return $this->request('post', [self::UPDATE_PRICE_API, $params]);
    }

    /**
     * 更新菜品库存【sku的库存】
     *
     * @param $params
     * @return array
     */
    public function updateStock($params)
    {
        return $this->request('post', [self::UPDATE_STOCK_API, $params]);
    }

    /**
     * 新增／更新菜品分类
     *
     * @param $params
     * @return array
     */
    public function updateCat($params)
    {
        return $this->request('post', [self::UPDATE_CAT_API, $params]);
    }

    /**
     * 上传菜品图片
     *
     * @param $params
     * @param $file
     * @return array
     */
    public function uploadImage($params, $file)
    {
        return $this->request('upload', [self::UPLOAD_IMAGE_API, $params], ['file' => $file]);
    }

    /**
     * 删除菜品
     *
     * @param $params
     * @return array
     */
    public function delete($params)
    {
        return $this->request('post', [self::DELETE_API, $params]);
    }

    /**
     * 删除菜品sku
     *
     * @param $params
     * @return array
     */
    public function deleteSku($params)
    {
        return $this->request('post', [self::DELETE_SKU_API, $params]);
    }

    /**
     * 删除菜品分类
     *
     * @param $params
     * @return array
     */
    public function deleteCat($params)
    {
        return $this->request('post', [self::DELETE_CAT_API, $params]);
    }

    /**
     * 查询菜品属性
     *
     * @param $eDishCode
     * @return array
     */
    public function queryPropertyList($eDishCode)
    {
        return $this->request('get', [self::QUERY_PROPERTY_LIST_API, ['eDishCode' => $eDishCode]]);
    }

    /**
     * 批量创建/更新菜品属性
     *
     * @param $params
     * @return array
     */
    public function updateProperty($params)
    {
        return $this->request('post', [self::UPDATE_PROPETY_API, ['dishProperty' => $params]]);
    }

    /**
     * 查询菜品信息
     *
     * @param $ePoiId
     * @param $eDishCodes
     * @return array
     */
    public function queryListByEdishCodes($ePoiId, $eDishCodes)
    {
        return $this->request('post', [self::QUERY_BY_EDISH_CODES_API, ['ePoiId' => $ePoiId, 'eDishCodes' => $eDishCodes]]);
    }

}
