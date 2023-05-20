<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class Bob
{
    protected array $bobCookie;

    protected array $onPropertyCookie;

    public function __construct(array $bobCookie, array $onPropertyCookie)
    {
        $this->bobCookie = $bobCookie;
        $this->onPropertyCookie = $onPropertyCookie;
    }

    public function getAdvisor(): array
    {
        return Cache::remember(
            key: 'data::advisor',
            ttl: now()->addDay(),
            callback: fn () => Http::acceptJson()
                ->withCookies($this->bobCookie, 'styreportalen.bob.no')
                ->get('https://styreportalen.bob.no/api/advisor', [
                    'clientId' => '58',
                ])
                ->json()
        );
    }

    public function getBoardMembers(): Collection
    {
        return Cache::remember(
            key: 'data::board2',
            ttl: now()->addHour(),
            callback: function () {
                $response = Http::acceptJson()
                    ->withCookies($this->bobCookie, 'styreportalen.bob.no')
                    ->get('https://styreportalen.bob.no/klient/58/informasjon-om-styret');

                $crawler = new Crawler($response->body());
                $json = $crawler->filter('#__NEXT_DATA__')->html();
                $data = json_decode($json, true);

                return collect($data['props']['pageProps']['organizationRoles']);
            });

        return Cache::remember(
            key: 'data::board2',
            ttl: now()->addDay(),
            callback: fn () => Http::acceptJson()
                ->withCookies($this->bobCookie, 'styreportalen.bob.no')
                ->get('https://styreportalen.bob.no/_next/data/gxrnALqPPm5Rbx7xL9dil/klient/58/informasjon-om-styret.json', [
                    'clientId' => '58',
                ])
                ->collect('pageProps.organizationRoles')
        );
    }

    public function getRentersAndResidents(): Collection
    {
        return collect([
            $this->getResidents(),
            $this->getRenters(),
        ])->collapse()->values();
    }

    public function getResidents(): Collection
    {
        return Cache::remember(
            key: 'residents::index',
            ttl: now()->addDay(),
            callback: fn () => Http::acceptJson()
                ->withCookies($this->onPropertyCookie, 'bob.on.no')
                ->get('https://bob.on.no/ResidentProperty', [
                    'handler' => 'propertyList',
                    'skip' => 0,
                    'take' => 100,
                    'requireTotalCount' => 'false',
                    '_' => microtime(),
                ])
                ->collect('data')
        );
    }

    public function getRenters(): Collection
    {
        return Cache::remember(
            key: 'renters::index',
            ttl: now()->addDay(),
            callback: fn () => Http::acceptJson()
                ->withCookies($this->onPropertyCookie, 'bob.on.no')
                ->get('https://bob.on.no/RenterProperty', [
                    'handler' => 'propertyList',
                    'skip' => 0,
                    'take' => 100,
                    'requireTotalCount' => 'false',
                    '_' => microtime(),
                ])
                ->collect('data')
        );
    }
}
