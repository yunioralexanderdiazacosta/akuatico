<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ListingCategory extends Model
{
    use HasFactory;
    protected $table = "listing_categories";
    protected $guarded = ["id"];

    public function language()
    {
        return $this->hasMany(Language::class, "language_id", "id");
    }

    public function details()
    {
        return $this->hasOne(ListingCategoryDetails::class);
    }

    public function subcategories()
    {
        return $this->hasMany(ListingCategory::class, "parent_id");
    }

    public function parent()
    {
        return $this->belongsTo(ListingCategory::class, "parent_id");
    }

    public function scopeOnlyParent($query)
    {
        return $query->whereNull("parent_id");
    }

    public function scopeOnlySubcategories($query)
    {
        return $query->whereNotNull("parent_id");
    }

    public function get_listings()
    {
        return $this->hasMany(Listing::class, "category_id", "id")
            ->where("status", 1)
            ->where("is_active", 1);
    }

    public function getCategoryCount()
    {
        $today = date('Y-m-d');

        return Listing::where("status", 1)
            ->whereHas('get_package', function ($query) use ($today) {
                return $query->where('expire_date', '>=', $today)->orWhereNull('expire_date');
            })
            ->where("is_active", 1)
            ->where(function ($query) {
                $query
                    ->whereJsonContains("category_id", json_encode($this->id))
                    ->orWhereJsonContains(
                        "subcategory_id",
                        json_encode($this->id),
                    );
            })
            ->count();
    }
}
