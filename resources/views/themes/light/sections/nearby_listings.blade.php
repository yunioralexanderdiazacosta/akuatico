@if(isset($nearby_listings['nearbyListings']) && $nearby_listings['nearbyListings']->isNotEmpty())
    <style>
        .nearby-listings .listing-grid-card-link {
            height: 100%;
        }

        .nearby-listings .listing-grid-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .nearby-listings .listing-grid-card .img-box {
            width: 100%;
            height: 180px;
            overflow: hidden;
        }

        .nearby-listings .listing-grid-card .img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .nearby-listings .listing-grid-card .text-box {
            flex: 1;
        }
    </style>
    <section class="nearby-listings">
        <div class="overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="header-text text-center mb-5">
                            @if(isset($nearby_listings['single']['title']) && $nearby_listings['single']['title'])
                                <h5>@lang($nearby_listings['single']['title'])</h5>
                            @endif
                            @if(isset($nearby_listings['single']['sub_title']) && $nearby_listings['single']['sub_title'])
                                <h3>@lang($nearby_listings['single']['sub_title'])</h3>
                            @else
                                <!--  <h3>
                                            @if(!empty($nearby_listings['detectedCityName']))
                                                @lang('Clasificados en') {{ $nearby_listings['detectedCityName'] }}
                                            @elseif(!empty($nearby_listings['detectedCountryName']))
                                                @lang('Clasificados en') {{ $nearby_listings['detectedCountryName'] }}
                                            @else
                                                @lang('Clasificados recientes')
                                            @endif
                                        </h3> -->
                                <h3>
                                    @lang('Clasificados recientes')
                                </h3>
                            @endif
                            @if(isset($nearby_listings['single']['description']) && $nearby_listings['single']['description'])
                                <p class="mx-auto">@lang($nearby_listings['single']['description'])</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    @foreach($nearby_listings['nearbyListings'] as $key => $item)
                        @php
                            $total = $item->reviews()[0]->total;
                            $average_review = $item->reviews()[0]->average;
                        @endphp
                        <div class="col-lg-3 col-md-6">
                            <a class="title h-100 listing-grid-card-link" href="{{ route('listing.details', $item->slug) }}">
                                <div class="listing-box listing-grid-card">
                                    <div class="img-box">
                                        <img class="img-fluid" src="{{ getFile($item->thumbnail_driver, $item->thumbnail) }}"
                                            alt="image" />
                                    </div>
                                    <div class="text-box">
                                        <p class="title">
                                            @lang(Str::limit($item->title, 20))
                                        </p>
                                        <p class="mb-2 mt-2">
                                            <span>@lang('Category'): </span>
                                            @lang(optional($item)->getCategoriesName())
                                        </p>
                                        <p style="color: var(--primary);">
                                            @lang(optional($item->get_user)->firstname)
                                            @lang(optional($item->get_user)->lastname)
                                        </p>
                                        <p class="address">
                                            <i class="fal fa-map-marker-alt"></i>
                                            @lang($item->city_id != null ? $item->get_cities?->getAddress() : $item->address)
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="row text-center mt-5">
                    <div class="col">
                        <a href="{{ route('listings') }}" class="btn-custom">
                            @lang('View More')
                            <i class="fal fa-angle-double-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif