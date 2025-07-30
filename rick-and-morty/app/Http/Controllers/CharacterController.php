<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Character;

class CharacterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['myCharacters', 'store', 'destroy', 'edit', 'update']);
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
        return view('characters', compact('characters'));
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
            return redirect()->route('characters.index')->with('warning', 'Personagem já está salvo.');
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
                    'name' => $characterModel->name,
                    'species' => $characterModel->species,
                    'image' => $characterModel->image,
                    'url' => $characterModel->url,
                    'created_at' => $characterModel->created_at,
                    'updated_at' => $characterModel->updated_at,
                    'status' => $apiData['status'],
                    'gender' => $apiData['gender'],
                    'origin' => $apiData['origin'],
                    'location' => $apiData['location'],
                ];
            }
        }

        if (!$character) {
            $response = Http::get("https://rickandmortyapi.com/api/character/{$id}");
            $character = $response->json();
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
