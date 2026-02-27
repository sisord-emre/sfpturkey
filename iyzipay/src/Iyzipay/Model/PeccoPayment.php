<?php

namespace Iyzipay\Model;

use Iyzipay\Model\Mapper\PeccoPaymentMapper;
use Iyzipay\Options;
use Iyzipay\Request\CreatePeccoPaymentRequest;

class PeccoPayment extends PaymentResource
{
    private $token;

    public static function create(CreatePeccoPaymentRequest $request, Options $options)
    {
        $uri = "/payment/pecco/auth";
        $rawResult = parent::httpClient()->post($options->getBaseUrl() . $uri, parent::getHttpHeadersV2($uri, $request, $options), $request->toJsonString());
        return PeccoPaymentMapper::create($rawResult)->jsonDecode()->mapPeccoPayment(new PeccoPayment());
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }
}