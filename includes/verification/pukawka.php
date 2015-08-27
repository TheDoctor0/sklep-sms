<?php

$heart->register_payment_module("pukawka", "PaymentModule_Pukawka");

class PaymentModule_Pukawka extends PaymentModule implements IPayment_Sms
{

	const SERVICE_ID = "pukawka";

	/** @var  string */
	private $api;

	/** @var  string */
	private $sms_code;

	private $stawki = array();

	function __construct()
	{
		parent::__construct();

		$this->api = $this->data['api'];
		$this->sms_code = $this->data['sms_text'];

		$this->stawki = json_decode(curl_get_contents(
			'https://admin.pukawka.pl/api/' .
			'?keyapi=' . urlencode($this->api) .
			'&type=sms_table'
		), true);
	}

	public function verify_sms($return_code, $number)
	{
		$get = curl_get_contents(
			'https://admin.pukawka.pl/api/' .
			'?keyapi=' . urlencode($this->api) .
			'&type=sms' .
			'&code=' . urlencode($return_code)
		);

		if (!$get) {
			return IPayment_Sms::NO_CONNECTION;
		}

		$get = json_decode($get);

		if (is_object($get)) {
			if ($get->error) {
				return array(
					'status' => IPayment_Sms::UNKNOWN,
					'text' => $get->error
				);
			}

			if ($get->status == 'ok') {
				$kwota = str_replace(',', '.', $get->kwota);
				foreach ($this->stawki as $s) {
					if (str_replace(',', '.', $s->wartosc) != $kwota)
						continue;

					if ($s->numer == $number) {
						return IPayment_Sms::OK;
					}

					return array(
						'status' => IPayment_Sms::BAD_NUMBER,
						'tariff' => $this->getTariffByNumber($s->numer)->getId()
					);
				}

				return IPayment_Sms::ERROR;
			}

			return IPayment_Sms::BAD_CODE;
		}

		return IPayment_Sms::ERROR;
	}

	public function getSmsCode()
	{
		return $this->sms_code;
	}

}