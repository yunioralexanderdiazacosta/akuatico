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
        $pageSeo["breadcrumb_image"] =
            $pageSeo?->breadcrumb_status == 1
                ? getFile(
                    $pageSeo->breadcrumb_image_driver,
                    $pageSeo->breadcrumb_image,
                )
                : null;

        $pageSeo['page_title'] = ucfirst($routeName);

        if ($routeName === 'clasificado') {
            $availableCategories = ['Botes', 'Jets Skies', 'Veleros', 'Kayaks', 'Juguetes para el Agua'];
        } elseif ($routeName === 'directorio') {
            $availableCategories = ['Piezas', 'Financiamiento', 'Seguros', 'Marinas'];
        } elseif ($routeName === 'servicios') {
            $availableCategories = ['Mantenimiento/Limpieza', 'Mecánica', 'Tapicería', 'Instalaciones'];
        } else {
            $availableCategories = [];
        }

        $categories = ListingCategory::select(
            "id",
            "icon",
            "mobile_app_image",
            "image_driver",
        )
            ->whereHas("details", function ($q) use ($availableCategories) {
                $q->whereIn("name", $availableCategories);
            })
            ->where("status", 1)
            ->latest()
            ->get();
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
