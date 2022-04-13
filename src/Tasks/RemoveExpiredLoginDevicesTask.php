<?php

namespace SilverStripe\MFA\Tasks;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\BuildTask;
use XD\MFARemember\Models\LoginDevice;

/**
 * Class RemoveExpiredLoginDevicesTask
 * @package SilverStripe\MFA\Tasks
 */
class RemoveExpiredLoginDevicesTask extends BuildTask
{
    /**
     * @var string
     */
    private static $segment = 'RemoveExpiredLoginDevicesTask';

    /**
     * @var string
     */
    protected $title = 'Remove expired MFA login devices';

    /**
     * @var string
     */
    protected $description = 'Removes expired MFA login devices from the database';

    /**
     * @param HTTPRequest $request
     */
    public function run($request)
    {
        // find expired stored login devices
        $lifetime = LoginDevice::config()->get('expiry');
        $devices = LoginDevice::get()->filter([
            'LastAccessed:LessThan' => date('Y-m-d H:i:s', time() - $lifetime)
        ]);
        $devices->removeAll();
    }
}
