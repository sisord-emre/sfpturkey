<?php

namespace Iyzipay\Model;

use Iyzipay\IyzipayResource;
use Iyzipay\Model\Mapper\InstallmentHtmlMapper;
use Iyzipay\Options;
use Iyzipay\Request\RetrieveInstallmentInfoRequest;

class InstallmentHtml extends IyzipayResource
{
    private $htmlContent;

    public static function retrieve(RetrieveInstallmentInfoRequest $request, Options $options)
    {
        $uri = "/payment/iyzipos/installment/html/horizontal";
        $rawResult = parent::httpClient()->post($options->getBaseUrl() . $uri, parent::getHttpHeadersV2($uri, $request, $options), $request->toJsonString());
        return InstallmentHtmlMapper::create($rawResult)->jsonDecode()->mapInstallmentHtml(new InstallmentHtml());
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
