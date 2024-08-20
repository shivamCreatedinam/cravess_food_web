<?php
namespace App\Interfaces;

interface ItemCategoriesInterface extends BaseInterface
{
    public function getCategories();
    public function getSubCategories($cat_id);
}
