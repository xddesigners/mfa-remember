# SilverStripe MFA Remember

Allow users to remember a browser so a user doesnt have to enter a code when they log in again from that browser.

After a successfull login with MFA, the user gets an prompt to save the browser or continue. If the browser is saved MFA won't be triggered again. If the browser isn't saved MFA will always trigger for that browser.

## Requirements

* PHP ^7.4
* SilverStripe ^4.1
* silverstripe/mfa: ^4.0

## Installation

Install with Composer:

```bash
composer require xddesigners/mfa-remember
```
