<?php

namespace App\Helpers;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class AgentClientHelper
{
    public function getClientDetails(Request $request)
    {
        $agent = new Agent();

        // Get device information
        $device = $agent->device();
        $platform = $agent->platform();
        $platformVersion = $agent->version($platform);
        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);
        $isMobile = $agent->isMobile();
        $isTablet = $agent->isTablet();
        $isDesktop = $agent->isDesktop();
        $isBot = $agent->isRobot();
        $ipAddress = $request->ip();

        // Check if the device is iPhone or Android
        $isIphone = $agent->is('iPhone');
        $isAndroid = $agent->is('AndroidOS');

        // Return as an associative array
        return [
            'device' => $device,
            'platform' => $platform,
            'platform_version' => $platformVersion,
            'browser' => $browser,
            'browser_version' => $browserVersion,
            'is_mobile' => $isMobile,
            'is_tablet' => $isTablet,
            'is_desktop' => $isDesktop,
            'is_bot' => $isBot,
            'ip_address' => $ipAddress,
            'is_iphone' => $isIphone,
            'is_android' => $isAndroid,
        ];
}
}
