<?php

namespace Iyzipay\Model;

use Iyzipay\Model\Mapper\PaymentPostAuthMapper;
use Iyzipay\Options;
use Iyzipay\Request\CreatePaymentPostAuthRequest;

class PaymentPostAuth extends PaymentResource
{
    public static function create(CreatePaymentPostAuthRequest $request, Options $options)
    {
        $uri = "/payment/postauth";
        $rawResult = parent::httpClient()->post($options->getBaseUrl() . $uri, parent::getHttpHeadersV2($uri, $request, $options), $request->toJsonString());
        return PaymentPostAuthMapper::create($rawResult)->jsonDecode()->mapPaymentPostAuth(new PaymentPostAuth());
    }
}