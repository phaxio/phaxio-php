<?php

namespace Phaxio;

class Fax
{
    private $id;
    private $phaxio;

    public function __construct($phaxio)
    {
        $this->phaxio = $phaxio;
    }

    public static function create($phaxio, $params) {
        $fax = new self($phaxio);
        return $fax->_create($params);
    }

    public function _create($params) {
        if ($this->id) throw new PhaxioException("Fax #$id already created");

        $result = $this->phaxio->doRequest("faxes", $params);
        $this->id = $result->getData()['id'];

        return $this;
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
}
