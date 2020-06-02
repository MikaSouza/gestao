<?php
class SendSMS
{
	private $account    = 'marpa';
	private $password   = 'WRUi9v8Yr8';
	private $endpoint   = 'https://api-rest.zenvia.com/services/send-sms';
	private $recipients = array();
	private $message;
	private $curl;

	public function __construct()
	{
		$this->curl = curl_init($this->endpoint);
	}

	public function setRecipient($recipient)
	{
		try {
			//Replace non numeric caracters
			$recipient = preg_replace("/[^0-9]/", "", $recipient);
			//Verify phone lenght
			if (strlen($recipient) < 10) {
				throw new Exception('Please verify phone number');
			}
			//Concat with brazilian coutry code.
			$this->recipients[] = '55'.$recipient;
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function setMessage($message)
	{
		$this->message = $message;
	}

	public function send()
	{
		try {
			//Verify message lenght
			if (empty($this->message)) {
				throw new Exception('The message is empty!');
			}
			//Verify recipients lenght
			if (empty($this->recipients)) {
				throw new Exception('The list of recipients is empty!');
			}
			//Push Zenvia response into a response array
			$response = array();
			foreach ($this->recipients as $recipient) {
				$response[] = $this->sendFinal($recipient);
			}

			return $response;
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	private function sendFinal($phoneNumber)
	{
		try {
			$data = [
				'sendSmsRequest' => [
					'from'           => 'Marpa',
					'to'             => $phoneNumber,
					'msg'            => $this->message,
					'callbackOption' => 'NONE',
				],
			];

			curl_setopt_array($this->curl, [
				CURLOPT_POSTFIELDS     => json_encode($data),
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => "",
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_POST  		   => true,
				CURLOPT_USERPWD        => $this->account.':'.$this->password,
				CURLOPT_HTTPHEADER     => [
					'Accept: application/json',
					'Content-Type: application/json',
				],
			]);

			$response = curl_exec($this->curl);
			$error    = curl_error($this->curl);

			if ($error) {
				throw new Exception("cURL Error #: ".$error);
			}

			$response = json_decode($response, true);
			return $response['sendSmsResponse'];
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}
}