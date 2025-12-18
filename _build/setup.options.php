<?php
/**
 * @package smushit
 * @subpackage build
 */

$package = 'smushit';

$settings = array(
    array(
        'key' => 'smushit_qlty',
        'value' => '80',
        'name' => 'JPEG default quality',
    ),
);

/* get values based on mode */
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:

        foreach ($settings as $key => $setting) {
            $settingObject = $modx->getObject(
                'modSystemSetting',
                array('key' => strtolower($package) . '.' . $setting['key'])
            );
            if ($settingObject) {
                $settings[$key]['value'] = $settingObject->get('value');
            }
        }

        break;
    case xPDOTransport::ACTION_UPGRADE:
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

$output[] = '<h2>SmushIt For MODX</h2>
<p>Thanks for installing SmushIt! Please review the setup options below before proceeding.</p>';
$output[] = '<h3>Requirements</h3>
<ul>
    <li> - PHP version 7.3+ to 8.0 <u>not yet tested</u></li>
    <li> - PHP version 8.1+ is recommended</li>
<ul>';

foreach ($settings as $setting) {
    $str = '<label for="'. $setting['key'] .'">'. $setting['name'] .'</label>';
    $str .= '<input type="text" name="'. $setting['key'] .'"';
    $str .= ' id="'. $setting['key'] .'" width="300" value="'. $setting['value'] .'" />';
    $output[] = $str;
}

$output[] = '<p style="background: #fafafa; padding: 10px; text-align: center;">SmushIt is brought to you by <a href="https://www.stewartorr.co.uk" target="_blank" style="color: #ef3f24; text-decoration: none;"><img src="https://www.stewartorr.co.uk/assets/images/logo-orange.svg" alt="Stewart Orr" width="16" height="14" style="vertical-align: middle;" /> Stewart Orr</a></p>';
return implode('<br /><br />', $output);