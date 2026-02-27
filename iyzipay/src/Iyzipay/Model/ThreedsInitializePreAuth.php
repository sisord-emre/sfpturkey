<?php

namespace Iyzipay\Model;

use Iyzipay\IyzipayResource;
use Iyzipay\Model\Mapper\ThreedsInitializePreAuthMapper;
use Iyzipay\Options;
use Iyzipay\Request\CreatePaymentRequest;

class ThreedsInitializePreAuth extends IyzipayResource
{
    private $htmlContent;

    public static function create(CreatePaymentRequest $request, Options $options)
    {
        $uri = "/payment/3dsecure/initialize/preauth";
        $rawResult = parent::httpClient()->post($options->getBaseUrl() . $uri, parent::getHttpHeadersV2($uri, $request, $options), $request->toJsonString());
        return ThreedsInitializePreAuthMapper::create($rawResult)->jsonDecode()->mapThreedsInitializePreAuth(new ThreedsInitializePreAuth());
    }

    public function getHtmlContent()
    {
        return $this->htmlContent;
    }

    public function setHtmlContent($htmlContent)
    {
        $this->htmlContent = $htmlContent;
    }
}
