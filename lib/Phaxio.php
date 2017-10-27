<?php

class Phaxio
{
    private $debug = true;
    private $api_key = null;
    private $api_secret = null;
    private $host = "https://api.phaxio.com/v2/";

    public function __construct($api_key = null, $api_secret = null, $host = null)
    {
        $this->api_key = $api_key ? $api_key : $this->getApiKey();
        $this->api_secret = $api_secret ? $api_secret : $this->getApiSecret();
        if ($host != null) {
            $this->host = $host;
        }
    }
    
    public function faxes()
    {
        return new Phaxio\Faxes($this);
    }

    public function getApiKey()
    {
        return $this->api_key;
    }

    public function getApiSecret()
    {
        return $this->api_secret;
    }

    public function doRequest($method, $path, $params = array(), $wrapInPhaxioOperationResult = true)
    {
        $address = $this->host . $path;

        if ($this->debug) {
            echo "Request address: \n\n $address?" . http_build_query($params) . "\n\n";
        }

        $result = $this->curlRequest($method, $address, $params, false);

        if ($this->debug) {
            echo "Response: \n\n";
            var_dump($result);
            echo "\n\n";
        }

        if ($wrapInPhaxioOperationResult) {
            $result = json_decode($result['body'], true);

            if (! $result) {
                $opResult = new Phaxio\OperationResult(false, "No data received from service.");
            } else {
                $opResult = new Phaxio\OperationResult($result['success'], $result['message'], isset($result['data']) ? $result['data'] : null);

                if (isset($result['paging']) && $result['paging']){
                    $opResult->addPagingData($result['paging']);
                }
            }

            return $opResult;
        } else {
            return $result;
        }
    }

    private function curlRequest($method, $address, $params = array(), $async = false)
    {
        $handle = curl_init($address);

        curl_setopt($handle, CURLOPT_CUSTOMREQUEST, $method);

        # Authentication
        if ($this->debug) {
            echo "Authentication: " . $this->getApiKey() . ':' . $this->getApiSecret() . "\n\n";
        }
        curl_setopt($handle, CURLOPT_USERPWD, $this->getApiKey() . ':' . $this->getApiSecret());

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

        curl_close($handle);

        return array('status' => $status, 'contentType' => $contentType, 'body' => $result);
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
