<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Exception;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;

class ProductSubCategoryController extends Controller
{
    use ImageUploadTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {

            // Page Length
            $pageNumber = ($request->start / $request->length) + 1;
            $pageLength = $request->length;
            $skip = ($pageNumber - 1) * $pageLength;
            $search = $request->search['value'];
            // $order = $request->order[0]['column'];
            $dir = $request->order[0]['dir'];
            // $column = $request->columns[$order]['data'];

            $categories = ProductSubCategory::query()->orderBy('created_at', $dir);

            if ($search) {
                $categories->where(function ($q) use ($search) {
                    $q->orWhere('name', 'like', '%' . $search . '%');
                });
            }
            $total = $categories->count();
            $categories = $categories->skip($skip)->take($pageLength)->get();
            $return = [];
            foreach ($categories as $key => $category) {
                // dd($category->category);
                $action_buttons = "<a href='" . route('subcat_edit_form', ['sub_cat_id' => $category->id]) . "' title='View' class='btn btn-sm btn-primary'>Edit</a>";
                $action_buttons .= "&nbsp;<a href='" . route('subcat_delete', ['sub_cat_id' => $category->id]) . "' title='View' class='btn btn-sm btn-danger'>Delete</a>";

                $image = "<img src='" . $category->sub_icon . "' alt='category image' class='img-fluid' width='64' height='64'>";

                $status = $category->sub_status === 1 ? "<span class='badge text-bg-success'>Active</span>" : "<span class='badge text-bg-danger'>Disable</span>";
                // fetch trade status
                $return[] = [
                    'id' => $key + 1,
                    'icon' => $image,
                    'category' => $category->category?->name ?? null,
                    'sub_cat_name' => $category->sub_cat_name ?? null,
                    'status' => $status,
                    'actions' => $action_buttons,
                ];
            }
            return response()->json([
                'draw' => $request->draw,
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $return,
            ]);
        }
        return view('product_sub_category.index');
    }

    public function addForm()
    {
        $data['categories'] = ProductCategory::where('status', 1)->get();
        return view('product_sub_category.add', $data);
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "category_id" => "required",
            "status" => "required|boolean",
            "icon" => "nullable|mimes:png,jpg,jpeg,gif|max:500",
            "banner_image" => "nullable|mimes:png,jpg,jpeg,gif|max:2048",
        ]);

        // dd($request->all());

        try {
            $icon = null;
            $path = "sub_category_icon";
            if ($request->hasFile("icon")) {
                // if (!is_null($user->icon)) {
                //     $this->deleteImage($user->icon);
                // }
                $icon = $this->uploadImage($request->file('icon'), $path);
            }

            $category_banner = null;
            $path = "sub_category_banner";
            if ($request->hasFile("banner_image")) {
                $category_banner = $this->uploadImage($request->file('banner_image'), $path);
            }

            ProductSubCategory::create([
                "category_id" => $request->category_id,
                "sub_cat_name" => $request->name,
                "sub_status" => $request->status,
                "sub_icon" => $icon,
                "sub_banner_image" => $category_banner,
            ]);

            return redirect()->route('subcategory_list')->with('success', ucfirst($request->name) . " successfully created.");
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function EditPage($cat_id)
    {
        $data['sub_category'] = ProductSubCategory::find($cat_id);
        $data['categories'] = ProductCategory::where("status", 1)->get();
        return view('product_sub_category.edit', $data);
    }

    public function Update(Request $request)
    {
        $request->validate([
            "sub_cat_id" => "required|exists:product_sub_categories,id",
            "category_id" => "required",
            "name" => "required|string",
            "status" => "required|boolean",
            "icon" => "nullable|mimes:png,jpg,jpeg,gif|max:500",
            "banner_image" => "nullable|mimes:png,jpg,jpeg,gif|max:2048",
        ]);

        // dd($request->all());

        try {
            $category = ProductSubCategory::find($request->sub_cat_id);

            $old_icon = null;
            if (!is_null($category->sub_icon)) {
                $old_icon = $this->extactImageOldPath($category->sub_icon, "sub_category_icon");
            }
            $icon = null;
            $path = "sub_category_icon";
            if ($request->hasFile("icon")) {
                if (!is_null($category->sub_icon)) {
                    $this->deleteImage($old_icon);
                }
                $icon = $this->uploadImage($request->file('icon'), $path);
            }

            $old_banner_image = null;
            if (!is_null($category->sub_banner_image)) {
                $old_banner_image = $this->extactImageOldPath($category->sub_banner_image, "sub_category_banner");
            }

            $category_banner = null;
            $path = "sub_category_banner";
            if ($request->hasFile("banner_image")) {
                if (!is_null($category->sub_banner_image)) {
                    $this->deleteImage($old_banner_image);
                }
                $category_banner = $this->uploadImage($request->file('banner_image'), $path);
            }

            $category->update([
                "category_id" => $request->category_id,
                "sub_cat_name" => $request->name,
                "sub_status" => $request->status,
                "sub_icon" => $icon ?? $old_icon,
                "sub_banner_image" => $category_banner ?? $old_banner_image,
            ]);

            return redirect()->route('subcategory_list')->with('success', ucfirst($request->name) . " successfully updated.");
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function delete($cat_id)
    {
        $category = ProductSubCategory::find($cat_id);

        if (!is_null($category->sub_icon)) {
            $old_icon = $this->extactImageOldPath($category->sub_icon, "sub_category_icon");
            if (!is_null($old_icon)) {
                $this->deleteImage($old_icon);
            }
        }

        if (!is_null($category->sub_banner_image)) {
            $old_banner_image = $this->extactImageOldPath($category->sub_banner_image, "sub_category_banner");
            if (!is_null($old_banner_image)) {
                $this->deleteImage($old_banner_image);
            }
        }
        $category->delete();
        return redirect()->route('subcategory_list')->with('success', "Sub Category successfully deleted.");
    }

    private function extactImageOldPath(string $full_path, string $folder)
    {
        return substr(parse_url($full_path, PHP_URL_PATH), strpos(parse_url($full_path, PHP_URL_PATH), $folder));
    }
}
