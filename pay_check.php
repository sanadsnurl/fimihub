<?php
// Payment authantication
//phpinfo(); die;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Useful for generation of test Order numbers
function msTimeStamp()
{
	return (string)round(microtime(1) * 1000);
}
// How to sign a FAC Authorize message
function Sign($passwd, $facId, $acquirerId, $orderNumber, $amount, $currency)
{
	 $stringtohash = $passwd.$facId.$acquirerId.$orderNumber.$amount.$currency;
	 $hash = sha1($stringtohash, true);
	 $signature = base64_encode($hash);

	 return $signature;
}

// Ensure you append the ?wsdl query string to the url
$wsdlurl =
'https://ecm.firstatlanticcommerce.com/PGService/Services.svc?wsdl';
// Set up client to use SOAP 1.1 and NO CACHE for WSDL. You can choose
//between
// exceptions or status checking. Here we use status checking. Trace is for
//Debug only
// Works better with MS Web Services where
// WSDL is split into several files. Will fetch all the WSDL up front.
$options = array(
 'location' =>
'https://ecm.firstatlanticcommerce.com/PGService/Services.svc',
 'soap_version'=>SOAP_1_1,
 'exceptions'=>0,
 'trace'=>1,
 'cache_wsdl'=>WSDL_CACHE_NONE
 );
// WSDL Based calls use a proxy, so this is the best way
// to call FAC PG Operations.
$client = new SoapClient($wsdlurl , $options);
// This should not be in your code in plain text!
$password = 'Oy54vDbK';
//92 of 136
// Use your own FAC ID
$facId = '88802005';
// Acquirer is always 464748
$acquirerId = '464748';
// orderNumber must be Unique per order. Put your own format here
$orderNumber = 'DIVAY' . msTimeStamp();
// 12 chars, always, no decimal place
$amount = '000000001200';
// 840 = USD, put your currency code here
$currency = '388';
$signature = Sign($password, $facId, $acquirerId, $orderNumber, $amount,$currency);
// You only need to initialise the message sections you need. So for a basic
//Auth
// only Credit Cards and Transaction details are required.
// Card Details. Arrays serialise to elements in XML/SOAP
$CardDetails = array('CardCVV2' => '123',
 'CardExpiryDate' => '0922',
 'CardNumber' => '4111111111111111',
 'IssueNumber' => '',
 'StartDate' => '');
// Transaction Details.
$TransactionDetails = array('AcquirerId' => $acquirerId,
 'Amount' => $amount,
 'Currency' => $currency,
 'CurrencyExponent' => 2,
 'IPAddress' => 'fimihub.com',
 'MerchantId' => $facId,
 'OrderNumber' =>
 $orderNumber,
'Signature' => $signature,
 'SignatureMethod' => 'SHA1',
 'TransactionCode' => '0');
// The request data is named 'Request' for reasons that are not clear!
$AuthorizeRequest = array('Request' => array('CardDetails' => $CardDetails,

'TransactionDetails' => $TransactionDetails));
// For debug, to check the values are OK
//var_dump($AuthorizeRequest);
// Call the Authorize through the Client
$result = $client->Authorize($AuthorizeRequest);

echo "<pre>";
print_r($client);
// Check for a fault
if ($client->fault) {
 echo '<h2>Fault</h2><pre>';
//93 of 136
 print_r($result);
 echo '</pre>';
} else {
 // Check for errors
 $err = $client->error;
 if ($err) {
 // Display the error
 echo '<h2>Error</h2><pre>' . $err . '</pre>';
 } else {
 // Display the result
 echo '<h2>Result</h2><pre>';
 print_r($result);
 echo '</pre>';
 }
}
?>
