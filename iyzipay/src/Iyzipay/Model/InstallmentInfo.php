<?php

namespace Iyzipay\Model;

use Iyzipay\IyzipayResource;
use Iyzipay\Model\Mapper\InstallmentInfoMapper;
use Iyzipay\Options;
use Iyzipay\Request\RetrieveInstallmentInfoRequest;

class InstallmentInfo extends IyzipayResource
{
    private $installmentDetails;

    public static function retrieve(RetrieveInstallmentInfoRequest $request, Options $options)
    {
        $uri = "/payment/iyzipos/installment";
        $rawResult = parent::httpClient()->post($options->getBaseUrl() . $uri, parent::getHttpHeadersV2($uri, $request, $options), $request->toJsonString());
        return InstallmentInfoMapper::create($rawResult)->jsonDecode()->mapInstallmentInfo(new InstallmentInfo());
    }

    public function getInstallmentDetails()
    {
        return $this->installmentDetails;
    }

    public function setInstallmentDetails($installmentDetails)
    {
        $this->installmentDetails = $installmentDetails;
    }
}