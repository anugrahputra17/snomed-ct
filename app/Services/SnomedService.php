<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class SnomedService
{
    public function search(string $keyword): array
    {
        if (strlen($keyword) < 3) {
            return [];
        }

        /** @var Response $response */
        $response = Http::withoutVerifying()->get(
            'https://browser.ihtsdotools.org/snowstorm/snomed-ct/MAIN/concepts',
        [
                'term' => $keyword,
                'limit' => 10,
            ]
        );

        if (! $response->successful()) {
            return [];
        }

        $items = $response->json('items');

        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->mapWithKeys(function (array $item) {
                return [
                    $item['conceptId'] => $item['pt']['term'],
                ];
            })
            ->toArray();
    }
}
