<?php

namespace ezeasorekene\CredoPayments;

use Curl\Curl;

class CredoPay
{

    protected $publicKey;

    protected $secretKey;

    protected $baseUrl;

    protected $response;

    public $debug = false;

    public $responseType = 'json';

    public function __construct($apiKeys = [], $mode = 'LIVE')
    {
        $this->publicKey = isset($apiKeys['publicKey']) ? $apiKeys['publicKey'] : null;
        $this->secretKey = isset($apiKeys['secretKey']) ? $apiKeys['secretKey'] : null;
        $this->baseUrl = $mode == 'LIVE' ? 'https://api.credocentral.com' : 'https://api.credodemo.com';
    }

    public function initialize(array $data = [])
    {
        $initialize = new Curl();

        $initialize->setHeader('Authorization', $this->publicKey);
        $initialize->setHeader('Content-Type', 'application/json');

        $initialize->post($this->baseUrl . "/transaction/initialize", json_encode($data));

        $responseObject = json_decode($initialize->response);
        $responseArray = json_decode($initialize->response, true);

        if ($responseObject->status == 200) {
            if ($this->responseType == 'array') {
                $data = [
                    'status' => $responseArray['status'],
                    'message' => $responseArray['message'],
                    'data' => [
                        'authorizationUrl' => $responseArray['data']['authorizationUrl'],
                        'reference' => $responseArray['data']['reference'],
                        'credoReference' => $responseArray['data']['credoReference'],
                        'account' => $responseArray['data']['account'] ?? null,
                    ]
                ];
            } else {
                $data = json_encode([
                    'status' => $responseObject->status,
                    'message' => $responseObject->message,
                    'data' => [
                        'authorizationUrl' => $responseObject->data->authorizationUrl,
                        'reference' => $responseObject->data->reference,
                        'credoReference' => $responseObject->data->credoReference,
                        'account' => $responseObject->data->account ?? null,
                    ]
                ]);
            }
        } else {
            if ($this->responseType == 'array') {
                $data = [
                    'status' => $responseArray['status'],
                    'message' => $responseArray['message'],
                    'error' => isset($responseArray['error']) ? $responseArray['error'] : '',
                ];
            } else {
                $data = json_encode([
                    'status' => $responseObject->status,
                    'message' => $responseObject->message,
                    'error' => isset($responseObject->error) ? $responseObject->error : '',
                ]);
            }
        }

        return $data;
    }

    public function verify(string $reference)
    {
        $verify = new Curl();

        $verify->setHeader('Authorization', $this->secretKey);
        $verify->setHeader('Content-Type', 'application/json');

        $verify->get($this->baseUrl . "/transaction/{$reference}/verify");

        $responseObject = json_decode($verify->response);
        $responseArray = json_decode($verify->response, true);

        if ($responseObject->status == 200) {
            if ($this->responseType == 'array') {
                $data = [
                    'status' => $responseArray['status'],
                    'message' => $responseArray['message'],
                    'data' => [
                        'email' => $responseArray['data']['customerId'],
                        'transactionDate' => isset($responseArray['data']['transactionDate']) ? $responseArray['data']['transactionDate'] : '',
                        'reference' => $responseArray['data']['businessRef'],
                        'credoReference' => $responseArray['data']['transRef'],
                        'currencyCode' => $responseArray['data']['currencyCode'],
                        'debitedAmount' => $responseArray['data']['debitedAmount'],
                        'transAmount' => $responseArray['data']['transAmount'],
                        'transFeeAmount' => $responseArray['data']['transFeeAmount'],
                        'metadata' => $responseArray['data']['metadata'],
                        'status' => $responseArray['data']['status'],
                    ]
                ];
            } else {
                $data = json_encode([
                    'status' => $responseObject->status,
                    'message' => $responseObject->message,
                    'data' => [
                        'email' => $responseObject->data->customerId,
                        'transactionDate' => isset($responseObject->data->transactionDate) ? $responseObject->data->transactionDate : '',
                        'reference' => $responseObject->data->businessRef,
                        'credoReference' => $responseObject->data->transRef,
                        'currencyCode' => $responseObject->data->currencyCode,
                        'debitedAmount' => $responseObject->data->debitedAmount,
                        'transAmount' => $responseObject->data->transAmount,
                        'transFeeAmount' => $responseObject->data->transFeeAmount,
                        'metadata' => $responseObject->data->metadata,
                        'status' => $responseObject->data->status,
                    ]
                ]);
            }
        } else {
            if ($this->responseType == 'array') {
                $data = [
                    'status' => $responseArray['status'],
                    'message' => $responseArray['message'],
                    'error' => isset($responseArray['error']) ? $responseArray['error'] : '',
                ];
            } else {
                $data = json_encode([
                    'status' => $responseObject->status,
                    'message' => $responseObject->message,
                    'error' => isset($responseObject->error) ? $responseObject->error : '',
                ]);
            }
        }

        return $data;
    }

}