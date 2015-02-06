<?php
	include('ebayFunctions.php');

	///print_r(new simpleXMLElement(set_taxtable()));
	$xml = new simpleXMLElement(get_taxtable());
	print_r($xml);

function set_taxtable(){
	global $token;

	$post_data = '<?xml version="1.0" encoding="utf-8"?>
<SetTaxTableRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <TaxTable>
    <TaxJurisdiction>
        <JurisdictionID>AZ</JurisdictionID>
        <SalesTaxPercent>8.25</SalesTaxPercent>
        <ShippingIncludedInTax>true</ShippingIncludedInTax>
      </TaxJurisdiction>
      <TaxJurisdiction>
        <JurisdictionID>MI</JurisdictionID>
        <SalesTaxPercent>6.0</SalesTaxPercent>
        <ShippingIncludedInTax>false</ShippingIncludedInTax>
      </TaxJurisdiction>
  </TaxTable>
  <ErrorLanguage>en_US</ErrorLanguage>
  <RequesterCredentials>
		<eBayAuthToken>'.$token.'</eBayAuthToken>
		</RequesterCredentials>
  <WarningLevel>High</WarningLevel>
</SetTaxTableRequest>';

$body = callapi($post_data,'SetTaxTable');
return $body;
}

?>
