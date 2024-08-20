<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interfaces\ItemCategoriesInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class RestaurantCategoriesController extends Controller
{
    use ApiResponseTrait;
    public function __construct(private ItemCategoriesInterface $itemCategoriesInterface) {}

    /**
     * @OA\Get(
     *     path="/store/get-categories",
     *     tags={"Product Categories API"},
     *     summary="Get list of all categories",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items())
     *     )
     * )
     */
    public function getCategories()
    {
        return $this->itemCategoriesInterface->getCategories();
    }

    /**
     * @OA\Get(
     *     path="/store/get-subcategory/{cat_id}",
     *     tags={"Product Categories API"},
     *     summary="Get subcategories by category ID",
     *     @OA\Parameter(
     *         name="cat_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="Category ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items())
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category ID not found"
     *     )
     * )
     */
    public function getSubCategories($cat_id)
    {
        if (!empty($cat_id)) {

            return $this->itemCategoriesInterface->getSubCategories($cat_id);
        } else {
            return  $this->errorResponse('Failed : Category ID not found.');
        }
    }
}
