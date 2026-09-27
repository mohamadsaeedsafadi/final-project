<?php
namespace App\Repositories;

use App\Models\ServiceQuestion;

class QuestionRepository
{
    public function create(array $data)
    {
        return ServiceQuestion::create($data);
    }
}