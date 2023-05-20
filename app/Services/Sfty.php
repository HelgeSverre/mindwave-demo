<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Sfty
{
    const ACCESS_TOKEN = 'pfOMHfBPj8mdxNLcS4iA7-aT4dB2LRsfBL02liF95QxOHR79CNX-ISsDDnkc2Mp4';

    protected ?string $authToken;

    protected ?string $webAuthToken;

    public function __construct(
        ?string $authToken = null,
        ?string $webAuthToken = null,
    ) {
        $this->authToken = $authToken;
        $this->webAuthToken = $webAuthToken;
    }

    public static function login(
        string $username,
        string $password,
        string $deviceType = 'web',
        string $webAppVersion = '5.4.0',
        string $webOsVersion = 'Chrome 107.0.0.0'
    ): self {
        $response = Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
        ])->post('https://home.sfty.com/api/vx1/login', [
            'phone' => $username,
            'password' => $password,
            'device_type' => $deviceType,
            'web_app_version' => $webAppVersion,
            'web_os_version' => $webOsVersion,
        ]);

        return new self(
            authToken: $response->json('web_auth_token'),
            webAuthToken: $response->json('auth_token'),
        );
    }

    public static function access(
        ?string $authToken = null,
        ?string $webAuthToken = null,
    ): self {
        return new self(
            authToken: $authToken,
            webAuthToken: $webAuthToken,
        );
    }

    public function validatePhone(string $phoneNumber)
    {
        // POST
        // https://home.sfty.com/api/vx1/registration/validate_phone
        // {"phone":"+4795965871"}
        // HEADRE: access-token: pfOMHfBPj8mdxNLcS4iA7-aT4dB2LRsfBL02liF95QxOHR79CNX-ISsDDnkc2Mp4
    }

    public function agreements(): Response
    {
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get('https://home.sfty.com/api/vx1/agreements');

        // Request URL:
        //Request Method: GET
        //        access-token: pfOMHfBPj8mdxNLcS4iA7-aT4dB2LRsfBL02liF95QxOHR79CNX-ISsDDnkc2Mp4
        //auth-token: a1SZekEjD1PPf8lsQZY40g
    }

    public function agreement($agreementId)
    {
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/vx1/agreements/{$agreementId}");

        //GET https://home.sfty.com/api/vx1/agreements/463
    }

    public function agreementSummary($agreementId)
    {
        // https://home.sfty.com/api/v5/agreements/463/summary
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/vx1/agreements/{$agreementId}/summary");

    }

    public function agreementAdmins($agreementId)
    {
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/vx1/agreements/{$agreementId}/admins", [
            'direction' => 'asc',
            'limit' => 100,
            'order' => 'name',
            'page' => 1,
        ]);
        // GET https://home.sfty.com/api/v5/agreements/463/admins
        // query params
        // direction=asc
        // limit=100
        // order=name
        // page=1
    }

    public function agreementAlarmGroups($agreementId)
    {
        // https://home.sfty.com/api/vx1/agreements/463/alarm_groups
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/vx1/agreements/{$agreementId}/alarm_groups");

    }

    public function agreementAlarmGroup($agreementId, $alarmGroupId)
    {
        // https://home.sfty.com/api/v5/agreements/463/alarm_group/6858
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/v5/agreements/{$agreementId}/alarm_group/{$alarmGroupId}");

    }

    public function agreementAlarmGroupProgress($agreementId, $alarmGroupId)
    {
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/v5/agreements/{$agreementId}/alarm_group/{$alarmGroupId}/progress");

    }

    public function agreementAlarmGroupStats($agreementId, $alarmGroupId)
    {
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/v5/agreements/{$agreementId}/alarm_group_stats/{$alarmGroupId}");
        //
    }

    public function agreementAlarmGroupZones($agreementId, $alarmGroupId)
    {
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/vx1/agreements/{$agreementId}/alarm_group/{$alarmGroupId}/zones");
    }

    public function mduEvents($alarmGroupId, ?array $params = [])
    {
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/vx1/mdu/{$alarmGroupId}/mdu_events", $params);
        //
    }

    public function apartmentImps($mduApartmentId)
    {
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/v5/apartment/apartment_imps/{$mduApartmentId}");
        // mdu_apartment_id from agreementAlarmGroupZones when area = "appartment"
        // https://home.sfty.com/api/v5/apartment/apartment_imps/65211
    }

    public function commonAreaImps($mduCommonAreaId)
    {
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/v4/common_area/{$mduCommonAreaId}/imps");
        // note: mdu_common_area_id is used from  agreementAlarmGroupZones when area = "area" and not "appartment"
        // https://home.sfty.com/api/v4/common_area/{$mduCommonAreaId}/imps
    }

    public function zoneStatusCommonArea()
    {
        // https://home.sfty.com/api/vx1/agreements/463/alarm_group/6858/zone_status?mdu_common_area_id=9249
    }

    public function zoneStatusApartment()
    {
        // https://home.sfty.com/api/vx1/agreements/463/alarm_group/6858/zone_status?mdu_apartment_id=65204
    }
    // https://home.sfty.com/api/vx1/apartment/65211/zone_status
    // https://home.sfty.com/api/v5/apartment/apartment_wlds/65211
    // https://home.sfty.com/api/vx1/wlds/65211/info

    // https://home.sfty.com/api/vx1/agreements/463/thermo_alarm_charts?days=28&mdu_apartment_id=65211
    // https://home.sfty.com/api/vx1/mdu/65211/apartment_contacts
    // https://home.sfty.com/api/v5/home/20321/contacts

    public function impCharts($impId)
    {
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/v5/imp/{$impId}/charts");

    }

    public function impGraphsData($impId)
    {
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/v5/imp/{$impId}/graphs_data");
    }

    public function impWifiGraphsData($impId)
    {
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/v5/imp/{$impId}/wifi_graphs_data");
    }

    public function impMotionGraphsData($impId)
    {
        return Http::withHeaders([
            'access-token' => self::ACCESS_TOKEN,
            'auth-token' => $this->webAuthToken,
        ])->get("https://home.sfty.com/api/v5/imp/{$impId}/motion_graphs_data");
    }

    // https://home.sfty.com/api/v5/imp/37230/web_charts - broken
    // https://home.sfty.com/api/v5/imp/37230/charts - html with highchartjs embedded, put in iframe
    // https://home.sfty.com/api/v5/imp/37230/motion_graphs_data
    // https://home.sfty.com/api/v5/imp/37230/wifi_graphs_data
    // https://home.sfty.com/api/v5/imp/37230/graphs_data
    // https://home.sfty.com/api/v5/imp/37230/smoke_graphs_data

    // https://home.sfty.com/api/v5/imp/37231/sense_info
    // https://home.sfty.com/api/v5/imp/37231/sense_extended_info

    public function ticketMessages()
    {
        // https://home.sfty.com/api/vx1/tickets/messages/all?limit=1&marked=true
    }
}
