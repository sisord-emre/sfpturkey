<?php

namespace Iyzipay\Model;

use Iyzipay\IyzipayResource;
use Iyzipay\Model\Mapper\BasicBkmInitializeMapper;
use Iyzipay\Options;
use Iyzipay\Request\CreateBasicBkmInitializeRequest;

class BasicBkmInitialize extends IyzipayResource
{
    private $htmlContent;
    private $token;

    public static function create(CreateBasicBkmInitializeRequest $request, Options $options)
    {
        $uri = "/payment/bkm/initialize/basic";
        $rawResult = parent::httpClient()->post($options->getBaseUrl() . $uri, parent::getHttpHeadersV2($uri, $request, $options), $request->toJsonString());
        return BasicBkmInitializeMapper::create($rawResult)->jsonDecode()->mapBasicBkmInitialize(new BasicBkmInitialize());
    }

    public function getHtmlContent()
    {
        return $this->htmlContent;
    }

    public function setHtmlContent($htmlContent)
    {
        $this->htmlContent = $htmlContent;
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