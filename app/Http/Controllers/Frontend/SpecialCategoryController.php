<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContentDetails;
use App\Models\ListingCategory;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpecialCategoryController extends Controller
{
    public function __construct()
    {
        $this->theme = template();
    }

    public function index(Request $request)
    {
        $selectedTheme = getTheme();
        $routeName = $request->route()->getName();
        $pageSeo = Page::where("template_name", $selectedTheme)
            ->where("slug", "category")
            ->first();
        /* $pageSeo["breadcrumb_image"] =
            $pageSeo?->breadcrumb_status == 1
            ? getFile(
                $pageSeo->breadcrumb_image_driver,
                $pageSeo->breadcrumb_image,
            )
            : null; */
        $pageSeo["breadcrumb_image"] = 'assets/admin/img/default2.webp';

        $pageSeo['page_title'] = ucfirst($routeName);

        if ($routeName === 'anuncios') {
            $availableCategories = ['Botes', 'Jets Skies', 'Veleros', 'Kayaks', 'Juguetes para el Agua'];
        } elseif ($routeName === 'directorio') {
            $availableCategories = ['Piezas', 'Financiamiento', 'Seguros', 'Marinas'];
        } elseif ($routeName === 'servicios') {
            $availableCategories = ['Mantenimiento', 'Mecánica', 'Tapicería', 'Instalaciones'];
        } else {
            $availableCategories = [];
        }

        $categories = ListingCategory::select(
            "id",
            "icon",
            "mobile_app_image",
            "image_driver",
        )
            ->with("details:id,listing_category_id,name")
            ->whereHas("details", function ($q) use ($availableCategories) {
                $q->whereIn("name", $availableCategories);
            })
            ->where("status", 1)
            ->get()
            ->sortBy(function ($cat) {
                return optional($cat->details)->name ?? '';
            });
        $categorySingle = DB::table("content_details")
            ->join("contents", "contents.id", "=", "content_details.content_id")
            ->where("contents.theme", $selectedTheme)
            ->where("contents.name", "listing_categories")
            ->where("contents.type", "single")
            ->first();
        return view(
            template() . "frontend.category.specialcategories",
            compact("pageSeo", "categories", "categorySingle", "routeName"),
        );
    }
}
