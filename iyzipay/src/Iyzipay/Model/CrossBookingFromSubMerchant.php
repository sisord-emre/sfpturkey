<?php

namespace Iyzipay\Model;

use Iyzipay\IyzipayResource;
use Iyzipay\Model\Mapper\CrossBookingFromSubMerchantMapper;
use Iyzipay\Options;
use Iyzipay\Request\CreateCrossBookingRequest;

class CrossBookingFromSubMerchant extends IyzipayResource
{
    public static function create(CreateCrossBookingRequest $request, Options $options)
    {
        $uri = "/crossbooking/receive";
        $rawResult = parent::httpClient()->post($options->getBaseUrl() . $uri, parent::getHttpHeadersV2($uri, $request, $options), $request->toJsonString());
        return CrossBookingFromSubMerchantMapper::create($rawResult)->jsonDecode()->mapCrossBookingFromSubMerchant(new CrossBookingFromSubMerchant());
    }
}