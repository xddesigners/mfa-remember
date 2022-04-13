<?php

namespace XD\MFARemember\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\MFA\Service\RegisteredMethodManager;
use XD\MFARemember\Models\LoginDevice;

/**
 * Class RegisteredMethodManagerExtension
 * @package XD\MFARemember\Extensions
 * @property RegisteredMethodManager|RegisteredMethodManagerExtension $owner
 */
class RegisteredMethodManagerExtension extends Extension
{

    public function onRegisterMethod($member, $method)
    {
        // store device
        LoginDevice::createDevice($member);
    }


}