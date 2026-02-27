<?php

namespace Iyzipay\Model;

use Iyzipay\Model\Mapper\BkmMapper;
use Iyzipay\Options;
use Iyzipay\Request\RetrieveBkmRequest;

class Bkm extends PaymentResource
{
    private $token;
    private $callbackUrl;

    public static function retrieve(RetrieveBkmRequest $request, Options $options)
    {
        $uri = "/payment/bkm/auth/detail";
        $rawResult = parent::httpClient()->post($options->getBaseUrl() . $uri, parent::getHttpHeadersV2($uri, $request, $options), $request->toJsonString());
        return BkmMapper::create($rawResult)->jsonDecode()->mapBkm(new Bkm());
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
    }
}