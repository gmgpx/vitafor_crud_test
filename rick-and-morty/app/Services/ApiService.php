<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ApiService
{
    public function fetchCharacters(array $queryParams)
    {
        $cacheKey = 'rickandmorty_characters_' . md5(json_encode($queryParams));

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($queryParams) {
            try {
                $response = Http::timeout(5)->get('https://rickandmortyapi.com/api/character', $queryParams);

                if ($response->successful()) {
                    return $response->json();
                }

                return null;
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    public function fetchCharacterById(int $id)
    {
        try {
            $response = Http::timeout(5)->get("https://rickandmortyapi.com/api/character/{$id}");
            if ($response->successful()) {
                return $response->json();
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
