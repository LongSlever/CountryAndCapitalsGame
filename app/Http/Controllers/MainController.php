<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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
                'total_questions' => 'required|integer|min:3|max:30'
            ],
            [
                'total_questions.required' => 'O número de questões é obrigatório',
                'total_questions.integer' => 'O número de questões tem que ser inteiro',
                'total_questions.min' => 'O número de questões é no minimo :min',
                'total_questions.max' => 'O número de questões é no máximo :max'
            ]
            );

        //get totalquestions

        $total_questions = intval($request->input('total_questions'));

        //prepare all the quiz structure

        $quiz = $this->prepareQuiz($total_questions);

        //store the quiz in the session.

        session()->put([
            'quiz' => $quiz,
            'total_questions' => $total_questions,
            'current_question' => 1,
            'correct_answers' => 0,
            'wrong_answers' => 0
        ]);

        return redirect()->route('game');

        dd($quiz);
    }

    public function game(): View {
        $quiz = session('quiz');
        $total_questions = session('total_questions');
        $current_questions = session('current_question') - 1;

        // prepare answers to show in view

        $answers = $quiz[
            $current_questions
        ]['wrong_answers'];
        $answers[] = $quiz[
            $current_questions
        ]['correct_answer'];

        shuffle($answers);

        return view('game')->with(
            [
                'country' =>$quiz[
                    $current_questions
                ]['country'],
                'totalQuestions' => $total_questions,
                'currentQuestion' => $current_questions,
                'answers' => $answers
                ]
            );
    }

    private function prepareQUiz($total_questions) {

        $questions = [];
        $total_countries = count($this->app_data);

        //create countries index for unique questions

        $indexes = range(0, $total_countries -1);
        shuffle($indexes);
        $indexes = array_slice($indexes, 0, $total_questions);
        $question_number = 1;
        foreach($indexes as $index) {
            $question['question_number'] = $question_number++;
            $question['country'] = $this->app_data[$index]['country'];
            $question['correct_answer'] = $this->app_data[$index]['capital'];

            // wrong answers

            $other_capitals = array_column($this->app_data, 'capital');

            // remove correct answer
            $other_capitals = array_diff($other_capitals, [$question['correct_answer']]);

            shuffle($other_capitals);
            $question['wrong_answers'] = array_slice($other_capitals, 0, 3);


            // Store Answer Result

            $question['correct'] = null;

            $questions[] = $question;
        }
        return $questions;

    }

    public function answer($enc_answer) {

        try {
            $answer = Crypt::decryptString($enc_answer);
        } catch (\Exception $e) {
            return redirect()->route('game');
        }

        // game logic

        $quiz = session('quiz');
        $current_question = session('current_question') - 1;
        $correct_answer = $quiz[$current_question]['correct_answer'];
        $correct_answers = session('correct_answers');
        $wrong_answers = session('wrong_answers');

        if($answer == $correct_answer) {
            $correct_answers++;
            $quiz[$current_question]['correct'] = true;
        } else {
            $wrong_answers++;
            $quiz[$current_question]['correct'] = false;
        }

        //update session

        session()->put([
            'quiz' => $quiz,
            'correct_answers' => $correct_answers,
            'wrong_answers' => $wrong_answers,
        ]);


        // prepare data to show the correct answer

        $data = [
            'country' => $quiz[$current_question]['country'],
            'correct_answer' => $correct_answer,
            'choice_answer' => $answer,
            'currentQuestion' => $current_question,
            'totalQuestions' => session('total_questions')
        ];

        return view('answer_result')->with($data);

    }

    public function next_question() {
        $current_question = session('current_question');
        $total_questions = session('total_questions');


        // check if the gamei sover

        if($current_question < $total_questions) {
            $current_question++;
            session()->put('current_question', $current_question);
            return redirect()->route('game');
        } else {
            return redirect()->route('show_results');
        }
    }

    public function showResults() {
        $total_questions = session('total_quesitons');

        $correct_answers = session('correct_answers');

        return view('final_results')->with([
            'total_questions' => session('total_questions'),
            'wrong_answers' => session('wrong_answers'),
            'correct_answers' => session('correct_answers'),
            'percentage' => round(session('correct_answers') / session('total_questions')*100)
        ]);
    }
}
