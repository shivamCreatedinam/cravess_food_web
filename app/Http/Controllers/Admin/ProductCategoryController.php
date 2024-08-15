<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Traits\ImageUploadTrait;
use Exception;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    use ImageUploadTrait;
    public function __construct() {}

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

            $categories = ProductCategory::query()->orderBy('created_at', $dir);

            if ($search) {
                $categories->where(function ($q) use ($search) {
                    $q->orWhere('name', 'like', '%' . $search . '%');
                });
            }
            $total = $categories->count();
            $categories = $categories->skip($skip)->take($pageLength)->get();
            $return = [];
            foreach ($categories as $key => $category) {

                $action_buttons = "<a href='" . route('cat_edit_form', ['category_id' => $category->id]) . "' title='View' class='btn btn-sm btn-primary'>Edit</a>";
                $action_buttons .= "&nbsp;<a href='" . route('cat_delete', ['category_id' => $category->id]) . "' title='View' class='btn btn-sm btn-danger'>Delete</a>";

                $image = "<img src='" . $category->icon . "' alt='category image' class='img-fluid' width='64' height='64'>";

                $status = $category->status === 1 ? "<span class='badge text-bg-success'>Active</span>" : "<span class='badge text-bg-danger'>Disable</span>";
                // fetch trade status
                $return[] = [
                    'id' => $key + 1,
                    'icon' => $image,
                    'name' => $category->name,
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
        return view('product_category.index');
    }

    public function addForm()
    {
        return view('product_category.add');
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "status" => "required|boolean",
            "icon" => "nullable|mimes:png,jpg,jpeg,gif|max:500",
            "banner_image" => "nullable|mimes:png,jpg,jpeg,gif|max:2048",
        ]);

        // dd($request->all());

        try {
            $icon = null;
            $path = "category_icon";
            if ($request->hasFile("icon")) {
                // if (!is_null($user->icon)) {
                //     $this->deleteImage($user->icon);
                // }
                $icon = $this->uploadImage($request->file('icon'), $path);
            }

            $category_banner = null;
            $path = "category_banner";
            if ($request->hasFile("banner_image")) {
                $category_banner = $this->uploadImage($request->file('banner_image'), $path);
            }

            ProductCategory::create([
                "name" => $request->name,
                "status" => $request->status,
                "icon" => $icon,
                "banner_image" => $category_banner,
            ]);

            return redirect()->route('category_list')->with('success', ucfirst($request->name) . " successfully created.");
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function EditPage($cat_id)
    {
        $data['category'] = ProductCategory::find($cat_id);
        return view('product_category.edit', $data);
    }

    public function Update(Request $request)
    {
        $request->validate([
            "cat_id" => "required|exists:product_categories,id",
            "name" => "required|string",
            "status" => "required|boolean",
            "icon" => "nullable|mimes:png,jpg,jpeg,gif|max:500",
            "banner_image" => "nullable|mimes:png,jpg,jpeg,gif|max:2048",
        ]);

        // dd($request->all());

        try {
            $category = ProductCategory::find($request->cat_id);

            $old_icon = null;
            if (!is_null($category->icon)) {
                $old_icon = $this->extactImageOldPath($category->icon, "category_icon");
            }
            $icon = null;
            $path = "category_icon";
            if ($request->hasFile("icon")) {
                if (!is_null($category->icon)) {
                    $this->deleteImage($old_icon);
                }
                $icon = $this->uploadImage($request->file('icon'), $path);
            }

            $old_banner_image = null;
            if (!is_null($category->banner_image)) {
                $old_banner_image = $this->extactImageOldPath($category->banner_image, "category_banner");
            }

            $category_banner = null;
            $path = "category_banner";
            if ($request->hasFile("banner_image")) {
                if (!is_null($category->banner_image)) {
                    $this->deleteImage($old_banner_image);
                }
                $category_banner = $this->uploadImage($request->file('banner_image'), $path);
            }

            $category->update([
                "name" => $request->name,
                "status" => $request->status,
                "icon" => $icon ?? $old_icon,
                "banner_image" => $category_banner ?? $old_banner_image,
            ]);

            return redirect()->route('category_list')->with('success', ucfirst($request->name) . " successfully updated.");
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function delete($cat_id)
    {
        $category = ProductCategory::find($cat_id);

        if (!is_null($category->icon)) {
            $old_icon = $this->extactImageOldPath($category->icon, "category_icon");
            if (!is_null($old_icon)) {
                $this->deleteImage($old_icon);
            }
        }

        if (!is_null($category->banner_image)) {
            $old_banner_image = $this->extactImageOldPath($category->banner_image, "category_banner");
            if (!is_null($old_banner_image)) {
                $this->deleteImage($old_banner_image);
            }
        }
        $category->delete();
        return redirect()->route('category_list')->with('success', "Category successfully deleted.");
    }

    private function extactImageOldPath(string $full_path, string $folder)
    {
        return substr(parse_url($full_path, PHP_URL_PATH), strpos(parse_url($full_path, PHP_URL_PATH), $folder));
    }
}
