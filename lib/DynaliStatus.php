<?php

namespace Dynali;

use InvalidArgumentException;
use DateTime;

/**
 * Entity which represents information about Dynali hostname's status.
 */
class DynaliStatus
{
    /**
     * Numerical representation of the status as returned by Dynali.
     *
     * @param int
     */
    protected $status;

    /**
     * IP address status assigned to a hostname.
     *
     * @param string
     */
    protected $ip;

    /**
     * Textual representation of the status as returned by Dynali.
     *
     * @param string
     */
    protected $statusMessage;

    /**
     * Expiry date (may be in the future or in the past).
     *
     * @param DateTime
     */
    protected $expiryDate;

    /**
     * Hostname.
     *
     * @param string
     */
    protected $hostname;

    /**
     * Date of the hostname's creation.
     *
     * @param DateTime
     */
    protected $creationDate;

    /**
     * Date of the last update.
     *
     * @param DateTime
     */
    protected $lastUpdateDate;

    /**
     * Date when this status check was performed.
     *
     * @param DateTime
     */
    protected $statusCheckDate;

    /**
     * Gets the IP address assigned to the hostname.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Gets the numerical representation of the status as returned by Dynali.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Gets the textual representation of the status as returned by Dynali.
     *
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->statusMessage;
    }

    /**
     * Gets the hostname's expiry date (may be in the future or in the past).
     *
     * @return DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Gets the hostname.
     *
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * Gets the date of the hostname's creation.
     *
     * @return DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Gets the last update date.
     *
     * @return DateTime
     */
    public function getLastUpdateDate()
    {
        return $this->lastUpdateDate;
    }

    /**
     * Informs if hostname is active.
     *
     * @return boolean
     */
    public function isActive()
    {
        return ($this->status === 0);
    }

    /**
     * Informs if hostname is expired.
     *
     * @return boolean
     */
    public function isExpired()
    {
        return ($this->status === 2);
    }

    /**
     * Informs if hostname is banned.
     *
     * @return boolean
     */
    public function isBanned()
    {
        return ($this->status === 9);
    }

    /**
     * Gets the date when this status check was performed.
     *
     * @return DateTime
     */
    public function getStatusCheckDate()
    {
        return $this->statusCheckDate;
    }

    /**
     * Creates new instance of the hostname's status entity.
     *
     * @param string $hostname Hostname
     * @param string $ip IP address assigned to the hostname
     * @param int $status Numerical status
     * @param string $statusMessage Textual status
     * @param DateTime $expiryDate Expiry date
     * @param DateTime $creationDate Creation date
     * @param DateTime $lastUpdateDate Last update date
     * @param DateTime $statuCheckDate Status check date
     * @return $this
     */
    public function __construct($hostname, $ip, $status, $statusMessage, DateTime $expiryDate, DateTime $creationDate, DateTime $lastUpdateDate, DateTime $statusCheckDate)
    {
        $this->hostname = $hostname;
        $this->ip = $ip;
        $this->status = $status;
        $this->statusMessage = $statusMessage;
        $this->expiryDate = $expiryDate;
        $this->creationDate = $creationDate;
        $this->lastUpdateDate = $lastUpdateDate;
        $this->statusCheckDate = $statusCheckDate;
    }
}
