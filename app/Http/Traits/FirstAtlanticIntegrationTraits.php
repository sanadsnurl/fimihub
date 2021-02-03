<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
//user import section
use App\User;
use App\Model\cart;
use App\Model\cart_submenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use File;
use SoapClient;

use function GuzzleHttp\json_encode;

trait FirstAtlanticIntegrationTraits
{
    function makeFirstAtlanticPayment($payment_data)
    {
        $order_id = $payment_data['order_unique_id'].'-'.$payment_data['order_id'].'-';
        $amt = round($payment_data['amount'],2);
        $date_process = str_replace(" ", '',$payment_data['card_expiry_date']);
        $date_process = str_replace("/", '',$date_process);
        $card_exp_date = trim($date_process);
        $card_process = str_replace(" ", '',$payment_data['card_number']);
        $card_number_input = trim($card_process);
        $base_url_api = Config('FAC_URL');
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
            $stringtohash = $passwd . $facId . $acquirerId . $orderNumber . $amount . $currency;
            $hash = sha1($stringtohash, true);
            $signature = base64_encode($hash);

            return $signature;
        }

        // Ensure you append the ?wsdl query string to the url
        $wsdlurl = $base_url_api.'?wsdl';
        // Set up client to use SOAP 1.1 and NO CACHE for WSDL. You can choose
        //between
        // exceptions or status checking. Here we use status checking. Trace is for
        //Debug only
        // Works better with MS Web Services where
        // WSDL is split into several files. Will fetch all the WSDL up front.
        $options = array(
            'location' => $base_url_api,
            'soap_version' => SOAP_1_1,
            'exceptions' => 0,
            'trace' => 1,
            'cache_wsdl' => WSDL_CACHE_NONE
        );
        // WSDL Based calls use a proxy, so this is the best way
        // to call FAC PG Operations.
        $client = new SoapClient($wsdlurl, $options);
        // This should not be in your code in plain text!
        $password = Config('first_atlantic_password');
        //92 of 136
        // Use your own FAC ID
        $facId = Config('fac_id');
        // Acquirer is always 464748
        $acquirerId = Config('acquirer_id');
        // orderNumber must be Unique per order. Put your own format here
        $orderNumber = $order_id. msTimeStamp();
        // 12 chars, always, no decimal place, 'RI'.str_pad(1, 8, "0", STR_PAD_LEFT);

        $total_amounts = round($amt,2)*100;
        $amount =  str_pad($total_amounts, 12, "0", STR_PAD_LEFT);
        // $amount = '000000001350';
        // 840 = USD, put your currency code here
        $currency = '388';
        $signature = Sign($password, $facId, $acquirerId, $orderNumber, $amount, $currency);
        // You only need to initialise the message sections you need. So for a basic
        //Auth
        // only Credit Cards and Transaction details are required.
        // Card Details. Arrays serialise to elements in XML/SOAP
        $CardDetails = array(
            'CardCVV2' => $payment_data['card_ccv'],
            'CardExpiryDate' => $card_exp_date,
            'CardNumber' => $card_number_input,
            'IssueNumber' => $payment_data['issue_number'],
            'StartDate' => $payment_data['start_date']
        );
        // Transaction Details.
        $TransactionDetails = array(
            'AcquirerId' => $acquirerId,
            'Amount' => $amount,
            'Currency' => $currency,
            'CurrencyExponent' => 2,
            'IPAddress' => 'https://fimihub.com',
            'MerchantId' => $facId,
            'OrderNumber' => $orderNumber,
            'Signature' => $signature,
            'SignatureMethod' => 'SHA1',
            'TransactionCode' => '64'
        );
        // The request data is named 'Request' for reasons that are not clear!
        $AuthorizeRequest = array('Request' => array(
            'CardDetails' => $CardDetails,

            'TransactionDetails' => $TransactionDetails
        ));
        // For debug, to check the values are OK
        // Call the Authorize through the Client

        $result = $client->Authorize($AuthorizeRequest);
        return $result;
    }
}
