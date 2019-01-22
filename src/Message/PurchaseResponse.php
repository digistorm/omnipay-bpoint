<?php

namespace Omnipay\Bpoint\Message;

class PurchaseResponse extends AbstractResponse
{
    const API_CODE_MESSAGES = [
        '?' => 'Response Unknown',
        '6' => 'Transaction Declined - Error Communicating with Bank',
        '7' => 'Payment Server Processing Error - Typically caused by invalid input data such as an invalid card number. Processing errors can also occur',
        '8' => 'Transaction Declined - Transaction Type Not Supported',
        '9' => 'Bank Declined Transaction (Do not contact Bank)',
        'A' => 'Transaction Aborted',
        'C' => 'Transaction Cancelled',
        'D' => 'Deferred Transaction',
        'E' => 'Issuer Returned a Referral Response',
        'F' => '3D Secure Authentication Failed',
        'I' => 'Card Security Code Failed',
        'L' => 'Shopping Transaction Locked (This indicates that there is another transaction taking place using the same shopping transaction number)',
        'N' => 'Cardholder is not enrolled in 3D Secure (Authentication Only)',
        'P' => 'Transaction is Pending',
        'R' => 'Retry Limits Exceeded, Transaction Not Processed',
        'S' => 'Duplicate OrderInfo used. (This is only relevant for Payment Servers that enforce the uniqueness of this field)',
        'U' => 'Card Security Code Failed',
        'PT_E1' => 'Database error.',
        'PT_E2' => 'Unable to encrypt card number.',
        'PT_E3' => 'Unable to decrypt card number.',
        'PT_E4' => 'Server shutdown in progress.',
        'PT_E5' => 'Server busy, transaction timed out in queue and was not sent to the bank.',
        'PT_E6' => 'Processing aborted, payment server is shutting down.',
        'PT_V1' => 'Invalid transaction type.',
        'PT_V2' => 'Invalid financial type.',
        'PT_V3' => 'Invalid amount.',
        'PT_V4' => 'Invalid card number.',
        'PT_V5' => 'Invalid expiry date.',
        'PT_V6' => 'Invalid CVN.',
        'PT_V7' => 'Financial transaction type not supported by gateway.',
        'PT_V8' => 'Reversal not supported.',
        'PT_V9' => 'Merchant/biller details not found.',
        'PT_V10' => 'Unable to retrieve merchant/biller details.',
        'PT_V11' => 'Cardholder not authenticated (VbV, SecureCode).',
        'PT_V12' => 'Error authenticating cardholder (VbV, SecureCode).',
        'PT_V13' => 'Invalid BSB number.',
        'PT_V14' => 'Invalid account number.',
        'PT_V15' => 'Invalid account name.',
        'PT_V16' => 'Payment details not provided.',
        'PT_V17' => 'No valid DDA found.',
        'PT_V18' => 'Payment failed anti fraud rule validation.',
        'PT_V19' => 'Refund not allowed, daily refund limit of X reached.',
        'PT_V20' => 'Refund not allowed, daily refund amount limit of $x.xx exceeded.',
        'PT_T1' => 'DVToken payment not allowed for Internet, IVR and call centre transaction types.',
        'PT_T2' => 'Card payment details not found for this dvtoken.',
        'PT_T3' => 'Unable to decrypt card number.',
        'PT_T4' => 'Unable to retrieve card payment details due to system error.',
        'PT_T5' => 'DVToken payment not supported.',
        'PT_T6' => 'DVToken not yet valid.',
        'PT_T7' => 'DVToken expired.',
        'PT_R1' => 'Original transaction not found.',
        'PT_R2' => 'Original transaction was not approved.',
        'PT_R3' => 'Original transaction is locked.',
        'PT_R4' => 'Transaction already fully refunded.',
        'PT_R5' => 'Only $x.xx available for refund.',
        'PT_R6' => 'Preauth transaction already completed.',
        'PT_R7' => 'Unable to verify if reversal can be processed.',
        'PT_R8' => 'Transaction already reversed.',
        'PT_R9' => 'Transaction partially refunded.',
        'PT_R10' => '(Only for reversals of timed out transactions) Original transaction not found.',
        'PT_R11' => '(Only for reversals of timed out transactions) Multiple instances of original transaction found.',
        'PT_R12' => '(Only for reversals of timed out transactions) Original transaction was not successful.',
        'PT_R13' => '(Only for reversals of timed out transactions) Original transaction number not found.',
        'PT_R14' => '(Only for reversals of timed out transactions) Error looking up result of original transaction.',
        'PT_R15' => 'Invalid amount. Reversal amount must be the same as the amount of the original transaction.',
        'PT_R16' => 'DE payment already rejected.',
        'PT_G1' => 'Gateway configuration error.',
        'PT_G2' => 'Unable to build gateway request.',
        'PT_G3' => 'Unable to connect to gateway.',
        'PT_G4' => 'Unable to send transaction request data.',
        'PT_G5' => 'Unable to get response data.',
        'PT_G6' => 'Unable to process transaction.',
        'PT_G7' => 'Unable to process, server busy.',
        'PT_G8' => 'Unable to parse response data.',
        'PT_G9' => 'PayPal gateway error.',
        'PT_G10' => 'Payment response details not present in PayPal response.',
        'PT_G11' => 'PayPal communication error.',
        'PT_G12' => 'Gateway error.',
        'PT_G13' => 'Message response timeout.',
    ];

    const BANK_CODE_MESSAGES = [
        '00' => 'Approved',
        '08' => 'Honour with ID',
        '16' => 'Approved, Update Track 3',
        '09' => 'Request in progress',
        '10' => 'Approved for partial amount',
        '11' => 'Approved VIP',
        '12' => 'Invalid transaction',
        '13' => 'Invalid amount',
        '17' => 'Customer cancellation',
        '18' => 'Customer dispute',
        '20' => 'Invalid response',
        '21' => 'No action taken',
        '22' => 'Suspected malfunction',
        '23' => 'Unacceptable transaction fee',
        '24' => 'File update not supported by receiver',
        '26' => 'Duplicate file update record, old record replaced',
        '27' => 'File update field edit error',
        '28' => 'File update file locked out',
        '29' => 'File update not successful, contact acquirer',
        '30' => 'Format error',
        '32' => 'Completed partially',
        '35' => 'Card acceptor contact acquirer',
        '37' => 'Card acceptor call acquirer security',
        '38' => 'Allowable PIN tries exceeded',
        '40' => 'Request function not supported',
        '42' => 'No universal account',
        '44' => 'No investment account',
        '45' => 'Reserved for ISO use',
        '46' => 'Reserved for ISO use',
        '47' => 'Reserved for ISO use',
        '48' => 'Reserved for ISO use',
        '49' => 'Reserved for ISO use',
        '50' => 'Reserved for ISO use',
        '52' => 'No cheque account',
        '53' => 'No savings account',
        '55' => 'Incorrect PIN',
        '56' => 'No card record',
        '57' => 'Transaction not permitted to cardholder',
        '58' => 'Transaction not permitted to acquirer',
        '60' => 'Card acceptor contact acquirer',
        '62' => 'Restricted card',
        '63' => 'Security violation',
        '64' => 'Original amount incorrect',
        '66' => 'Card acceptor call acquirer\'s security department',
        '67' => 'Hard capture (requires that the card be picked up at ATM)',
        '69' => 'Reserved for ISO use',
        '70' => 'Reserved for ISO use',
        '71' => 'Reserved for ISO use',
        '72' => 'Reserved for ISO use',
        '73' => 'Reserved for ISO use',
        '74' => 'Reserved for ISO use',
        '75' => 'Allowable number of PIN tries exceeded',
        '76' => 'Reserved for private use',
        '77' => 'Reserved for private use',
        '78' => 'Reserved for private use',
        '79' => 'Reserved for private use',
        '80' => 'Reserved for private use',
        '81' => 'Reserved for private use',
        '82' => 'Reserved for private use',
        '83' => 'Reserved for private use',
        '84' => 'Reserved for private use',
        '85' => 'Reserved for private use',
        '86' => 'Reserved for private use',
        '87' => 'Reserved for private use',
        '88' => 'Reserved for private use',
        '89' => 'Reserved for private use',
        '93' => 'Transaction cannot be completed, violation of law',
        '94' => 'Duplicate transmission',
        '95' => 'Reconcile error',
        '96' => 'System malfunction',
        '97' => 'Advises that reconciliation totals have been reset',
        '01' => 'Refer to card issuer',
        '02' => 'Refer to card issuer\'s special conditions',
        '03' => 'Invalid merchant',
        '04' => 'Pick up card',
        '05' => 'Do not honor',
        '06' => 'Error',
        '07' => 'Pick up card, special condition',
        '14' => 'Invalid card number',
        '15' => 'No such Issuer',
        '19' => 'Re-enter transaction',
        '25' => 'Unable to locate record on file',
        '31' => 'Bank not supported by switch',
        '34' => 'Suspected fraud',
        '36' => 'Restricted card',
        '39' => 'No credit account',
        '41' => 'Lost card',
        '43' => 'Stolen card, pick up',
        '59' => 'Suspected fraud',
        '61' => 'Exceeds withdrawal amount limits',
        '65' => 'Exceeds withdrawal frequency limit',
        '90' => 'Cut-off is in process (switch ending a days business and starting the next. Transaction can be sent again in a few minutes.)',
        '91' => 'Issuer or switch inoperative',
        '92' => 'Financial institution or intermediate network facility cannot be found for routing',
        '98' => 'MAC error',
        '99' => 'Reserved for National Use',
        '68' => 'Response received too late',
        '33' => 'Expired card',
        '54' => 'Expired card',
        '51' => 'Not sufficient funds',
    ];

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        if (!parent::isSuccessful()) {
            return false;
        }

        $responseCode = $this->data['TxnResp']['ResponseCode'];

        // These are the only valid API response codes for a successful purchase
        if ($responseCode == '0' || $responseCode == '00' || $responseCode == '08' || $responseCode == '16') {
            return true;
        }

        return false;
    }

    /**
     * Get the error message from the response.
     *
     * Returns null if the request was successful.
     *
     * @link https://www.bpoint.com.au/developers/v3/index.htm#!#txnResponses
     *
     * @return string|null
     */
    public function getMessage()
    {
        if (!parent::isSuccessful() && isset($this->data['APIResponse']) && isset($this->data['APIResponse']['ResponseText'])) {
            return $this->data['APIResponse']['ResponseText'];
        }

        if (!$this->isSuccessful()) {
            if (isset($this->data['ErrorString'])) {
                return $this->data['ErrorString'];
            }
            if (isset($this->data['TxnResp']['ResponseCode'], $this->data['TxnResp']['ResponseText'])) {
                $responseCode = $this->data['TxnResp']['ResponseCode'];
                $responseText = $this->data['TxnResp']['ResponseText'];

                // Transaction response codes 1-5 will use the bank response code and message
                if ($responseCode == '1' || $responseCode == '2' || $responseCode == '3' || $responseCode == '4' || $responseCode == '5') {
                    $bankResponseCode = $this->data['TxnResp']['BankResponseCode'];
                    $fullErrorMessage = isset(self::BANK_CODE_MESSAGES[$bankResponseCode]) ? self::BANK_CODE_MESSAGES[$bankResponseCode] : null;
                } else {
                    $fullErrorMessage = isset(self::API_CODE_MESSAGES[$responseCode]) ? self::API_CODE_MESSAGES[$responseCode] : 'Response Unknown';
                }

                // e.g. "Declined (Restricted card)."
                return $responseText . ($fullErrorMessage ? ' (' . $fullErrorMessage . ')' : '') . '.';
            }
        }

        return null;
    }

    /**
     * Get the error code from the response.
     *
     * Transaction responses 1-5 will return the two digit bank response code e.g. "38" (Allowable PIN tries exceeded).
     * All other error responses will return the BPoint error code.
     *
     * @link https://www.bpoint.com.au/developers/v3/index.htm#!#txnResponses
     *
     * Returns null if the request was successful.
     *
     * @return string|null
     */
    public function getCode()
    {
        if (!parent::isSuccessful() && isset($this->data['APIResponse']) && isset($this->data['APIResponse']['ResponseCode'])) {
            return $this->data['APIResponse']['ResponseCode'];
        }

        if (!$this->isSuccessful()) {
            if (isset($this->data['ErrorCode'])) {
                return $this->data['ErrorCode'];
            }
            if (isset($this->data['TxnResp']['ResponseCode'], $this->data['TxnResp']['ResponseText'])) {
                $responseCode = $this->data['TxnResp']['ResponseCode'];

                // Transaction response codes 1-5 will use the bank response code and message
                if ($responseCode == '1' || $responseCode == '2' || $responseCode == '3' || $responseCode == '4' || $responseCode == '5') {
                    $bankResponseCode = $this->data['TxnResp']['BankResponseCode'];
                    return $bankResponseCode;
                }

                return $responseCode;
            }
        }

        return null;
    }

    /**
     * Gateway Reference
     *
     * @return null|string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference()
    {
        if (isset($this->data['TxnResp']['TxnNumber'])) {
            return $this->data['TxnResp']['TxnNumber'];
        }
    }

    /**
     * Get the transaction ID as generated by the merchant website.
     *
     * @return string
     */
    public function getTransactionId()
    {
        return null;
    }
}