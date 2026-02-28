<?php

namespace App\Services;

use App\Models\JobModel;
use App\Models\Categories;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Log;

class JobService
{
    /**
     * Lấy tất cả jobs với relationships
     */
    public function getAllJobsWithRelations()
    {
        return JobModel::with(['roleCategory', 'positionCategory', 'locationCategory'])->get();
    }

    /**
     * Tìm kiếm jobs theo category name
     */
    public function searchJobsByCategoryName($query)
    {
        if (empty($query)) {
            return collect();
        }

        return JobModel::join('categories', 'jobs.role_category_id', '=', 'categories.category_id')
            ->where('categories.category_name', 'LIKE', "%{$query}%")
            ->select('jobs.*')
            ->distinct()
            ->with('roleCategory')
            ->get();
    }

    /**
     * Lấy jobs theo category_id
     */
    public function getJobsByCategoryId($categoryId)
    {
        return JobModel::with(['roleCategory', 'positionCategory', 'locationCategory'])
            ->where(function ($query) use ($categoryId) {
                $query->whereHas('roleCategory', function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                })
                ->orWhereHas('positionCategory', function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                })
                ->orWhereHas('locationCategory', function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            })
            ->get();
    }

    /**
     * Lấy job theo ID (trả về collection để tương thích với view)
     */
    public function getJobById($jobId)
    {
        return JobModel::where('jobs.job_id', $jobId)->get();
    }

    /**
     * Lấy parent categories theo IDs
     */
    public function getParentCategories(array $parentIds)
    {
        return ParentModel::whereIn('parent_id', $parentIds)->get();
    }

    /**
     * Lấy categories theo parent_id
     */
    public function getCategoriesByParentId($parentId)
    {
        return Categories::where('parent_id', $parentId)->get();
    }

    /**
     * Lấy tất cả categories theo nhiều parent_ids
     */
    public function getCategoriesByParentIds(array $parentIds)
    {
        $parentCategories = [];
        
        foreach ($parentIds as $parentId) {
            $parent = ParentModel::find($parentId);
            if ($parent) {
                $parentCategories[$parentId] = $this->getCategoriesByParentId($parentId);
            }
        }
        
        return $parentCategories;
    }
}

