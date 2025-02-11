<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        if (!session()->has('randomNumber')) {
            session([
                'randomNumber' => $this->generateUniqueNumber(),
                'attempts' => 5,
                "historique" =>[]

            ]);
        }

        return view('guess');
    }
    public function makeGuess(Request $request)
    {
        $request->validate([
            'guess' => 'required|digits:4'
        ]);
        $randomNumber = session('randomNumber');
        $guess = $request->input('guess');
        $attempts = session('attempts');
        $histo=session('historique');

        
        if ($guess === $randomNumber) {
            session()->forget(['randomNumber', 'attempts']);
            return back()->with('success', "Congratulations! You guessed the number: $randomNumber.")->with("histo",$histo)->with('gameOver', true);
        }

        
        session(['attempts' => $attempts - 1]);


        
        [$correctPlace, $wrongPlace] = $this->evaluateGuess($randomNumber, $guess);
        $histo[] = "The guess: $guess, Correct digits: $correctPlace, Wrong digits: $wrongPlace"; 
        session(['historique' => $histo]);
        if ($attempts - 1 <= 0) {
            session()->forget(['randomNumber', 'attempts']);
            return back()->with('error', 'salw lik lmo7awalt! The correct number was: ' . $randomNumber )->with("histo",$histo)->with('gameOver', true);
        }
        return back()->with('error', "Incorrect guess. Digits in the correct place: $correctPlace.
         Digits in the wrong place: $wrongPlace.
          ila bghiti tgache: $randomNumber. lmo7awalt liba9in: " . ($attempts - 1))->with("histo",$histo);
    }

    private function generateUniqueNumber()
    {
        $digits = range(0, 9);
        shuffle($digits);
        return implode('', array_slice($digits, 0, 4));
    }

    private function evaluateGuess($randomNumber, $guess)
    {
        $correctPlace = 0;
        $wrongPlace = 0;
        $usedRandom = [];
        $usedGuess = [];
        for ($i = 0; $i < 4; $i++) {
            if ($randomNumber[$i] === $guess[$i]) {
                $correctPlace++;
                $usedRandom[] = $i;
                $usedGuess[] = $i;
            }
        }
        for ($i = 0; $i < 4; $i++) {
            if (!in_array($i, $usedGuess)) {
                for ($j = 0; $j < 4; $j++) {
                    if (!in_array($j, $usedRandom) && $randomNumber[$j] === $guess[$i]) {
                        $wrongPlace++;
                        $usedRandom[] = $j;
                        break;
                    }
                }
            }
        }

        return [$correctPlace, $wrongPlace];
    }
}
