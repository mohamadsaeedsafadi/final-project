<?php
namespace App\Repositories;

use App\Models\ServiceRequest;

class ServiceRequestRepository
{
    public function create(array $data)
    {
        return ServiceRequest::create($data);
    }
   public function getRequestsForProvider($provider)
{
    $categoryIds = $provider->categories->pluck('id');

    $requests = ServiceRequest::with([
            'category.questions',
            'user:id,name,email'
        ])
        ->whereIn('category_id', $categoryIds)
        ->where('status', 'pending')
        ->latest()
        ->paginate(10);

    $requests->getCollection()->transform(function ($request) {

        $questions = $request->category->questions;

        $formattedAnswers = [];

        foreach ($questions as $index => $question) {

            $formattedAnswers[] = [

                'question_id' => $question->id,

                'question' => $question->question,

                'type' => $question->type,

                'answer' => $request->answers[$index] ?? null,
            ];
        }

        return [

            'id' => $request->id,

            'status' => $request->status,

            'created_at' => $request->created_at,

            /*
            |--------------------------------------------------
            | Category
            |--------------------------------------------------
            */

            'category' => [

                'id' => $request->category->id,

                'name' => $request->category->name,
            ],

            /*
            |--------------------------------------------------
            | User
            |--------------------------------------------------
            */

            'user' => [

                'id' => $request->user->id,

                'name' => $request->user->name,

                'email' => $request->user->email,
            ],

            /*
            |--------------------------------------------------
            | Questions + Answers
            |--------------------------------------------------
            */

            'answers' => $formattedAnswers,
        ];
    });

    return $requests;
}
}