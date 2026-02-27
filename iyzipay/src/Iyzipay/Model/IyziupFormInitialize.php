<?php

namespace Iyzipay\Model;

use Iyzipay\Model\Mapper\IyziupFormInitializeMapper;
use Iyzipay\Options;
use Iyzipay\Request\CreateIyziupFormInitializeRequest;

class IyziupFormInitialize extends IyziupFormInitializeResource
{
    public static function create(CreateIyziupFormInitializeRequest $request, Options $options)
    {
        $uri = "/v1/iyziup/form/initialize";
        $rawResult = parent::httpClient()->post($options->getBaseUrl() . $uri, parent::getHttpHeadersV2($uri, $request, $options), $request->toJsonString());
        return IyziupFormInitializeMapper::create($rawResult)->jsonDecode()->mapIyziupFormInitialize(new IyziupFormInitialize());
    }
}