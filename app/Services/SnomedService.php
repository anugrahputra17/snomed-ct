<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SnomedService
{
    /**
     * Search SNOMED by keyword (for Select dropdown)
     */
    public function searchByTerm(string $term): array
    {
        if (strlen($term) < 3) {
            return [];
        }

        $cacheKey = 'snomed_search_' . md5($term);

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($term) {

            /** @var Response $response */
            $response = Http::get(
                'https://browser.ihtsdotools.org/snowstorm/snomed-ct/MAIN/concepts',
                [
                    'term'  => $term,
                    'limit' => 10,
                    'activeFilter' => 'true',
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
                ->mapWithKeys(fn (array $item) => [
                    $item['conceptId'] => $item['fsn']['term'],
                ])
                ->toArray();
        });
    }

    /**
     * Get single SNOMED concept by conceptId
     */
    public function getByConceptId(string $conceptId): ?string
    {
        $cacheKey = 'snomed_concept_' . $conceptId;

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($conceptId) {

            /** @var Response $response */
            $response = Http::get(
                "https://browser.ihtsdotools.org/snowstorm/snomed-ct/MAIN/concepts/{$conceptId}"
            );

            if (! $response->successful()) {
                return null;
            }

            return $response->json('fsn.term');
        });
    }

    public function formatLabel(string $conceptId): ?string
{
    $term = $this->getByConceptId($conceptId);

    return $term
        ? "{$conceptId} — {$term}"
        : $conceptId;
}

}
