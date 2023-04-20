<?php
require_once(dirname(__DIR__).'/IyzipayBootstrap.php');

include("../../Panel/System/Config.php");

define("odemeFirmaPublicKey", $sabitB["sabitBilgiEposPublicKey"]);
define("odemeFirmaPrivateKey", $sabitB["sabitBilgiPrivateKey"]);

IyzipayBootstrap::init();

class Config
{
  public static function options()
  {
    $options = new \Iyzipay\Options();
    $options->setApiKey(odemeFirmaPublicKey);
    $options->setSecretKey(odemeFirmaPrivateKey);
    $options->setBaseUrl('https://api.iyzipay.com');
    //$options->setApiKey('sandbox-9pm0NtyV6QhdzC8ewC44k8DArZio3dmY');//test
    //$options->setSecretKey('sandbox-ISHhQqp4MB0rIpWL2MczfN2jtLygmp8D');//test
    //$options->setBaseUrl('https://sandbox-api.iyzipay.com');//test

    return $options;
  }
}
