<?php

namespace Iyzipay\Model;

use Iyzipay\Model\Mapper\BasicPaymentMapper;
use Iyzipay\Options;
use Iyzipay\Request\CreateBasicPaymentRequest;

class BasicPayment extends BasicPaymentResource
{
    public static function create(CreateBasicPaymentRequest $request, Options $options)
    {
        $uri = "/payment/auth/basic";
        $rawResult = parent::httpClient()->post($options->getBaseUrl() . $uri, parent::getHttpHeadersV2($uri, $request, $options), $request->toJsonString());
        return BasicPaymentMapper::create($rawResult)->jsonDecode()->mapBasicPayment(new BasicPayment());
    }
}