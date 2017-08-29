# RatePAY GmbH - PHP SDK
============================================

|Module | RatePAY PHP SDK
|------|----------
|Author | Aarne Welschlau
|Version | `0.9.3.1`
|Link | http://www.ratepay.com
|Mail | integration@ratepay.com

### Installation
```bash
composer install
```

### How to start

Find the basic usage of the different API calls and operations of the API library explained in the example files. The *example.php* explains the basic usage of the library.
###### To run the examples, insert your (received by RatePAY Customer Integration Team) credentials into 'ratepay_credentials.php'.
Following table describes the basic (transaction-driven) operations in chronological order.

|#|File|Operation/+*Subtype*|Information|Description|
|-|-|-|-|-|
|1|example.php|Payment Init|Head|Shows the basic functionality of the RatePAY library|
|2|example.paymentRequest.*.php|Payment Request|Head, customer, shopping basket, payment|Call for authorization. Different examples for all payment methods|
|3|example.paymentChange.cancellation.php|Payment Change - *Cancellation*|Head, shopping basket|Informs RatePAY of a cancelled basket or article|
|4|example.confirmationDeliver.php|Confirmation Deliver|Head, shopping basket|Informs RatePAY of a shipped basket or article|
|5|example.paymentChange.return.php|Payment Change - *Return*|Head, shopping basket|Informs RatePAY of a returned basket or article|
|6|example.paymentChange.changeOrder.php|Payment Change - *Change Order*|Head, shopping basket|Informs RatePAY of a totally changed shopping basket|
|7|example.paymentChange.credit.php|Payment Change - *Credit*|Head, shopping basket|Informs RatePAY of a subsequent refund or fee|

Following table describes optional and transaction-independent operations.

|File|Operation/+*Subtype*|Description|
|-|-|-|
|example.PaymentInit.php|Payment Init|**Optional** call before *Payment Request*. Opens new transaction. Returns RatePAY transaction id. If *Payment Request* is called without transaction id *Payment Init* is called automatically by library|
|example.PaymentQuery.php|Payment Query|Identical to *Payment Request*. Returns all admitted RatePAY payment methods for this transaction|
|example.PaymentConfirm.php|Payment Confirm|**Optional** call which is called between *Payment Request* and *Confirmation Deliver* to confirm authorization and transmit additional order information|
|example.configurationRequest.php|Configuration Request|Returns the merchant installment configuration from RatePAY|
|example.calculationRequest.php|Calculation Request - *calculation-by-time*/*calculation-by-rate*|Calculates an installment plan. Subtype has to be *calculation-by-rate* which calculates by a rate defined by the customer or *calculation-by-time* which calculates by a duration chosen by the customer|
|example.profileRequest.php|Profile Request|Returns the merchant configuration from RatePAY, includes the installment configuration|
