<?php

namespace App\Traits;

use App\Models\Blog;
use App\Models\ContentDetails;
use App\Models\Country;
use App\Models\Listing;
use App\Models\ListingCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait Frontend
{
    protected function getSectionsData($sections, $content, $selectedTheme)
    {
        if ($sections == null) {
            $data = ["support" => $content];
            return view("themes.$selectedTheme.support", $data)->toHtml();
        }

        $detectedCountryId = session("detected_country_id");
        $detectedCityId = session("detected_city_id");
        $detectedCountryName = session("detected_country_name");
        $detectedCityName = session("detected_city_name");

        if (!$detectedCountryId) {
            $location = getIpInfo();

            Log::info("Buscando localizacion:  ");
            Log::info($location);

            if ($location && isset($location["country"])) {
                $country = Country::where(
                    "name",
                    "LIKE",
                    "%" . $location["country"] . "%",
                )
                    ->where("status", 1)
                    ->first();
                if ($country) {
                    $detectedCountryId = $country->id;
                    $detectedCountryName = $country->name;
                    if (isset($location["city"])) {
                        $city = \App\Models\CountryCities::where(
                            "country_id",
                            $country->id,
                        )
                            ->where(
                                "name",
                                "LIKE",
                                "%" . $location["city"] . "%",
                            )
                            ->first();
                        if ($city) {
                            $detectedCityId = $city->id;
                            $detectedCityName = $city->name;
                        }
                    }
                }
            }
        }

        $contentData = ContentDetails::with("content")
            ->whereHas("content", function ($query) use ($sections) {
                $query->whereIn("name", $sections);
            })
            ->get();

        foreach ($sections as $section) {
            $singleContent =
                $contentData
                    ->where("content.theme", $selectedTheme)
                    ->where("content.name", $section)
                    ->where("content.type", "single")
                    ->first() ?? [];
            if ($section == "blog") {
                $data[$section] = [
                    "single" => $singleContent
                        ? collect($singleContent->description ?? [])->merge(
                            $singleContent->content->only("media"),
                        )
                        : [],
                    "popularBlogs" => Blog::with(["details", "category"])
                        ->where("status", 1)
                        ->take(3)
                        ->latest()
                        ->get(),
                ];
            } elseif ($section == "hero") {
                $listingCategories = ListingCategory::with("details")
                    ->whereNull("parent_id")
                    ->where("status", 1)
                    ->get();
                $data[$section] = [
                    "single" => $singleContent
                        ? collect($singleContent->description ?? [])->merge(
                            $singleContent->content->only("media"),
                        )
                        : [],
                    "all_categories" => $listingCategories->sortBy(function($cat) {
                        return optional($cat->details)->name ?? '';
                    }),
                    "all_places" => Country::select("id", "name")
                        ->where("status", 1)
                        ->orderBy("name", "ASC")
                        ->toBase()
                        ->get(),
                    "uniqueCities" => Listing::with("get_cities")
                        ->where("city_id", "!=", null)
                        ->get()
                        ->pluck("get_cities"),
                    "highlights_categories" => $listingCategories->random(4),
                    "detected_country_id" => $detectedCountryId,
                    "detected_country_name" => $detectedCountryName,
                    "detected_city_id" => $detectedCityId,
                    "detected_city_name" => $detectedCityName,
                ];
            } elseif ($section == "listing") {
                $popularListingsQuery = Listing::with([
                    "get_reviews",
                    "get_user",
                ])
                    ->where("status", 1)
                    ->where("is_active", 1)
                    ->where("is_popular", 1)
                    ->withCount("getFavourite");

                if ($detectedCityId) {
                    $popularListingsQuery->orderByRaw(
                        "CASE WHEN city_id = ? THEN 0 ELSE 1 END",
                        [$detectedCityId],
                    );
                }
                if ($detectedCountryId) {
                    $popularListingsQuery->orderByRaw(
                        "CASE WHEN country_id = ? THEN 0 ELSE 1 END",
                        [$detectedCountryId],
                    );
                }

                $popularListings = $popularListingsQuery
                    ->inRandomOrder()
                    ->get()
                    ->sort(function ($a, $b) use (
                        $detectedCityId,
                        $detectedCountryId,
                    ) {
                        if ($detectedCityId) {
                            if (
                                $a->city_id == $detectedCityId &&
                                $b->city_id != $detectedCityId
                            ) {
                                return -1;
                            }
                            if (
                                $a->city_id != $detectedCityId &&
                                $b->city_id == $detectedCityId
                            ) {
                                return 1;
                            }
                        }
                        if ($detectedCountryId) {
                            if (
                                $a->country_id == $detectedCountryId &&
                                $b->country_id != $detectedCountryId
                            ) {
                                return -1;
                            }
                            if (
                                $a->country_id != $detectedCountryId &&
                                $b->country_id == $detectedCountryId
                            ) {
                                return 1;
                            }
                        }
                        return $b->avgRating <=> $a->avgRating;
                    })
                    ->take(4);

                $data[$section] = [
                    "single" => $singleContent
                        ? collect($singleContent->description ?? [])->merge(
                            $singleContent->content->only("media"),
                        )
                        : [],
                    "popularListings" => $popularListings,
                ];
            } elseif ($section == "nearby_listings") {
                $nearbyListingsQuery = Listing::with([
                    "get_reviews",
                    "get_user",
                ])
                    ->where("status", 1)
                    ->where("is_active", 1)
                    ->withCount("getFavourite");

                if ($detectedCityId) {
                    $nearbyListingsQuery->orderByRaw(
                        "CASE WHEN city_id = ? THEN 0 ELSE 1 END",
                        [$detectedCityId],
                    );
                }
                if ($detectedCountryId) {
                    $nearbyListingsQuery->orderByRaw(
                        "CASE WHEN country_id = ? THEN 0 ELSE 1 END",
                        [$detectedCountryId],
                    );
                }

                $nearbyListings = $nearbyListingsQuery
                    ->inRandomOrder()
                    ->get()
                    ->sort(function ($a, $b) use (
                        $detectedCityId,
                        $detectedCountryId,
                    ) {
                        if ($detectedCityId) {
                            if (
                                $a->city_id == $detectedCityId &&
                                $b->city_id != $detectedCityId
                            ) {
                                return -1;
                            }
                            if (
                                $a->city_id != $detectedCityId &&
                                $b->city_id == $detectedCityId
                            ) {
                                return 1;
                            }
                        }
                        if ($detectedCountryId) {
                            if (
                                $a->country_id == $detectedCountryId &&
                                $b->country_id != $detectedCountryId
                            ) {
                                return -1;
                            }
                            if (
                                $a->country_id != $detectedCountryId &&
                                $b->country_id == $detectedCountryId
                            ) {
                                return 1;
                            }
                        }
                        return $b->created_at <=> $a->created_at;
                    })
                    ->take(8);

                $data[$section] = [
                    "single" => $singleContent
                        ? collect($singleContent->description ?? [])->merge(
                            $singleContent->content->only("media"),
                        )
                        : [],
                    "nearbyListings" => $nearbyListings,
                    "detectedCityName" => $detectedCityName,
                    "detectedCountryName" => $detectedCountryName,
                ];
            } elseif ($section == "listing_categories") {
                $categoryIds = array_count_values(
                    Listing::where("status", 1)
                        ->where("is_active", 1)
                        ->pluck("category_id")
                        ->flatten()
                        ->toArray(),
                );
                arsort($categoryIds);
                $sliceCategoryIds = array_keys(
                    array_slice($categoryIds, 0, 8, true),
                );

                if (!empty($sliceCategoryIds)) {
                    $popularCategories = ListingCategory::with("details")
                        ->whereIn("id", $sliceCategoryIds)
                        ->whereNull("parent_id")
                        ->orderByRaw(
                            "FIELD(id, " .
                                implode(",", $sliceCategoryIds) .
                                ")",
                        )
                        ->get();
                } else {
                    $popularCategories = collect(); // Return an empty collection
                }
                $data[$section] = [
                    "single" => $singleContent
                        ? collect($singleContent->description ?? [])->merge(
                            $singleContent->content->only("media"),
                        )
                        : [],

                    "popularCategories" => $popularCategories->sortBy(function($cat) {
                        return optional($cat->details)->name ?? '';
                    }),
                ];
            } else {
                $multipleContents = $contentData
                    ->where("content.theme", $selectedTheme)
                    ->where("content.name", $section)
                    ->where("content.type", "multiple")
                    ->values()
                    ->map(function ($multipleContentData) {
                        return collect(
                            $multipleContentData->description,
                        )->merge($multipleContentData->content->only("media"));
                    });

                $data[$section] = [
                    "single" => $singleContent
                        ? collect($singleContent->description ?? [])->merge(
                            $singleContent->content->only("media"),
                        )
                        : [],
                    "multiple" => $multipleContents,
                ];
            }

            $replacement = view(
                "themes.{$selectedTheme}.sections.{$section}",
                $data,
            )->toHtml();

            $content = str_replace(
                '<div class="custom-block" contenteditable="false"><div class="custom-block-content">[[' .
                    $section .
                    "]]</div>",
                $replacement,
                $content,
            );
            $content = str_replace(
                '<span class="delete-block">×</span>',
                "",
                $content,
            );
            $content = str_replace(
                '<span class="up-block">↑</span>',
                "",
                $content,
            );
            $content = str_replace(
                '<span class="down-block">↓</span></div>',
                "",
                $content,
            );
            $content = str_replace("<p><br></p>", "", $content);
        }

        return $content;
    }
}
