<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MainController extends Controller
{
    private $app_data;

    public function __construct()
    {
        // load appdata file from app folder
        // Estou dizendo que dentro da past app, vou buscar esse arquivo
        $this->app_data = require(app_path('AppData.php'));
    }

    public function startGame(): View {
        return view('Home');
    }

    public function prepareGame(Request $request) {
        $request->validate(
            [
                'total_question' => 'required|integer|min:3|max:30'
            ],
            [
                'total_question.required' => 'O número de questões é obrigatório',
                'total_question.integer' => 'O número de questões tem que ser inteiro',
                'total_question.min' => 'O número de questões é no minimo :min',
                'total_question.max' => 'O número de questões é no máximo :max'
            ]
            );

        //get totalquestions

        $total_questions = intval($request->input('total_questions'));

        //prepare all the quiz structure

        $quiz = $this->prepareQuiz($total_questions);

        dd($quiz);
    }
}
