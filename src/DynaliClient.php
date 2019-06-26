<?php

namespace Dynali;

use InvalidArgumentException;

class DynaliClient
{
    static private $endpoint = 'https://api.dynali.net/nice/';
    const AUTODETECT_IP = 1;

    /**
     * Send the actual POST request without using PHP's curl functions.
     *
     * @param string $action Dynali's function.
     * @param array $payload Additionaly data: like ip or authorization.
     * @return array Parsed JSON resposne
     * @throws Exception If the request fails or JSON cannot be parsed.
     */
    static protected function execute($action, $payload = []) {
        $postVars = ['action' => $action];
        if (!empty($payload)) {
            $postVars['payload'] = $payload;
        }

        $options = array(
            'http' =>
                array(
                    'method'  => 'POST', //We are using the POST HTTP method.
                    'header'  => 'Content-type: application/json',
                    'content' => json_encode($postVars) //Our URL-encoded query string.
                )
        );
        $streamContext  = stream_context_create($options);
        $result = file_get_contents(static::$endpoint, false, $streamContext);
        if($result === false){
            $error = error_get_last();
            throw new Exception('Request failed: ' . $error['message']);
        }
        return json_decode($result, true);
    }

    static public function myIp() {
        return static::execute('myip');
    }

    static public function update($hostname, $username, $password, $ip = self::AUTODETECT_IP) {
        if (!is_string($hostname) || empty($hostname)) {
            throw new InvalidArgumentException('Invalid or missing hostname.');
        }

        if (!is_string($username) || empty($username)) {
            throw new InvalidArgumentException('Invalid or missing username.');
        }

        if (!is_string($password) || empty($password)) {
            throw new InvalidArgumentException('Invalid or missing password.');
        }

        if ($ip === self::AUTODETECT_IP) {
            $response = static::myIp();
            if ($response['status'] !== 'success') {
                throw new DynaliException($response['code'], $response['message']);
            }

            $ip = $response['data']['ip'];
        }

        if (($valid = filter_var($ip, FILTER_VALIDATE_IP)) === false) {
            throw new InvalidArgumentException('Invalid IP. Provided: `' . (string)$ip . '`.');
        }

        $response = static::execute('update', [ 'username' => $username, 'password' => md5($password), 'myip' => $ip, 'hostname' => $hostname ]);

        if ($response['status'] !== 'success') { //failure
            throw new DynaliException($response['code'], $response['message']);
        }
        //success
        return $response;
    }

    /**
     * Receives hostname's status.
     */
    static public function status($hostname, $username, $password) {
        if (!is_string($hostname) || empty($hostname)) {
            throw new InvalidArgumentException('Invalid or missing hostname.');
        }

        if (!is_string($username) || empty($username)) {
            throw new InvalidArgumentException('Invalid or missing username.');
        }

        if (!is_string($password) || empty($password)) {
            throw new InvalidArgumentException('Invalid or missing password.');
        }

        $response = static::execute('status', [ 'username' => $username, 'password' => md5($password), 'hostname' => $hostname ]);

        if ($response['status'] !== 'success') { //failure
            throw new DynaliException($response['code'], $response['message']);
        }
        //success
        $data = $response['data'];
        return new DynaliStatus($hostname, $data['ip'], intval($data['status']), $data['status_message'], new DateTime($data['expiry_date']), new DateTime($data['created']), new DateTime($data['last_update']), new DateTime());
    }
}
