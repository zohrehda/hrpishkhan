# Swagger\Client\SMSApi

All URIs are relative to *http://morpheus-private-shared-testing.apps.private.teh-1.snappcloud.io*

Method | HTTP request | Description
------------- | ------------- | -------------
[**sendSMS**](SMSApi.md#sendsms) | **POST** /api/sms | Sends an SMS to passengers and drivers.

# **sendSMS**
> sendSMS($body)

Sends an SMS to passengers and drivers.

This API asynchronously sends an SMS, so getting success in this API doesn't mean a successful delivery of given SMS.  **Warning:** This endpoint has some rate limitations. Check the values in the technical docs.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');
    // Configure HTTP bearer authorization: BearerAuth
    $config = Swagger\Client\Configuration::getDefaultConfiguration()
    ->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new Swagger\Client\Api\SMSApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$body = new \Swagger\Client\Model\Body(); // \Swagger\Client\Model\Body | 

try {
    $apiInstance->sendSMS($body);
} catch (Exception $e) {
    echo 'Exception when calling SMSApi->sendSMS: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **body** | [**\Swagger\Client\Model\Body**](../Model/Body.md)|  | [optional]

### Return type

void (empty response body)

### Authorization

[BearerAuth](../../README.md#BearerAuth)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

