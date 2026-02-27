<?php

namespace Iyzipay\Model;

use Iyzipay\Model\Mapper\RefundMapper;
use Iyzipay\Options;
use Iyzipay\Request\CreateRefundRequest;

class Refund extends RefundResource
{
    public static function create(CreateRefundRequest $request, Options $options)
    {
        $uri = "/payment/refund";
        $rawResult = parent::httpClient()->post($options->getBaseUrl() . $uri, parent::getHttpHeadersV2($uri, $request, $options), $request->toJsonString());
        return RefundMapper::create($rawResult)->jsonDecode()->mapRefund(new Refund());
    }
}