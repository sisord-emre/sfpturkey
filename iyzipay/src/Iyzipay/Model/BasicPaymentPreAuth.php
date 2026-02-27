<?php

namespace Iyzipay\Model;

use Iyzipay\Model\Mapper\BasicPaymentPreAuthMapper;
use Iyzipay\Options;
use Iyzipay\Request\CreateBasicPaymentRequest;

class BasicPaymentPreAuth extends BasicPaymentResource
{
    public static function create(CreateBasicPaymentRequest $request, Options $options)
    {
        $uri = "/payment/preauth/basic";
        $rawResult = parent::httpClient()->post($options->getBaseUrl() . $uri, parent::getHttpHeadersV2($uri, $request, $options), $request->toJsonString());
        return BasicPaymentPreAuthMapper::create($rawResult)->jsonDecode()->mapBasicPaymentPreAuth(new BasicPaymentPreAuth());
    }
}