<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class GoldPriceController extends Controller
{
    public function __invoke()
    {
        $cacheKey = 'gold_prices_api';

        $cachedResponse = Cache::get($cacheKey);

        if ($cachedResponse) {
            return $cachedResponse;
        }

        try {
            $response = Http::get('https://www.moci.gov.kw/ar/market-prices/gold');

            if ($response->status() == 200) {
                $html = $response->body();

                $crawler = new Crawler($html);

                $goldPrices = [];

                $crawler->filter('#gold_price tbody tr')->each(function ($tr) use (&$goldPrices) {
                    $type = $this->extractNumberFromType($tr->filter('.gold_type')->text());
                    $dinarPrice = $this->extractNumberFromType($tr->filter('.price_dinar')->text());
                    $dollarPrice = $this->extractNumberFromType($tr->filter('.price_dolar')->text());

                    $goldPrices[] = [
                        'type' => $type,
                        'dinar_price' => $dinarPrice,
                        'dollar_price' => $dollarPrice,
                    ];
                });

                // Cache the response for one day (in minutes).
                Cache::put($cacheKey, response()->json(['gold_prices' => $goldPrices]), 1440);

                return response()->json(['gold_prices' => $goldPrices]);
            } else {
                return response()->json(['error' => 'Failed to fetch data'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function extractNumberFromType($type)
    {
        preg_match('/\d+(\.\d+)?/', $type, $matches);

        if (isset($matches[0])) {
            return $matches[0];
        }

        return null;
    }
}
