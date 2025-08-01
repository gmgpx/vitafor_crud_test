<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Character;

class CharacterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store', 'destroy', 'edit', 'update']);
    }

    public function index(Request $request)
    {
        $filters = $request->only(['name', 'status', 'species', 'gender']);
        $page = $request->get('page', 1);

        $queryParams = array_merge($filters, ['page' => $page]);

        $response = Http::get('https://rickandmortyapi.com/api/character', $queryParams);

        if ($response->failed()) {
            return view('home', [
                'characters' => [],
                'info' => ['pages' => 0],
                'page' => 1,
                'filters' => $filters,
                'error' => 'Nenhum personagem encontrado com esses filtros.'
            ]);
        }

        $data = $response->json();

        return view('home', [
            'characters' => $data['results'],
            'info' => $data['info'],
            'page' => $page,
            'filters' => $filters
        ]);
    }

    public function loadCharactersAjax(Request $request)
    {
        $filters = $request->only(['name', 'status', 'species', 'gender']);
        $page = $request->get('page', 1);

        $queryParams = array_merge($filters, ['page' => $page]);

        $response = Http::get('https://rickandmortyapi.com/api/character', $queryParams);

        if ($response->failed()) {
            return response()->json([
                'html' => view('partials.characters-list', [
                    'characters' => [],
                    'info' => ['pages' => 0],
                    'page' => 1,
                    'filters' => $filters,
                    'error' => 'Nenhum personagem encontrado com esses filtros.',
                ])->render()
            ]);
        }

        $data = $response->json();

        return response()->json([
            'html' => view('partials.characters-list', [
                'characters' => $data['results'],
                'info' => $data['info'],
                'page' => $page,
                'filters' => $filters,
                'error' => null,
            ])->render()
        ]);
    }

    public function myCharacters()
    {
        $characters = Character::where('user_id', Auth::id())->get();
        $ownedNames = $characters->pluck('name')
            ->map(fn($name) => Str::lower(trim($name)))
            ->toArray();

        $groups = [
            'Rick and Morty' => [
                'Rick Sanchez' => 'https://rickandmortyapi.com/api/character/avatar/1.jpeg',
                'Morty Smith' => 'https://rickandmortyapi.com/api/character/avatar/2.jpeg',
            ],
            'Família Smith' => [
                'Rick Sanchez' => 'https://rickandmortyapi.com/api/character/avatar/1.jpeg',
                'Morty Smith' => 'https://rickandmortyapi.com/api/character/avatar/2.jpeg',
                'Summer Smith' => 'https://rickandmortyapi.com/api/character/avatar/3.jpeg',
                'Beth Smith' => 'https://rickandmortyapi.com/api/character/avatar/4.jpeg',
                'Jerry Smith' => 'https://rickandmortyapi.com/api/character/avatar/5.jpeg',
            ],
            'Arquinimigos do Rick' => [
                'Rick Prime' => 'https://rickandmortyapi.com/api/character/avatar/285.jpeg',
                'Evil Morty' => 'https://rickandmortyapi.com/api/character/avatar/118.jpeg',
                'Mr. Nimbus' => 'https://rickandmortyapi.com/api/character/avatar/672.jpeg',
            ],
            'Amigos do Rick' => [
                'Mr. Poopybutthole' => 'https://rickandmortyapi.com/api/character/avatar/244.jpeg',
                'Revolio Clockberg Jr.' => 'https://rickandmortyapi.com/api/character/avatar/282.jpeg',
                'Squanchy' => 'https://rickandmortyapi.com/api/character/avatar/331.jpeg',
                'Birdperson' => 'https://rickandmortyapi.com/api/character/avatar/47.jpeg',
            ],

            'Vindicators' => [
                'Supernova' => 'https://rickandmortyapi.com/api/character/avatar/340.jpeg',
                'Calypso' => 'https://rickandmortyapi.com/api/character/avatar/60.jpeg',
                'Crocubot' => 'https://rickandmortyapi.com/api/character/avatar/81.jpeg',
                'Lady Katana' => 'https://rickandmortyapi.com/api/character/avatar/198.jpeg',
                'Alan Rails' => 'https://rickandmortyapi.com/api/character/avatar/10.jpeg',
                'Vance Maximus' => 'https://rickandmortyapi.com/api/character/avatar/375.jpeg',
            ],
        ];

        $badges = collect($groups)->map(function ($group, $title) use ($ownedNames) {
            $members = collect($group)->map(function ($img, $name) use ($ownedNames) {
                $normalized = Str::lower(trim($name));
                return [
                    'name' => $name,
                    'image' => $img,
                    'owned' => in_array($normalized, $ownedNames),
                ];
            });

            $hasAtLeastOne = $members->contains(fn($m) => $m['owned']);
            $completed = $members->every(fn($m) => $m['owned']);

            return $hasAtLeastOne ? [
                'title' => $title,
                'members' => $members,
                'completed' => $completed,
            ] : null;
        })->filter();
        $allGroupsCompleted = $badges->every(fn($badge) => $badge['completed']);

        return view('characters', compact('characters', 'badges', 'allGroupsCompleted'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'image' => 'required|url',
            'url' => 'required|url',
        ]);

        $exists = Character::where('user_id', Auth::id())
            ->where('name', $request->name)
            ->first();

        if ($exists) {
            $exists->species = $request->species;
            $exists->image = $request->image;
            $exists->url = $request->url;
            $exists->updated_at = now();
            $exists->save();
            return redirect()->route('characters.index')->with('success', 'Personagem atualizado!');
        }

        Character::create([
            'name' => $request->name,
            'species' => $request->species,
            'image' => $request->image,
            'url' => $request->url,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('characters.index')->with('success', 'Personagem salvo!');
    }

    public function show($id)
    {
        $character = null;
        $fromDb = false;

        if (Auth::check()) {
            $characterModel = Character::where('id', $id)->where('user_id', Auth::id())->first();
            if ($characterModel) {
                $fromDb = true;

                $response = Http::get("https://rickandmortyapi.com/api/character/{$id}");
                $apiData = $response->json();

                $character = [
                    'id' => $characterModel->id,
                    'name' => $characterModel->name,
                    'species' => $characterModel->species,
                    'image' => $characterModel->image,
                    'url' => $characterModel->url,
                    'created_at' => $characterModel->created_at,
                    'updated_at' => $characterModel->updated_at,
                    'status' => $apiData['status'] ?? 'unknown',
                    'gender' => $apiData['gender'] ?? 'unknown',
                    'origin' => $apiData['origin'] ?? null,
                    'location' => $apiData['location'] ?? null,
                ];
            }
        }

        if (!$character) {
            $response = Http::get("https://rickandmortyapi.com/api/character/{$id}");
            $character = $response->json();
            $fromDb = false;
        }

        return view('details', ['character' => $character, 'fromDb' => $fromDb]);
    }

    public function destroy($id)
    {
        $character = Character::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $character->delete();
        return redirect()->route('characters.index')->with('success', 'Personagem excluído!');
    }

    public function edit($id)
    {
        $character = Character::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('characters.edit', compact('character'));
    }

    public function update(Request $request, $id)
    {
        $character = Character::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'image' => 'required|url',
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return redirect()->route('characters.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $character->update($request->only(['name', 'species', 'image', 'url']));

        return redirect()->route('characters.index')->with('success', 'Personagem atualizado com sucesso!');
    }
}
