<?php

namespace Iyzipay\Model;

use Iyzipay\Model\Mapper\SettlementToBalanceMapper;
use Iyzipay\Options;
use Iyzipay\Request\CreateSettlementToBalanceRequest;

class SettlementToBalance extends SettlementToBalanceResource
{
    public static function create(CreateSettlementToBalanceRequest $request, Options $options)
    {
        $uri = "/payment/settlement-to-balance/init";
        $rawResult = parent::httpClient()->post($options->getBaseUrl() . $uri, parent::getHttpHeadersV2($uri, $request, $options), $request->toJsonString());

        return SettlementToBalanceMapper::create($rawResult)->jsonDecode()->mapSettlementToBalance(new SettlementToBalance());
    }
}