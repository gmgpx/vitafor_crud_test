<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Character;
use Illuminate\Support\Facades\Auth;

class CharacterController extends Controller
{
    public function index()
    {
        $response = Http::get('https://rickandmortyapi.com/api/character');
        $characters = $response->json()['results'];
        return view('home', compact('characters'));
    }

    public function myCharacters()
    {
        $this->middleware('auth');
        $characters = Character::where('user_id', Auth::id())->get();
        return view('characters', compact('characters'));
    }

    public function store(Request $request)
    {
        $this->middleware('auth');
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
        $response = Http::get("https://rickandmortyapi.com/api/character/{$id}");
        $character = $response->json();
        return view('details', compact('character'));
    }

    public function destroy($id)
    {
        $this->middleware('auth');
        $character = Character::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $character->delete();
        return redirect()->route('characters.index')->with('success', 'Personagem excluído!');
    }
}