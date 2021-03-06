<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MigEvents\Http\Client;

use MigEvents\Http\Client\ClientCurl;
use MigEvents\Http\RequestInterface;
use MigEvents\Http\Request;
use MigEvents\Http\ResponseInterface;
use MigEvents\Http\Headers;
use MigEvents\Http\Client\ClientInterface;
use MigEvents\Http\Parameters;
use MigEvents\Http\Adapter\CurlAdapter;
use MigEvents\Http\Response;
use MigEvents\Http\Exception\EmptyResponseException;
use MigEvents\Http\Exception\RequestException;
use MigEvents\Http\OneTimePassword;
use MigEvents\Tripledes;

class GraphGlobalClient extends Client implements ClientInterface {

    protected $responseResult;
    protected $timeSlice;

    public function __construct() {
        $this->setDefaultBaseDomain("dllglobal.net");
        $this->setDefaultLastLevelDomain("graph");
    }

    public function getEndPoint() {
        return __CLASS__;
    }
    
    public function getTimeSlice() {
        if ($this->timeSlice == null) {
            $this->timeSlice = (int) (time() / 30);
        }
        return $this->timeSlice;
    }

    public function setTimeSlice($timeSlice) {
        $this->timeSlice = $timeSlice;
    }

    public function sendRequest(RequestInterface $request) {
        $header = new Headers();

        $otpCode = OneTimePassword::getCode($this->getSecret(), $this->getTimeSlice());
        $params = $request->getQueryParams()->getArrayCopy();

        $original = implode("", $params) . $otpCode;
        $token = md5($original . $this->getSecret());

        $header['otp'] = $otpCode;
        $header["app"] = $this->getApp();
        $header["token"] = $token;

        $this->setDefaultRequestHeaders($header);

        $this->responseResult = parent::sendRequest($request);
        //parse result
        return $this->responseResult;
    }

    public function prepareResponse() {
        //var_dump($this->responseResult);
        if ($this->responseResult != NULL) {
            $result = $this->responseResult->getBody();
            $resultDecrypt = Tripledes::decrypt($result, $this->getSecret());

            $endResult = null;
            if (is_array($resultDecrypt))
                $endResult = $resultDecrypt;
            else {
                $endResult = json_decode($result, true);
                if ($endResult == null) {
                    return $this->responseResult->getContent();
                }
            }
            return $endResult;
        } else {
            return null;
        }
    }

}
