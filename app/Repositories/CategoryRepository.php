<?php
namespace App\Repositories;

use App\Models\ServiceCategory;

class CategoryRepository
{
    public function create(array $data)
    {
        return ServiceCategory::create($data);
    }

    public function getMainWithChildren()
    {
        return ServiceCategory::with('children')
            ->whereNull('parent_id')
            ->get();
    }

    public function findWithQuestions($id)
    {
        return ServiceCategory::with('questions')
            ->findOrFail($id);
    }
}