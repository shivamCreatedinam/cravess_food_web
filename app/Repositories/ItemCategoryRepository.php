<?php

namespace App\Repositories;

use App\Interfaces\ItemCategoriesInterface;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\UserPanCardVerification;
use App\Traits\ApiResponseTrait;
use App\Traits\ImageUploadTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemCategoryRepository implements ItemCategoriesInterface
{
    use ApiResponseTrait;
    use ImageUploadTrait;

    public function getCategories()
    {
        try {
            $categories = ProductCategory::where("status", 1)->get();
            return $this->successResponse($categories, "Categories Successfully Fetched.");
        } catch (Exception $e) {
            return $this->errorResponse('Failed :' . $e->getMessage());
        }
    }

    public function getSubCategories($cat_id)
    {
        try {
            $categories = ProductSubCategory::where(["sub_status" => 1, "category_id" => $cat_id])->get();
            return $this->successResponse($categories, "Sub Categories Successfully Fetched.");
        } catch (Exception $e) {
            return $this->errorResponse('Failed :' . $e->getMessage());
        }
    }
}
