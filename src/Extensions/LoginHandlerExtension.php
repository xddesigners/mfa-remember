<?php

namespace XD\MFARemember\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\MFA\Authenticator\LoginHandler;
use XD\MFARemember\Models\LoginDevice;

/**
 * Class LoginHandlerExtension
 * @package XD\MFARemember\Extensions
 * @property LoginHandler|LoginHandlerExtension $owner
 */
class LoginHandlerExtension extends Extension
{

    public function onMethodVerificationSuccess($member, $methodInstance)
    {
        // store device
        LoginDevice::createDevice($member);
    }


}