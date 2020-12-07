![alt text](https://github.com/Tikol4Life/Tikol4LifeChecker/blob/main/assets/img/Tikol4Life_Header.PNG?raw=true)


## Tikol4Life Checker Source

Changelogs:
- SK Live and ChatID can now be stored as cookies.
- Added basic Theme changer (Light and Dark modes).
- Added Popup Notifications.
- Added forceHttps and forceAuth in config.php (read Dev Notes for more info).
- Added Bug BIN filters in config.php.

Features:
- 4 SK Based API
- CC Generator
- Checker Delay (default: 1 second)
- Telegram Forwarder (CVV and CCN)

Dev Notes [config.php additions]
- forceHttps - (boolean) forces weblink to initiate ssl connection. Highly recommended for PCI compliance.
- forceAuth - (boolean) forces users to input AuthPass to enter. Default AuthPass is Tikol4Life.
- testMode - (boolean) shows json response of each cURL resquest.
