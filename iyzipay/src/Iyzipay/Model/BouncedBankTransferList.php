<?php

namespace Iyzipay\Model;

use Iyzipay\IyzipayResource;
use Iyzipay\Model\Mapper\BouncedBankTransferListMapper;
use Iyzipay\Options;
use Iyzipay\Request\RetrieveTransactionsRequest;

class BouncedBankTransferList extends IyzipayResource
{
    private $bankTransfers;

    public static function retrieve(RetrieveTransactionsRequest $request, Options $options)
    {
        $uri = "/reporting/settlement/bounced";
        $rawResult = parent::httpClient()->post($options->getBaseUrl() . $uri, parent::getHttpHeadersV2($uri, $request, $options), $request->toJsonString());
        return BouncedBankTransferListMapper::create($rawResult)->jsonDecode()->mapBouncedBankTransferList(new BouncedBankTransferList());
    }

    public function getBankTransfers()
    {
        return $this->bankTransfers;
    }

    public function setBankTransfers($bankTransfers)
    {
        $this->bankTransfers = $bankTransfers;
    }
}