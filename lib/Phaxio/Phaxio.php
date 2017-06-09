<?php

namespace Phaxio;

class Phaxio
{
    private $debug = false;
    private $api_key = null;
    private $api_secret = null;
    private $host = "https://api.phaxio.com/v1/";

    const THUMBNAIL_LARGE = 'l';
    const THUMBNAIL_SMALL = 's';
    const PDF = 'p';

    public function __construct($api_key = null, $api_secret = null, $host = null)
    {
        $this->api_key = $api_key ? $api_key : $this->getApiKey();
        $this->api_secret = $api_secret ? $api_secret : $this->getApiSecret();
        if ($host != null) {
            $this->host = $host;
        }
    }

    public function deleteFax($faxId, $filesOnly = false) {
        if (!$faxId) throw new PhaxioException("You must include a fax id. ");

        $params = array('id' => $faxId, 'files_only' => $filesOnly);

        $result = $this->doRequest($this->host . "deleteFax", $params);
        return $result;
    }

    public function download($faxId, $fileType = null, $outfile = null){
        $params = array();
        $params['id'] = $faxId;

        if ($fileType){
            $params['type'] = $fileType;
        }

        $result = $this->doRequest($this->host . "faxFile", $params, false);

        if (array_search($result['contentType'], array('application/pdf', 'image/jpeg')) === false){
            if ($result['status'] == 404){
                throw new PhaxioException($result['body']);
            }
            $json = json_decode($result['body'], true);
            throw new PhaxioException(isset($json['message']) ? $json['message'] : "No data returned from service.");
        }
        else {
            if ($outfile){
                file_put_contents($outfile, $result['body']);
                return true;
            }
            else {
                return $result['body'];
            }
        }
    }

    public function accountStatus() {
        $result = $this->doRequest($this->host . "accountStatus");
        return $result;
    }

    public function faxCancel($faxId) {
        if (!$faxId) throw new PhaxioException("You must include a fax id. ");

        $params = array('id' => $faxId);

        $result = $this->doRequest($this->host . "faxCancel", $params);
        return $result;
    }

    public function faxList($startTimestamp, $endTimestamp, $options = array()){
        if (!$startTimestamp || !$endTimestamp) {
            throw new PhaxioException("You must provide a start and end timestamp. ");
        }

        $params = array('start' => $startTimestamp, 'end' => $endTimestamp);

        $this->paramsCopy(
            array('maxperpage', 'page', 'number', 'status'),
            $options,
            $params
        );

        if (isset($options['tags']) && is_array($options['tags'])){
            foreach($options['tags'] as $name => $value){
                $params["tag[$name]"] = $value;
            }
        }

        $result = $this->doRequest($this->host . "faxList", $params);
        return $result;
    }

    public function faxStatus($faxId)
    {
        if (! $faxId) {
            throw new PhaxioException("You must include a fax id. ");
        }

        $params = array('id' => $faxId);

        $result = $this->doRequest($this->host . "faxStatus", $params);
        return $result;
    }

    public function resendFax($faxId) {
        if (!$faxId) throw new PhaxioException("You must include a fax id. ");

        $params = array('id' => $faxId);

        $result = $this->doRequest($this->host . "resendFax", $params);
        return $result;
    }

    public function sendFax($to, $filenames = array(), $options = array())
    {
        if (! is_array($filenames)) {
            $filenames = array($filenames);
        }

        if (!$to) {
            throw new PhaxioException("You must include a 'to' number. ");
        } elseif (count($filenames) == 0 && !$options['string_data']) {
            throw new PhaxioException("You must include a file to send.");
        }

        $params = array();

        //setup the to parameter
        $to = (is_array($to) ? $to : array($to));
        $i = 0;
        foreach ($to as $toNumber) {
            $params["to[$i]"] = $toNumber;
            $i++;
        }

        $i = 0;
        foreach ($filenames as $filename) {
            if (! file_exists($filename)) {
                throw new PhaxioException("The file '$filename' does not exist.");
            }
            $params["filename[$i]"] = "@$filename";
            $i++;
        }

        $this->paramsCopy(
            array('string_data', 'string_data_type', 'batch', 'batch_delay', 'batch_collision_avoidance', 'callback_url', 'caller_id', 'cancel_timeout','header_text', 'test_fail'),
            $options,
            $params
        );

        if (isset($options['tags']) && $options['tags']){
            foreach($options['tags'] as $name => $value){
                $params["tag[$name]"] = $value;
            }
        }

        $result = $this->doRequest($this->host . "send", $params);

        return $result;
    }

    // PHONE NUMBERS

    public function provisionNumber($areaCode, $callbackUrl = null){
        $params = array('area_code' => $areaCode);

        if ($callbackUrl) $params['callback_url'] = $callbackUrl;
        return $this->doRequest($this->host . "provisionNumber", $params);
    }

    public function releaseNumber($phoneNumber){
        $params = array('number' => $phoneNumber);
        return $this->doRequest($this->host . "releaseNumber", $params);
    }

    public function listNumbers($options = array()){
        $params = array();

        $this->paramsCopy(
            array('area_code', 'number'),
            $options,
            $params
        );

        return $this->doRequest($this->host . "numberList", $params);
    }

    public function getAvailableAreaCodes($options = array()){
        $params = array();

        $this->paramsCopy(
            array('is_toll_free', 'state'),
            $options,
            $params
        );

        return $this->doRequest($this->host . "areaCodes", $params);
    }


    public function getSupportedCountries(){
        return $this->doRequest($this->host . "supportedCountries");
    }



    public function getApiKey()
    {
        return $this->api_key;
    }

    public function getApiSecret()
    {
        return $this->api_secret;
    }

    private function doRequest($address, $params = array(), $wrapInPhaxioOperationResult = true)
    {
        $params['api_key'] = $this->getApiKey();
        $params['api_secret'] = $this->getApiSecret();

        if ($this->debug) {
            echo "Request address: \n\n $address?" . http_build_query($params) . "\n\n";
        }

        $result = $this->curlPost($address, $params, false);

        if ($this->debug) {
            echo "Response: \n\n";
            var_dump($result);
            echo "\n\n";
        }

        if ($wrapInPhaxioOperationResult) {
            $result = json_decode($result['body'], true);

            if (! $result) {
                $opResult = new PhaxioOperationResult(false, "No data received from service.");
            } else {
                $opResult = new PhaxioOperationResult($result['success'], $result['message'], isset($result['data']) ? $result['data'] : null);

                if (isset($result['paging']) && $result['paging']){
                    $opResult->addPagingData($result['paging']);
                }
            }

            return $opResult;
        } else {
            return $result;
        }
    }

    private function curlPost($host, $params = array(), $async = false)
    {
        $handle = curl_init($host);
        curl_setopt($handle, CURLOPT_POST, true);

        if ($async) {
            curl_setopt($handle, CURLOPT_TIMEOUT, 1);
        } else {
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        }

        $this->curlSetoptCustomPostfields($handle, $params);
        $result = curl_exec($handle);

        if ($result === false) {
            throw new PhaxioException('Curl error: ' . curl_error($handle));
        }

        $contentType = curl_getinfo($handle, CURLINFO_CONTENT_TYPE);
        $status= curl_getinfo($handle, CURLINFO_HTTP_CODE);

        return array('status' => $status, 'contentType' => $contentType, 'body' => $result);
    }

    private function paramsCopy($names, $options, &$params)
    {
        foreach ($names as $name) {
            if (isset($options[$name])) {
                $params[$name] = $options[$name];
            }
        }
    }

    private function curlSetoptCustomPostfields($ch, $postfields, $headers = null)
    {
        $algos = hash_algos();
        $hashAlgo = null;

        foreach (array('sha1', 'md5') as $preferred) {
            if (in_array($preferred, $algos)) {
                $hashAlgo = $preferred;
                break;
            }
        }
        if ($hashAlgo === null) {
            list($hashAlgo) = $algos;
        }
        $boundary =
                '----------------------------' .
                substr(hash($hashAlgo, 'cURL-php-multiple-value-same-key-support' . microtime()), 0, 12);

        $body = array();
        $crlf = "\r\n";
        $fields = array();
        foreach ($postfields as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $fields[] = array($key, $v);
                }
            } else {
                $fields[] = array($key, $value);
            }
        }
        foreach ($fields as $field) {
            list($key, $value) = $field;
            if (strpos($value, '@') === 0) {
                preg_match('/^@(.*?)$/', $value, $matches);
                list($dummy, $filename) = $matches;
                $body[] = '--' . $boundary;
                $body[] = 'Content-Disposition: form-data; name="' . $key . '"; filename="' . basename($filename) . '"';
                $body[] = 'Content-Type: application/octet-stream';
                $body[] = '';
                $body[] = file_get_contents($filename);
            } else {
                $body[] = '--' . $boundary;
                $body[] = 'Content-Disposition: form-data; name="' . $key . '"';
                $body[] = '';
                $body[] = $value;
            }
        }
        $body[] = '--' . $boundary . '--';
        $body[] = '';
        $contentType = 'multipart/form-data; boundary=' . $boundary;
        $content = join($crlf, $body);
        $contentLength = strlen($content);

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Length: ' . $contentLength,
                'Expect: 100-continue',
                'Content-Type: ' . $contentType,
            )
        );

        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
    }
}
