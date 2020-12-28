<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en-GB" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Checkout</title>
    <link
        href="/SENTRY/PaymentGateway/Merchant/Administration/MerchantPages/88801357/JNCBCV/Checkout/css/blueprint/screen.css"
        rel="stylesheet" type="text/css" media="screen, projection">
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js" defer="defer"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.17.0/jquery.validate.min.js" defer="defer"></script>
</head>

<body data-new-gr-c-s-check-loaded="14.984.0" data-gr-ext-installed="">
    <p>&nbsp;</p>
    <form class="span-24" id="FrmCheckout" style="background-color: rgb(255, 255, 255);" action="" method="get">
        <input name="Downgrade3DS" id="Downgrade3DS" type="hidden" value="false">
        <div class="container">
            <div class="span-24">
                <p>&nbsp;</p>
            </div>
            <div class="span-12 prepend-6">
                <div class="top" style="text-align: center;">
                </div>
                <div>
                    <fieldset id="cardDetails">
                        <legend>Payment Details</legend> <br>
                        <label for="CardNo">Card Number</label><br>
                        <input name="CardNo" tabindex="1" id="CardNo" type="text" size="50" maxlength="16"><br>
                        <label for="CardExpDate">Expiry Date(MMYY)</label><br>
                        <input name="CardExpDate" tabindex="2" id="CardExpDate" type="text" size="10" maxlength="4"><br>
                        <label for="CardCVV2">Security Code (CVV2)</label><br>
                        <input name="CardCVV2" tabindex="3" id="CardCVV2" type="text" size="10" maxlength="4">
                        <div>
                            <img width="45" height="30" alt="Visa Logo"
                                src="/SENTRY/PaymentGateway/Merchant/Administration/MerchantPages/88801357/JNCBCV/Checkout/Images/visa-logo.jpg">&nbsp;&nbsp;
                            <img width="45" height="30" alt="MC Logo"
                                src="/SENTRY/PaymentGateway/Merchant/Administration/MerchantPages/88801357/JNCBCV/Checkout/Images/mastercard_logo.gif">&nbsp;&nbsp;
                            <img width="50" height="30" alt="VBV Logo"
                                src="/SENTRY/PaymentGateway/Merchant/Administration/MerchantPages/88801357/JNCBCV/Checkout/Images/VerifiedByVisa.jpg">&nbsp;&nbsp;
                            <img width="45" height="30" alt="MCSC Logo"
                                src="/SENTRY/PaymentGateway/Merchant/Administration/MerchantPages/88801357/JNCBCV/Checkout/Images/sc-mastercard-securecode.png">&nbsp;&nbsp;
                            <img width="55" height="30" alt="FAC Logo"
                                src="/SENTRY/PaymentGateway/Merchant/Administration/MerchantPages/88801357/JNCBCV/Checkout/Images/Powered-by-FAC_web.jpg">
                        </div>
                    </fieldset>
                </div>
                <div>
                    <input name="BtnSubmit" tabindex="5" id="BtnSubmit"
                        style="width: 150px; height: 25px; cursor: pointer;" type="submit" value="Confirm Payment">
                </div>
                <div>
                    &nbsp;
                    <!--  <div id="InputEvalMsg"><span style="color: #CD3700; font-weight: 700;">Payment amount not specified</span><br> -->
                    <span style="color: #CD3700; font-weight: 700;">Card Number not entered or invalid</span><br>
                    <span style="color: #CD3700; font-weight: 700;">Expiry Date not entered or invalid</span><br>
                    <span style="color: #CD3700; font-weight: 700;">Security Code not entered or invalid</span><br>
                </div>
            </div>
        </div>
        <div class="span-24">
            <p>&nbsp;</p>
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(onDocumentReady);

        function onDocumentReady() {
            DisableSubmit(false);
            evalFormValues();
            ConnectEvaluation();
            DisableButtonOnSubmit();
            $("#FrmCheckout").on("submit", function() {
                //reads entered card number from an input on the page
                var cardNum = $("#CardNo").val();
                // for Discovery and AMEX a 3ds auth is downgraded to regular auth
                if ((cardNum && cardNum.indexOf("6") === 0) //Discover
                    ||
                    (cardNum && cardNum.indexOf("34") === 0) //AMEX
                    ||
                    (cardNum && cardNum.indexOf("37") === 0) //AMEX
                    ||
                    (cardNum && cardNum.indexOf("777774") === 0) //JNCB
                ) {
                    $("#Downgrade3DS").val("true");
                    $("#FrmCheckout").attr("action", "/MerchantPages/HostedPage.aspx")
                }
            });
        }

        function DisableButtonOnSubmit() {
            $('#FrmCheckout').submit(function() {
                $("#BtnSubmit").prop("disabled", true);
            });
        }

        function ConnectEvaluation() {
            $("input").keyup(function() {
                evalFormValues()
            });
        }

        function DisableSubmit() {
            $("#BtnSubmit").attr("disabled", "disabled");
        }

        function EnableSubmit() {
            $("#BtnSubmit").removeAttr("disabled");
        }

        function AddEvalError(errmsg) {
            $("div#InputEvalMsg").append('<span style="color: #CD3700; font-weight: 700;">' + errmsg + '</span><br>');
        }
        //Evaluate Expiry Date (4 digits, month between 1 and 12, year greater than 2000)
        function EvalExpiryDate(source) {
            source = source.trim();
            if (isNaN(source) || source.length !== 4) {
                return false;
            }
            /* var amount = parseInt(source, 10);
             if (isNaN(amount) || amount <= 0) {
                 return false;
             }*/
            //Get the current Month and Year
            var currentTime = new Date();
            //Get the entered month
            var monthNo = parseInt(source.substring(0, 2), 10);
            if (isNaN(monthNo) || monthNo < 1 || monthNo > 12) {
                return false;
            }
            //Get the entered year
            var yearNo = parseInt(source.substring(2), 10) + 2000;
            if (isNaN(yearNo) || yearNo < currentTime.getFullYear()) {
                return false;
            }
            //Compare the entered Month and Year is greater or equal than the current ones
            if (yearNo * 12 + (monthNo - 1) < currentTime.getFullYear() * 12 + currentTime.getMonth()) {
                return false;
            }
            return true;
        }
        //Evaluate CVV (positive number of 3 or 4 digits)
        function EvalCVV2(source) {
            source = source.trim();
            if (isNaN(source) || source.length < 3 || source.length > 4) {
                return false;
            }
            return true;
        }

        function EvalCardNo(inputtxt) {
            //Evaluates the card starts with a digit (2 to 6) and has a total length of between 14 and 16 digits
            var regex = /^(?:[2-7][0-9]{13,15})$/;
            if (isNaN(inputtxt)) {
                return false;
            }
            return (regex.test(inputtxt));
        }

        function evalFormValues() {
            $("div#InputEvalMsg").empty();
            DisableSubmit();
            var isError = false;
            /* var amount = parseInt($("#Amount").val(), 10);
             if (isNaN(amount) || amount <= 0) {
                 isError = true;
                 AddEvalError('Payment amount not specified');
             }*/
            var cardNo = $("input#CardNo").val();
            if (!EvalCardNo(cardNo)) {
                isError = true;
                AddEvalError('Card Number not entered or invalid');
            }
            var expDate = $("input#CardExpDate").val();
            if (!EvalExpiryDate(expDate)) {
                isError = true;
                AddEvalError('Expiry Date not entered or invalid');
            }
            var cardCVV2 = $("input#CardCVV2").val();
            if (!EvalCVV2(cardCVV2)) {
                isError = true;
                AddEvalError('Security Code not entered or invalid');
            }
            if (isError) {
                DisableSubmit();
            } else {
                EnableSubmit();
            }
        }
    </script>
</body>

</html>
