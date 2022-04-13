<?php

namespace XD\MFARemember\Injector;

use SilverStripe\Security\Member;
use XD\MFARemember\Models\LoginDevice;

class EnforcementManager extends \SilverStripe\MFA\Service\EnforcementManager
{
    public function shouldRedirectToMFA(Member $member): bool
    {
        // test if logged in from known location
        // check if cookie is set with known device UUID
        // cookie is set after successful MFA login
        if( LoginDevice::check($member) ) return false;

        return parent::shouldRedirectToMFA($member);
    }

}