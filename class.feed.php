<?php


class Twitter_feed {

	private $api_key, $api_secret;

	public function __construct() {
		$this->api_key = "fLGpbtLVisue7Qnv2JXRZzDUJ";
		$this->api_secret = "rRD3PcYkSAgpbEsIAOjP5jvaZUGZEKQgIdairv8nCydWgCxq5x";
	}

	private function get_token() {
		$bearer_token_cred = base64_encode(urlencode($this->api_key).":".urlencode($this->api_secret));
		$custom_headers = array(
			"Authorization: Basic ".$bearer_token_cred,
			"Content-Type: application/x-www-form-urlencoded;charset=UTF-8"
		);
		$curl_handle = curl_init("https://api.twitter.com/oauth2/token");
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $custom_headers);
		curl_setopt($curl_handle, CURLOPT_POST, TRUE);
		curl_setopt($curl_handle,CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		$token_response = curl_exec($curl_handle);
		$http_status_code = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
		curl_close($curl_handle);
		if($http_status_code != 200) {
			throw new Exception("Error getting API token", 1);
		}
		$token_data = json_decode($token_response, TRUE);
		return $token_data['access_token'];
	}

	private function get_user_tweets() {
		$custom_headers = array(
			"Authorization: Bearer ".$this->get_token(),
			"Content-Type: application/x-www-form-urlencoded;charset=UTF-8"
		);
		$curl_handle = curl_init("https://api.twitter.com/1.1/statuses/user_timeline.json?count=10&screen_name=salesforce");
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $custom_headers);
		$feed_response = curl_exec($curl_handle);
		$http_status_code = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
		curl_close($curl_handle);
		if($http_status_code != 200) {
			throw new Exception("Error getting API Feed", 1);
		}
		return json_decode($feed_response, TRUE);
	}

	public function get_feed() {

		$feed_data = $this->get_user_tweets();

		$return = array();

		foreach($feed_data as $tweet) {
			$return[] = array(
				'tweet' => $tweet['text'],
				'user_name' => $tweet['user']['name'],
				'screen_name' => $tweet['user']['screen_name'],
				'profile_image' => $tweet['user']['profile_image_url'],
				'retweet_count' => $tweet['retweet_count']
			);
		}

		return $return;
	}
}

?>