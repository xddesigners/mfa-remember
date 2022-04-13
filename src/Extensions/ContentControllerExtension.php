<?php

namespace XD\MFARemember\Extensions;

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\SSViewer;
use XD\MFARemember\Models\LoginDevice;

/**
 * @property ContentController owner
 */
class ContentControllerExtension extends Extension
{
    private static $allowed_actions = [
        'rememberDevice',
        'alwaysAsk',
    ];

    public function rememberDevice(HTTPRequest $request)
    {
        $device = LoginDevice::getCurrent();
        $device->Remember = LoginDevice::REMEMBER_YES;
        $device->write();
        return $this->owner->redirectBack();
    }

    /**
     * Get the Device and check if the preference has been set
     */
    public function alwaysAsk(HTTPRequest $request)
    {
        $device = LoginDevice::getCurrent();
        $device->Remember = LoginDevice::REMEMBER_NO;
        $device->write();
        return $this->owner->redirectBack();
    }

    /**
     * Get the Device and check if the preference has been set
     */
    private function shouldDisplay()
    {
        $device = LoginDevice::getCurrent();
        // echo '<pre>';
        // print_r($device);
        // echo '</pre>';
        // exit();
        return $device && ($device->Remember === LoginDevice::REMEMBER_EMPTY);
    }

    public function afterCallActionHandler($request, $action, $result)
    {
        // Check that we're dealing with HTML
        $isHtmlResponse = $result instanceof DBHTMLText ||
            $result instanceof HTTPResponse && strpos($result->getHeader('content-type'), 'text/html') !== false;

        if (!$isHtmlResponse || !$this->shouldDisplay()) {
            return $result;
        }

        $html = $result instanceof DBHTMLText ? $result->getValue() : $result->getBody();
        $toastHTML = SSViewer::execute_template('XD\\MFARemember\\Toast', $this->owner);

        // Inject the NavigatorHTML before the closing </body> tag
        $html = preg_replace(
            '/(<\/body[^>]*>)/i',
            $toastHTML . '\\1',
            $html
        );
        if ($result instanceof DBHTMLText) {
            $result->setValue($html);
        } else {
            $result->setBody($html);
        }

        return $result;
    }
}
