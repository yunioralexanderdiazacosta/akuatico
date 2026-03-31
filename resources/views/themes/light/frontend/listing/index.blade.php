@extends(template().'layouts.app')
@section('title',trans('Listing'))

@section('banner_heading')
    @lang('All Listings')
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/frontend_leaflet.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/global/css/frontendControl.FullScreen.css') }}"/>
@endpush

@section('content')
    <section class="listing-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-2 col-lg-2 col-sm-12 my-4">
                    <form action="{{ route('listings') }}" method="get">
                        <div class="filter-area">
                            <div class="filter-box">
                                <h5>@lang('search')</h5>
                                <div class="input-group mb-3">
                                    <input type="text" name="name" class="form-control bg-white"
                                           value="{{ old('name', request()->name) }}" autocomplete="off"
                                           placeholder="@lang('Name')"/>
                                </div>
                                <div class="input-group mb-3">
                                    <select class="js-example-basic-single form-control" name="location">
                                        <option selected disabled>@lang('Country')</option>
                                        <option value="all"
                                                @if(request()->location == 'all') selected @endif>@lang('All Country')
                                        </option>
                                        @foreach($all_places as $place)
                                            @if($place != null)
                                            <option class="m-0" value="{{ $place->id }}"
                                                    @if(request()->location == $place->id) selected @endif>@lang($place->name)
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group mb-3">
                                    <select class="js-example-basic-single form-control" name="city">
                                        <option selected disabled>@lang('City')</option>
                                        <option value="all"
                                                @if(request()->city == 'all') selected @endif>@lang('All City')
                                        </option>
                                        @foreach($uniqueCities as $city)
                                            @if($city != null)
                                            <option class="m-0" value="{{ $city->id }}"
                                                    @if(request()->city == $city->id) selected @endif>@lang($city->name)
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group mb-3">
                                    <select id="category_id" class="listing__category__select2 form-control" name="category[]" multiple>
                                        <option value="all"
                                                @if(request()->category && in_array('all', request()->category))
                                                    selected
                                                @endif>@lang('All Category')</option>
                                        @foreach($all_categories as $category)
                                            @if($category != null)
                                            <option value="{{ $category->id }}"
                                                 @if(request()->category && in_array($category->id, request()->category))
                                                        selected
                                                @endif> @lang(optional($category->details)->name)
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group mb-3">
                                    <select id="subcategory_id" class="listing__subcategory__select2 form-control" name="subcategory[]" multiple>
                                        <option value="all"
                                                @if(request()->subcategory && in_array('all', request()->subcategory))
                                                    selected
                                                @endif>@lang('All Subcategory')</option>
                                        @foreach($all_subcategories as $subcategory)
                                            @if($subcategory != null)
                                            <option value="{{ $subcategory->id }}"
                                                    data-parent="{{ $subcategory->parent_id }}"
                                                 @if(request()->subcategory && in_array($subcategory->id, request()->subcategory))
                                                        selected
                                                @endif> @lang(optional($subcategory->details)->name)
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">@lang('Length (Feet)')</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" name="min_length" class="form-control bg-white"
                                                   value="{{ request()->min_length }}" min="10" max="100"
                                                   placeholder="@lang('Min')"/>
                                        </div>
                                        <div class="col-6">
                                            <input type="number" name="max_length" class="form-control bg-white"
                                                   value="{{ request()->max_length }}" min="10" max="100"
                                                   placeholder="@lang('Max')"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">@lang('Price ($)')</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" name="min_price" class="form-control bg-white"
                                                   value="{{ request()->min_price }}" min="0"
                                                   placeholder="@lang('Min')"/>
                                        </div>
                                        <div class="col-6">
                                            <input type="number" name="max_price" class="form-control bg-white"
                                                   value="{{ request()->max_price }}" min="0"
                                                   placeholder="@lang('Max')"/>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="filter-box">
                                <h5>@lang('Filter by Ratings') </h5>
                                <div class="check-box">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="5" name="rating[]"
                                               id="check1"
                                               @if(isset(request()->rating))
                                               @foreach(request()->rating as $data)
                                               @if($data == 5) checked @endif
                                            @endforeach
                                            @endif/>

                                        <label class="form-check-label" for="check1">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="4" name="rating[]"
                                               id="check2"
                                               @if(isset(request()->rating))
                                               @foreach(request()->rating as $data)
                                               @if($data == 4) checked @else @endif
                                            @endforeach
                                            @endif/>

                                        <label class="form-check-label" for="check2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="3" name="rating[]"
                                               id="check3"
                                               @if(isset(request()->rating))
                                               @foreach(request()->rating as $data)
                                               @if($data == 3) checked @endif
                                            @endforeach
                                            @endif/>
                                        <label class="form-check-label" for="check3">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="2" name="rating[]"
                                               id="check4"
                                               @if(isset(request()->rating))
                                               @foreach(request()->rating as $data)
                                               @if($data == 2) checked @endif
                                            @endforeach
                                            @endif/>
                                        <label class="form-check-label" for="check4">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="rating[]"
                                               id="check5"
                                               @if(isset(request()->rating))
                                               @foreach(request()->rating as $data)
                                               @if($data == 1) checked @endif
                                            @endforeach
                                            @endif/>
                                        <label class="form-check-label" for="check5">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <button class="btn-custom w-100" type="submit">@lang('submit')</button>
                        </div>
                    </form>
                </div>


                <div class="col-xl-6 col-lg-6 col-sm-12 my-4">
                    @if( 0 <count($all_listings))
                        <div class="row mb-4">
                            <div class="col-12 justify-content-end d-flex">
                                <div id="results-count" data-total="{{ $all_listings->total() }}" data-current-page="{{ $all_listings->currentPage() }}">
                                    {{--Showing results here--}}
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="googleMapAppKey" value="{{ basicControl()->google_map_app_key }}">
                        <input type="hidden" id="google_map_id" value="{{ basicControl()->google_map_id }}">
                        <div class="row g-4">
                            @forelse($all_listings as $key => $listing)
                                @php
                                    $total = $listing->reviews()[0]->total;
                                    $average_review = $listing->reviews()[0]->average;
                                @endphp
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="listing-box listing-map-box" data-lat="{{ $listing->lat }}"
                                         data-long="{{ $listing->long }}"
                                         data-title="@lang( Str::limit($listing->title, 30))"
                                         data-image="{{ getFile($listing->thumbnail_driver, $listing->thumbnail) }}"
                                         data-location="@lang($listing->address . ($listing->city_id != null ? ', '.$listing->get_cities?->getAddress() : ''))"
                                         data-route="{{ route('listing.details', $listing->slug) }}">
                                        <div class="img-box">
                                            <a href="{{ route('listing.details', $listing->slug) }}">
                                                <img class="img-fluid" style="object-fit: cover;"
                                                     src="{{ getFile($listing->thumbnail_driver, $listing->thumbnail) }}"
                                                     alt="{{ basicControl()->site_title }}"/>
                                            </a>
                                        </div>

                                        <div class="text-box">
                                            <div class="review">
                                                @php
                                                    $isCheck = 0;
                                                    $j = 0;
                                                @endphp
                                                @if($average_review != intval($average_review))
                                                    @php
                                                        $isCheck = 1;
                                                    @endphp
                                                @endif

                                                @for($i = $average_review; $i > $isCheck; $i--)
                                                    <i class="fas fa-star" aria-hidden="true"></i>
                                                    @php
                                                        $j = $j + 1;
                                                    @endphp
                                                @endfor

                                                @if($average_review != intval($average_review))
                                                    <i class="fas fa-star-half-alt"></i>
                                                    @php
                                                        $j = $j + 1;
                                                    @endphp
                                                @endif

                                                @if($average_review == 0 || $average_review != null)
                                                    @for($j; $j < 5; $j++)
                                                        <i class="far fa-star"></i>
                                                    @endfor
                                                @endif
                                                <span>( @lang($total.' reviews') )</span>
                                            </div>

                                            <h5 class="title"><a href="{{ route('listing.details', $listing->slug) }}">@lang(Str::limit($listing->title, 30))</a></h5>
                                            <!-- <a class="author"
                                               href="{{ route('profile', optional($listing->get_user)->username) }}">
                                                @lang(optional($listing->get_user)->firstname) @lang(optional($listing->get_user)->lastname)
                                            </a> -->
                                            <p class="mb-2">
                                                <span class="">@lang('Category'): </span> @lang(optional($listing)->getCategoriesName())
                                            </p>
                                            @if($listing->getSubCategoriesName())
                                                <p class="mb-2">
                                                    <span class="">@lang('Subcategory'): </span> @lang($listing->getSubCategoriesName())
                                                </p>
                                            @endif
                                            @if($listing->length)
                                                <p class="mb-2">
                                                    <span class="">@lang('Length'): </span> {{ $listing->length }} @lang('Feet')
                                                </p>
                                            @endif
                                            @if($listing->price)
                                                <p class="mb-2">
                                                    <span class="">@lang('Price'): </span> ${{ $listing->price }}
                                                </p>
                                            @endif
                                            <a style="color: var(--primary);"
                                               href="{{ route('profile', optional($listing->get_user)->username) }}">
                                                @lang(optional($listing->get_user)->firstname) @lang(optional($listing->get_user)->lastname)
                                            </a>
                                            <p class="address mb-1">
                                                <i class="fal fa-map-marker-alt"></i>
                                                @lang($listing->city_id != null ? $listing->get_cities?->getAddress() : $listing->address)
                                            </p>
                                            <a href="{{ route('listing.details', $listing->slug) }}"
                                               class="btn-custom">@lang('View details')</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="custom-not-found">
                                    <img src="{{ asset(template(true).'img/no_data_found.png') }}"
                                         alt="{{ basicControl()->site_title }}" class="img-fluid">
                                </div>
                            @endforelse


                            <div class="col-lg-12 d-flex justify-content-center mt-5">
                                <nav aria-label="Page navigation example mt-3">
                                    {{ $all_listings->appends($_GET)->links(template().'partials.pagination') }}
                                </nav>
                            </div>
                        </div>
                    @else
                        <div class="custom-not-found">
                            <img src="{{ asset(template(true).'img/no_data_found.png') }}" alt="image"
                                 class="img-fluid">
                        </div>
                    @endif
                </div>

                <div class="col-xl-4 col-lg-4 col-sm-12">
                    <div class="h-100" id="map"></div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('script')
    @if(basicControl()->is_google_map == 1)
        <script src="https://maps.googleapis.com/maps/api/js?key={{ basicControl()->google_map_app_key }}&loading=async&callback=initMap&libraries=marker" defer></script>
        <script src="{{ asset('assets/global/js/frontend_google_map.js') }}"></script>
    @else
        <script src="https://maps.googleapis.com/maps/api/js?key={{ basicControl()->google_map_app_key }}&loading=async"></script>
        <script src="{{ asset('assets/global/js/frontend_leaflet.js') }}"></script>
        <script src="{{ asset('assets/global/js/frontendControl.FullScreen.js') }}"></script>
        <script src="{{ asset('assets/global/js/frontend_map.js') }}"></script>
    @endif

    <script>
        'use strict'

        $(".listing__category__select2").select2({
            width: '100%',
            placeholder: '@lang("Categories")',
        });

        $(".listing__subcategory__select2").select2({
            width: '100%',
            placeholder: '@lang("Subcategories")',
        });

        function filterSubcategories() {
            let selectedParents = $('#category_id').val() || [];
            $('#subcategory_id option').each(function() {
                if ($(this).val() === 'all') return;
                let parentId = $(this).data('parent');
                if (parentId) {
                    parentId = parentId.toString();
                    if (selectedParents.includes('all') || selectedParents.includes(parentId) || selectedParents.length === 0) {
                        $(this).removeAttr('disabled');
                    } else {
                        $(this).attr('disabled', 'disabled');
                        $(this).prop('selected', false);
                    }
                }
            });
            $('#subcategory_id').trigger('change.select2');
        }

        $('#category_id').on('change', function() {
            filterSubcategories();
        });

        filterSubcategories();

        var isAuthenticate = '{{ Auth::check() }}';

        $(document).ready(function () {
            $('.wishList').on('click', function () {
                var _this = this.id;
                let user_id = $(this).data('user');
                let listing_id = $(this).data('listing');
                let purchase_package_id = $(this).data('purchase');

                if (isAuthenticate == 1) {
                    wishList(user_id, listing_id, purchase_package_id, _this);
                } else {
                    window.location.href = '{{route('login')}}';
                }
            });

            const $resultsCount = $('#results-count');
            if ($resultsCount.length) {
                const total = parseInt($resultsCount.data('total'), 10);
                const currentPage = parseInt($resultsCount.data('current-page'), 10);
                const perPage = 6;
                const start = (currentPage - 1) * perPage + 1;
                const end = Math.min(start + perPage - 1, total);
                $resultsCount.html(`Showing <strong>${start} – ${end}</strong> of <strong>${total}</strong> results`);
            }
        });


        function wishList(user_id = null, listing_id = null, purchase_package_id = null, id = null) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('user.add.to.wish.list') }}",
                type: "POST",
                data: {
                    user_id: user_id,
                    listing_id: listing_id,
                    purchase_package_id: purchase_package_id
                },
                success: function (data) {
                    if (data.stage == 'added') {
                        $(`.save${id}`).removeClass("fal fa-heart");
                        $(`.save${id}`).addClass("fas fa-heart");
                        Notiflix.Notify.success("Wishlist added");
                    }
                    if (data.stage == 'remove') {
                        $(`.save${id}`).removeClass("fas fa-heart");
                        $(`.save${id}`).addClass("fal fa-heart");
                        Notiflix.Notify.success("Wishlist removed");
                    }
                },
            });
        }
    </script>
@endpush
