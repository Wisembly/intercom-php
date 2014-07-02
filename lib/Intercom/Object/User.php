<?php

namespace Intercom\Object;

use \Datetime;

use Intercom\Request\FormatableInterface,
    Intercom\Exception\UserException;

/**
 * This class represents an Intercom User Object
 *
 * @link Api : http://doc.intercom.io/api/#users
 */
class User implements FormatableInterface
{
    private $intercomId;
    private $userId;
    private $email;
    private $name;
    private $createdAt;
    private $lastSeenIp;
    private $customAttributes;
    private $lastSeenUserAgent;
    private $lastRequestAt;
    private $unsubscribedFromEmails;
    private $locationData;
    private $sessionCount;
    private $socialProfiles;
    private $lastImpressionAt;
    private $avatarUrl;
    private $companyIds;
    private $vipTimestamp;

    /**
     * @param integer $userId
     * @param string  $email
     */
    public function __construct($userId = null, $email = null)
    {
        if (null === $userId && null === $email) {
            throw new UserException("user_id or email must be defined.");
        }

        $this->userId = $userId;
        $this->email = mb_strtolower($email, mb_detect_encoding($email));
        $this->createdAt = time();
        $this->customAttributes = [];
    }

    /**
     * {@inheritdoc}
     */
    public function format()
    {
        return [
            'user_id'                  => $this->userId,
            'email'                    => $this->email,
            'name'                     => $this->name,
            'created_at'               => $this->createdAt,
            'last_seen_ip'             => $this->lastSeenIp,
            'custom_attributes'        => $this->customAttributes,
            'last_seen_user_agent'     => $this->lastSeenUserAgent,
            'last_request_at'          => $this->lastRequestAt,
            'unsubscribed_from_emails' => $this->unsubscribedFromEmails,

            // @todo Disable the following field temporarily

            // 'location_data'            => $this->locationData,
            // 'session_count'            => $this->sessionCount,
            // 'social_profiles'          => $this->socialProfiles,
            // 'last_impression_at'       => $this->lastImpressionAt,
        ];
    }

    /**
     * Set IntercomId
     *
     * @param  string $intercomId
     *
     * @return $this
     */
    public function setIntercomId($intercomId)
    {
        $this->intercomId = (int) $intercomId;

        return $this;
    }

    /**
     * Get IntercomId
     *
     * @return string
     */
    public function getIntercomId()
    {
        return $this->intercomId;
    }

    /**
     * Set UserId
     *
     * @param  integer $userId
     *
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = (int) $userId;

        return $this;
    }

    /**
     * Get UserId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set Email
     *
     * @param  string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get Email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set Name
     *
     * @param  string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set CreatedAt
     *
     * @param  string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get CreatedAt
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set LastSeenIp
     *
     * @param  string $lastSeenIp
     *
     * @return $this
     */
    public function setLastSeenIp($lastSeenIp)
    {
        $this->lastSeenIp = $lastSeenIp;

        return $this;
    }

    /**
     * Get LastSeenIp
     *
     * @return string
     */
    public function getLastSeenIp()
    {
        return $this->lastSeenIp;
    }

    /**
     * Set CustomAttributes
     *
     * @param  array $customAttributes
     *
     * @return $this
     */
    public function setCustomAttributes(array $customAttributes)
    {
        $this->customAttributes = $customAttributes;

        return $this;
    }

    /**
     * Get CustomAttributes
     *
     * @return array
     */
    public function getCustomAttributes($property = null)
    {
        return null !== $property && isset($this->customAttributes[$property]) ? $this->customAttributes[$property] : $this->customAttributes;
    }

    /**
     * Add a property in customAttributes
     *
     * @param string $property
     * @param string $value
     */
    public function addCustomAttributes($property, $value)
    {
        $this->customAttributes[$property] = $value;

        return $this;
    }

    /**
     * Has a gievn property in customAttributes
     *
     * @param  string  $property
     *
     * @return boolean
     */
    public function hasCustomAttributes($property)
    {
        return isset($this->customAttributes[$property]);
    }

    /**
     * Set LastSeenUserAgent
     *
     * @param  string $lastSeenUserAgent
     *
     * @return $this
     */
    public function setLastSeenUserAgent($lastSeenUserAgent)
    {
        $this->lastSeenUserAgent = $lastSeenUserAgent;

        return $this;
    }

    /**
     * Get LastSeenUserAgent
     *
     * @return string
     */
    public function getLastSeenUserAgent()
    {
        return $this->lastSeenUserAgent;
    }

    /**
     * Set LastRequestAt
     *
     * @param  string $lastRequestAt
     *
     * @return $this
     */
    public function setLastRequestAt($lastRequestAt)
    {
        $this->lastRequestAt = $lastRequestAt;

        return $this;
    }

    /**
     * Get LastRequestAt
     *
     * @return string
     */
    public function getLastRequestAt()
    {
        return $this->lastRequestAt;
    }

    /**
     * Set UnsubscribedFromEmails
     *
     * @param  boolean $unsubscribedFromEmails
     *
     * @return $this
     */
    public function setUnsubscribedFromEmails($unsubscribedFromEmails)
    {
        $this->unsubscribedFromEmails = $unsubscribedFromEmails;

        return $this;
    }

    /**
     * Get UnsubscribedFromEmails
     *
     * @return boolean
     */
    public function getUnsubscribedFromEmails()
    {
        return $this->unsubscribedFromEmails;
    }

    /**
     * Set LocationData
     *
     * @param  mixed $locationData
     *
     * @return $this
     */
    public function setLocationData($locationData)
    {
        $this->locationData = $locationData;

        return $this;
    }

    /**
     * Get LocationData
     *
     * @return mixed
     */
    public function getLocationData()
    {
        return $this->locationData;
    }

    /**
     * Set SessionCount
     *
     * @param  integer $sessionCount
     *
     * @return $this
     */
    public function setSessionCount($sessionCount)
    {
        $this->sessionCount = $sessionCount;

        return $this;
    }

    /**
     * Get SessionCount
     *
     * @return integer
     */
    public function getSessionCount()
    {
        return $this->sessionCount;
    }

    /**
     * Set SocialProfiles
     *
     * @param  mixed $socialProfiles
     *
     * @return $this
     */
    public function setSocialProfiles($socialProfiles)
    {
        $this->socialProfiles = $socialProfiles;

        return $this;
    }

    /**
     * Get SocialProfiles
     *
     * @return mixed
     */
    public function getSocialProfiles()
    {
        return $this->socialProfiles;
    }

    /**
     * Set LastImpressionAt
     *
     * @param  string $lastImpressionAt
     *
     * @return $this
     */
    public function setLastImpressionAt($lastImpressionAt)
    {
        $this->lastImpressionAt = $lastImpressionAt;

        return $this;
    }

    /**
     * Get LastImpressionAt
     *
     * @return string
     */
    public function getLastImpressionAt()
    {
        return $this->lastImpressionAt;
    }

    /**
     * Set AvatarUrl
     *
     * @param  string $avatarUrl
     *
     * @return $this
     */
    public function setAvatarUrl($avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    /**
     * Get AvatarUrl
     *
     * @return string
     */
    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    /**
     * Set CompanyIds
     *
     * @param  mixed $companyIds
     *
     * @return $this
     */
    public function setCompanyIds($companyIds)
    {
        $this->companyIds = $companyIds;

        return $this;
    }

    /**
     * Get CompanyIds
     *
     * @return mixed
     */
    public function getCompanyIds()
    {
        return $this->companyIds;
    }

    /**
     * Set VipTimestamp
     *
     * @param  string $vipTimestamp
     *
     * @return $this
     */
    public function setVipTimestamp($vipTimestamp)
    {
        $this->vipTimestamp = $vipTimestamp;

        return $this;
    }

    /**
     * Get VipTimestamp
     *
     * @return string
     */
    public function getVipTimestamp()
    {
        return $this->vipTimestamp;
    }
}
