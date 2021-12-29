<?php

namespace PTK\NapThe\API;

class Vippay_API {
    
    private $merchant_id;
    private $pin;
    private $seri;
    private $card_type;
    private $api_user;
    private $api_password;
    private $code;
    private $msg;
    private $info_card;
    private $transaction_id;
    private $note;

	/**
	 * @return the $merchant_id
	 */
	public function getMerchantId() {
		return $this->merchant_id;
	}

	/**
	 * @return the $pin
	 */
	public function getPin() {
		return $this->pin;
	}

	/**
	 * @return the $seri
	 */
	public function getSeri() {
		return $this->seri;
	}

	/**
	 * @return the $card_type
	 */
	public function getCardType() {
		return $this->card_type;
	}

	/**
	 * @return the $api_user
	 */
	public function getApiUser() {
		return $this->api_user;
	}

	/**
	 * @return the $api_password
	 */
	public function getApiPassword() {
		return $this->api_password;
	}

	/**
	 * @return the $code
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * @return the $msg
	 */
	public function getMsg() {
		return $this->msg;
	}

	/**
	 * @return the $info_card
	 */
	public function getInfoCard() {
		return $this->info_card;
	}

	/**
	 * @return the $transaction_id
	 */
	public function getTransactionId() {
		return $this->transaction_id;
	}

	/**
	 * @return the $note
	 */
	public function getNote() {
		return $this->note;
	}

	/**
	 * @param field_type $merchant_id
	 */
	public function setMerchantId($merchant_id) {
		$this->merchant_id = $merchant_id;
	}

	/**
	 * @param field_type $pin
	 */
	public function setPin($pin) {
		$this->pin = $pin;
	}

	/**
	 * @param field_type $seri
	 */
	public function setSeri($seri) {
		$this->seri = $seri;
	}

	/**
	 * @param field_type $card_type
	 */
	public function setCardType($card_type) {
		$this->card_type = $card_type;
	}

	/**
	 * @param field_type $api_user
	 */
	public function setApiUser($api_user) {
		$this->api_user = $api_user;
	}

	/**
	 * @param field_type $api_password
	 */
	public function setApiPassword($api_password) {
		$this->api_password = $api_password;
	}

	/**
	 * @param field_type $code
	 */
	public function setCode($code) {
		$this->code = $code;
	}

	/**
	 * @param field_type $msg
	 */
	public function setMsg($msg) {
		$this->msg = $msg;
	}

	/**
	 * @param field_type $info_card
	 */
	public function setInfoCard($info_card) {
		$this->info_card = $info_card;
	}

	/**
	 * @param field_type $transaction_id
	 */
	public function setTransactionId($transaction_id) {
		$this->transaction_id = $transaction_id;
	}

	/**
	 * @param field_type $note
	 */
	public function setNote($note) {
		$this->note = $note;
	}

	public function cardCharging() {
        $fields = array(
            'merchant_id' => $this->merchant_id,
            'pin' => $this->pin,
            'seri' => $this->seri,
            'card_type' => $this->card_type,
			'note' => $this->note
        );
        
        $ch = curl_init("https://vippay.vn/api/api/card");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERPWD, $this->api_user . ":" . $this->api_password);
        $result = curl_exec($ch);
        $result = json_decode($result);
        $this->code = $result->code;
        $this->msg = $result->msg;
        $this->info_card = $result->info_card;
        $this->transaction_id = $result->transaction_id;
    }
}

