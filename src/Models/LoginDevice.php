<?php

namespace XD\MFARemember\Models;

use SilverStripe\Control\Controller;
use SilverStripe\Control\Cookie;
use SilverStripe\Control\Director;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;

/**
 * Class LoginDevice
 * @package XD\MFARemember\Models
 * @method Member $Member
 * @property String UniqueID
 * @property String LastAccessed
 * @property String IPAdress
 * @property String UserAgent
 */
class LoginDevice extends DataObject
{
    const REMEMBER_EMPTY = 'empty';
    const REMEMBER_YES = 'yes';
    const REMEMBER_NO = 'no';

    private static $table_name = 'LoginDevice';

    private static $expiry = 30;

    private static $geocodeService = 'https://freegeoip.app/json/';

    private static $db = [
        'UniqueID' => 'Varchar',
        'Remember' => 'Enum("empty,yes,no","empty")',
        'LastAccessed' => 'DBDatetime',
        'IPAddress' => 'Varchar(45)',
        'UserAgent' => 'Text',
        'Expiry' => 'Int',
        'Country' => 'Varchar',
        'City' => 'Varchar'
    ];

    private static $has_one = [
        'Member' => Member::class
    ];

    private static $summary_fields = [
        'Created' => 'Created',
        'LastAccessed' => 'LastAccessed',
        'IPAddress' => 'IPAddress',
//        'UserAgent' => 'UserAgent',
        'Expiry' => 'Expiry',
        'Country' => 'Country',
        'City' => 'City',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('MemberID');
        return $fields;

    }

    public static function getCurrent($member = null)
    {
        $member = $member ?? Security::getCurrentUser();
        if (!$member || !$uniqID = Cookie::get('mfa_device')) {
            return null;
        }

        return DataObject::get_one(LoginDevice::class, ['UniqueID' => $uniqID, 'MemberID' => $member->ID]);
    }

    public static function check($member)
    {
        $device = self::getCurrent($member);
        return (boolean)$device->Remember === self::REMEMBER_YES;
    }

    public function updateDevice()
    {
        $controller = Controller::curr();
        $request = $controller->getRequest();
        $this->update([
            'LastAccessed' => DBDatetime::now()->Rfc2822(),
            'IPAddress' => $request->getIP(),
            'UserAgent' => $request->getHeader('User-Agent'),
            'Expiry' => self::config()->get('expiry')
        ]);

        // update cookie to new date
        $secure = !Director::isDev();
        Cookie::set('mfa_device', $this->UniqueID, self::config()->get('expiry'), null, null, $secure);//$secure);
    }

    public static function createDevice($member)
    {
        // check if cookie matches device or if device has been deleted by user
        if ($uniqueID = Cookie::get('mfa_device')) {
            if (LoginDevice::get()->filter(['UniqueID' => $uniqueID, 'MemberID' => $member->ID])->first()) {
                return;
            }
        }

        // create and set cookie
        $uniqueID = uniqid();
        $device = self::create(['UniqueID' => $uniqueID, 'MemberID' => $member->ID]);
        $device->updateDevice();
        $device->write();
    }

    public function updateGeoLocation()
    {
        $service = self::config()->get('geocodeService');
        if( $service ) {
            $apiURL = $service . $this->IPAdress;
            $ch = curl_init($apiURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $apiResponse = curl_exec($ch);
            curl_close($ch);
            $ipData = json_decode($apiResponse, true);
            $this->Country = $ipData['country_name'];
            $this->City = $ipData['city'];
        }
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        $this->updateGeoLocation();
    }


}