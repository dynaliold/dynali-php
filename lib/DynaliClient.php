<?php

namespace Dynali;

use DateTime;
use InvalidArgumentException;

class DynaliClient
{
    static private $endpoint = 'https://api.dynali.net/nice/';
    const AUTODETECT_IP = true;

    /**
     * Send the actual POST request without using PHP's curl functions.
     *
     * @param string $action Dynali's function.
     * @param array $payload Additionaly data: like ip or authorization.
     * @return array Parsed JSON resposne
     * @throws Exception If the request fails or JSON cannot be parsed.
     */
    static protected function execute($action, $payload = [])
    {
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
        if ($result === false) {
            $error = error_get_last();
            throw new RuntimeException('Request failed: ' . $error['message']);
        }
        return json_decode($result, true);
    }

    /**
     * Returns client's IP as detected by Dynali.
     *
     * @static
     * @throws DynaliException
     * @return string
     */
    static public function myIp()
    {
        $dynaliIpDataRaw = static::execute('myip');
        if (!isset($dynaliIpDataRaw['status']) || $dynaliIpDataRaw['status'] !== 'success') {
            if (!isset($dynaliIpDataRaw['message'])) {
                throw new DynaliException(-804, 'Invalid output data. Missing message.');
            }

            throw new DynaliException($dynaliIpDataRaw['code'], $dynaliIpDataRaw['message']);
        }

        if (!isset($dynaliIpDataRaw['data']) || !isset($dynaliIpDataRaw['data']['ip'])) {
            throw new DynaliException(-805, 'Invalid output data. Missing payload.');
        }

        return $dynaliIpDataRaw['data']['ip'];
    }

    /**
     * Updates client's hostname with client's ip. Username and password are
     * required. IP can be provided or automatically detected.
     *
     * Returns `true` on success, throws exception on any failure
     *
     * @static
     * @param string $hostname
     * @param string $username
     * @param string $password
     * @param string|boolean $ip if set to true (self::AUTODETECT_IP) will attempt
     * to detect client's IP.
     * @throws InvalidArgumentException
     * @throws DynaliException
     * @return boolean
     */
    static public function update($hostname, $username, $password, $ip = self::AUTODETECT_IP)
    {
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
            $ip = static::myIp();
        }

        if ((filter_var($ip, FILTER_VALIDATE_IP)) === false) {
            throw new InvalidArgumentException('Invalid IP. Provided: `' . (string) $ip . '`.');
        }

        $dynaliDataRaw = static::execute('update', ['username' => $username, 'password' => md5($password), 'myip' => $ip, 'hostname' => $hostname]);

        if (!isset($dynaliDataRaw['status']) || $dynaliDataRaw['status'] !== 'success') {
            if (!isset($dynaliDataRaw['message'])) {
                throw new DynaliException(-804, 'Invalid output data. Missing message.');
            }

            throw new DynaliException($dynaliDataRaw['code'], $dynaliDataRaw['message']);
        }

        if ($dynaliDataRaw['status'] !== 'success') { //failure
            throw new DynaliException($dynaliDataRaw['code'], $dynaliDataRaw['message']);
        }

        //success
        return true;
    }

    /**
     * Receives hostname's status.
     *
     * @static
     * @param string $hostname
     * @param string $username
     * @param string $password
     * @throws InvalidArgumentException
     * @throws DynaliException
     * @return DynaliStatus
     */
    static public function status($hostname, $username, $password)
    {
        if (!is_string($hostname) || empty($hostname)) {
            throw new InvalidArgumentException('Invalid or missing hostname.');
        }

        if (!is_string($username) || empty($username)) {
            throw new InvalidArgumentException('Invalid or missing username.');
        }

        if (!is_string($password) || empty($password)) {
            throw new InvalidArgumentException('Invalid or missing password.');
        }

        $dynaliDataRaw = static::execute('status', ['username' => $username, 'password' => md5($password), 'hostname' => $hostname]);

        if (!isset($dynaliDataRaw['status']) || $dynaliDataRaw['status'] !== 'success') {
            if (!isset($dynaliDataRaw['message'])) {
                throw new DynaliException(-804, 'Invalid output data. Missing message.');
            }

            throw new DynaliException($dynaliDataRaw['code'], $dynaliDataRaw['message']);
        }

        if ($dynaliDataRaw['status'] !== 'success') { //failure
            throw new DynaliException($dynaliDataRaw['code'], $dynaliDataRaw['message']);
        }

        if (!isset($dynaliDataRaw['data']) || !isset($dynaliDataRaw['data']['ip'])) {
            throw new DynaliException(-805, 'Invalid output data. Missing payload.');
        }

        $requiredFields = ['ip', 'status', 'status_message', 'expiry_date', 'created', 'last_update'];
        $payloadFields = array_keys($dynaliDataRaw['data']);
        $missingFields = array_diff($requiredFields, $payloadFields);

        if (!empty($missingFields)) {
            throw new DynaliException(-806, 'Invalid output data. Missing fields: ' . join(',', $missingFields));
        }

        $dynaliPayload = $dynaliDataRaw['data'];

        return new DynaliStatus($hostname, $dynaliPayload['ip'], intval($dynaliPayload['status']), $dynaliPayload['status_message'], new DateTime($dynaliPayload['expiry_date']), new DateTime($dynaliPayload['created']), new DateTime($dynaliPayload['last_update']), new DateTime());
    }
}
