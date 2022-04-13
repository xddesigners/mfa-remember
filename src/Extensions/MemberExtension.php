<?php

namespace XD\MFARemember\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Security\Member;
use XD\MFARemember\Models\LoginDevice;

/**
 * Class MemberExtension
 * @package XD\MFARemember\Extensions
 * @property Member|MemberExtension $owner
 */
class MemberExtension extends DataExtension
{

    private static $has_many = [
      'LoginDevices' => LoginDevice::class
    ];

}