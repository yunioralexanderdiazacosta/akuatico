@extends('user_panel.layouts.user')
@section('title', trans('Add Listing'))
@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/tagsinput.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/global/css/image-uploader.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrapicons-iconpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote.min.css')}}">
    <style>
        .wizard-steps {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 30px 0;
            padding: 0 20px;
            flex-wrap: wrap;
            gap: 8px;
        }
        .wizard-step {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: default;
        }
        .wizard-step .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            border: 2px solid #dee2e6;
            background: #fff;
            color: #6c757d;
            transition: all 0.3s ease;
        }
        .wizard-step.active .step-circle {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        .wizard-step.completed .step-circle {
            background: #28a745;
            border-color: #28a745;
            color: #fff;
        }
        .wizard-step .step-label {
            font-size: 14px;
            font-weight: 500;
            color: #6c757d;
            transition: color 0.3s ease;
        }
        .wizard-step.active .step-label {
            color: var(--primary);
            font-weight: 600;
        }
        .wizard-step.completed .step-label {
            color: #28a745;
        }
        .wizard-step-line {
            width: 40px;
            height: 2px;
            background: #dee2e6;
            transition: background 0.3s ease;
        }
        .wizard-step-line.completed {
            background: #28a745;
        }
        .wizard-nav-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            margin-bottom: 30px;
            padding: 0 15px;
        }
        .wizard-nav-buttons .btn-wizard {
            padding: 10px 30px;
            font-size: 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .wizard-nav-buttons .btn-wizard-prev {
            background: #6c757d;
            color: #fff;
            border: none;
        }
        .wizard-nav-buttons .btn-wizard-prev:hover {
            background: #5a6268;
        }
        .wizard-nav-buttons .btn-wizard-next {
            background: var(--primary);
            color: #fff;
            border: none;
        }
        .wizard-nav-buttons .btn-wizard-next:hover {
            opacity: 0.9;
        }
        .wizard-nav-buttons .btn-wizard-save {
            background: #28a745;
            color: #fff;
            border: none;
        }
        .wizard-nav-buttons .btn-wizard-save:hover {
            background: #218838;
        }
        .switcher.navigator {
            display: none !important;
        }
        @media (max-width: 768px) {
            .wizard-step .step-label {
                display: none;
            }
            .wizard-step-line {
                width: 20px;
            }
            .wizard-nav-buttons .btn-wizard {
                padding: 8px 20px;
                font-size: 14px;
            }
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="switcher navigator">
            <button tab-id="tab1" class="tab active">
                @lang('Basic Info')
                @if ($errors->has('title') || $errors->has('category_id') || $errors->has('description') || $errors->has('place_id') || $errors->has('lat') || $errors->has('long'))
                    @php
                        $tabOne = ['title', 'category_id', 'email', 'phone', 'description', 'place_id', 'lat', 'long'];
                    @endphp
                    <span class="text-danger" type="button" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip"
                        data-bs-html="true"
                        data-bs-title="
                                                                                                                                <div class='text-start px-3 text-white'>
                                                                                                                                   <ul class=''>
                                                                                                                                      @foreach ($errors->getMessages() as $key => $error)
                                                                                                                                        @if(in_array($key, $tabOne))
                                                                                                                                            <li class='text-white'>{{ $error[0] }}</li>
                                                                                                                                        @endif
                                                                                                                                      @endforeach
                                                                                                                                   </ul>
                                                                                                                                </div>">
                        <i class="fal fa-info-circle"></i>
                    </span>
                @endif
            </button>

            @if($single_package_infos->is_video == 1)
                <button tab-id="tab2" class="tab">@lang('Video')
                    @if ($errors->has('youtube_video_id'))
                        @php
                            $tabTwo = ['youtube_video_id'];
                        @endphp

                        <span class="text-danger" type="button" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip"
                            data-bs-html="true"
                            data-bs-title="
                                                                                                                                                                                        <div class='text-start px-3 text-white'>
                                                                                                                                                                                           <ul class=''>
                                                                                                                                                                                              @foreach ($errors->getMessages() as $key => $error)
                                                                                                                                                                                                @if(in_array($key, $tabTwo))
                                                                                                                                                                                                    <li class='text-white'>{{ $error[0] }}</li>
                                                                                                                                                                                                @endif
                                                                                                                                                                                              @endforeach
                                                                                                                                                                                           </ul>
                                                                                                                                                                                        </div>">
                            <i class="fal fa-info-circle"></i>
                        </span>
                    @endif
                </button>
            @endif

            <button tab-id="tab3" class="tab">
                @lang('Photos')
            </button>

            <!-- @if($single_package_infos->is_amenities == 1)
                                            <button tab-id="tab4" class="tab">
                                                @lang('Amenities')
                                                @if ($errors->has('amenity_id.*'))
                                                    @php
                                                        $tabFour = ['amenity_id'];
                                                    @endphp
                                                    <span class="text-danger" type="button" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip"
                                                        data-bs-html="true" data-bs-title="
                                                                                                                                <div class='text-start px-3 text-white'>
                                                                                                                                   <ul class=''>
                                                                                                                                      @foreach ($errors->getMessages() as $key => $error)
                                                                                                                                        @if(in_array($key, $tabFour))
                                                                                                                                            <li class='text-white'>{{ $error[0] }}</li>
                                                                                                                                        @endif
                                                                                                                                      @endforeach
                                                                                                                                   </ul>
                                                                                                                                </div>">
                                                        <i class="fal fa-info-circle"></i>
                                                    </span>
                                                @endif
                                            </button>
                                        @endif -->

            <!-- @if($single_package_infos->is_product == 1)
                                                                    <button tab-id="tab5" class="tab">
                                                                        @lang('Products')
                                                                        @if ($errors->has('product_title.*') || $errors->has('product_price.*') || $errors->has('product_description.*') || $errors->has('product_thumbnail.*'))
                                                                            @php
                                                                                $tabFive = ['product_title', 'product_price', 'product_description', 'product_thumbnail'];
                                                                            @endphp
                                                                            <span class="text-danger" type="button" data-bs-custom-class="custom-tooltip"
                                                                                  data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="
                                                                                <div class='text-start px-3 text-white'>
                                                                                   <ul class=''>
                                                                                      @foreach ($errors->getMessages() as $key => $error)

                                                                                        @if(in_array($key, $tabFive))
                                                                                            <li class='text-white'>{{ $error[0] }}</li>
                                                                                        @endif
                                                                                      @endforeach
                                                                                   </ul>
                                                                                </div>">
                                                                                <i class="fal fa-info-circle"></i>
                                                                            </span>
                                                                        @endif
                                                                    </button>
                                                                @endif -->

            @if($single_package_infos->seo == 1)
                <button tab-id="tab6" class="tab">
                    @lang('SEO')
                    @if ($errors->has('seo_image') || $errors->has('meta_title') || $errors->has('meta_keywords') || $errors->has('meta_description'))
                        @php
                            $tabSix = ['seo_image', 'meta_title', 'meta_keywords', 'meta_description'];
                        @endphp
                        <span class="text-danger" type="button" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip"
                            data-bs-html="true"
                            data-bs-title="
                                                                                                                                                                                        <div class='text-start px-3 text-white'>
                                                                                                                                                                                           <ul class=''>
                                                                                                                                                                                              @foreach ($errors->getMessages() as $key => $error)
                                                                                                                                                                                                @if(in_array($key, $tabSix))
                                                                                                                                                                                                    <li class='text-white'>{{ $error[0] }}</li>
                                                                                                                                                                                                @endif
                                                                                                                                                                                              @endforeach
                                                                                                                                                                                           </ul>
                                                                                                                                                                                        </div>">
                            <i class="fal fa-info-circle"></i>
                        </span>
                    @endif
                </button>
            @endif

            <!-- @if($single_package_infos->is_whatsapp == 1 || $single_package_infos->is_messenger == 1)
                                                <button tab-id="tab7" class="tab">
                                                    @lang('Communication')
                                                    @if ($errors->has('whatsapp_number') || $errors->has('fb_app_id') || $errors->has('fb_page_id'))
                                                        @php
                                                            $tabSeven = ['whatsapp_number', 'fb_app_id', 'fb_page_id'];
                                                        @endphp

                                                        <span class="text-danger" type="button" data-bs-custom-class="custom-tooltip" data-bs-toggle="tooltip"
                                                            data-bs-html="true" data-bs-title="
                                                                                                                        <div class='text-start px-3 text-white'>
                                                                                                                           <ul class=''>
                                                                                                                              @foreach ($errors->getMessages() as $key => $error)
                                                                                                                                @if(in_array($key, $tabSeven))
                                                                                                                                    <li class='text-white'>{{ $error[0] }}</li>
                                                                                                                                @endif
                                                                                                                              @endforeach
                                                                                                                           </ul>
                                                                                                                        </div>">
                                                            <i class="fal fa-info-circle"></i>
                                                        </span>
                                                    @endif
                                                </button>
                                            @endif -->

            <!-- @if($single_package_infos->is_create_from == 1)
                                                                    <button tab-id="tab8" class="tab">
                                                                        @lang('Custom Form')
                                                                    </button>
                                                                @endif -->
        </div>

        <div class="wizard-steps" id="wizardSteps">
            {{-- Steps are generated dynamically by JavaScript --}}
        </div>

        <form action="{{ route('user.storeListing', $id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div id="tab1" class="add-listing-form content active">
                <div class="main row gy-4">
                    <div class="col-xl-12">
                        <h3 class="mb-3">@lang('Basic Info')</h3>
                        <div class="form">
                            <div class="basic-form">
                                <div class="row g-3">
                                    <div class="input-box col-md-12">
                                        <input class="form-control change_name_input @error('title') is-invalid @enderror"
                                            type="text" name="title" value="{{ old('title') }}"
                                            placeholder="@lang('Title')" />
                                        <div class="invalid-feedback">
                                            @error('title') @lang($message) @enderror
                                        </div>
                                    </div>
                                    <!-- <div class="input-box col-md-12">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <div class="col-4">
                                                                                            <b>@lang("Permalink : ")</b> <span>{{ url('/listing') }}/</span>
                                                                                        </div>
                                                                                        <div class="col-5">
                                                                                            <input class="form-control set-slug @error('slug') is-invalid @enderror"
                                                                                                type="text" name="slug" value="{{ old('slug') }}"
                                                                                                placeholder="@lang('Slug')" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="invalid-feedback">
                                                                                        @error('slug') @lang($message) @enderror
                                                                                    </div>
                                                                                </div> -->
                                    <div class="input-box col-md-12">
                                        <select id="category_id"
                                            class="listing__category__select2 form-control @error('category_id') is-invalid @enderror"
                                            name="category_id[]" multiple
                                            data-categories="{{ $single_package_infos->no_of_categories_per_listing }}">
                                            @foreach ($all_listings_category->whereNull('parent_id') as $item)
                                                <option value="{{ $item->id }}" {{ (collect(old('category_id'))->contains($item->id)) ? 'selected' : '' }}>
                                                    @lang(optional($item->details)->name)
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('category_id') @lang($message) @enderror
                                        </div>
                                    </div>

                                    <div class="input-box col-md-12 subcategory-field" style="display: none;">
                                        <select id="subcategory_id"
                                            class="form-control @error('subcategory_id') is-invalid @enderror"
                                            name="subcategory_id[]" multiple>
                                            <option disabled> @lang('Select Subcategory')</option>
                                            @foreach ($all_listings_category->whereNotNull('parent_id') as $item)
                                                <option value="{{ $item->id }}" data-parent="{{ $item->parent_id }}" {{ (collect(old('subcategory_id'))->contains($item->id)) ? 'selected' : '' }}>
                                                    @lang(optional($item->details)->name)
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('subcategory_id') @lang($message) @enderror
                                        </div>
                                    </div>

                                    <div class="input-box col-md-12 boat-fields" style="display: none;">
                                        <select id="marca" class="form-control @error('marca') is-invalid @enderror"
                                            name="marca[]" multiple>
                                            <option disabled> @lang('Select Brand')</option>
                                            @foreach ($marcas as $item)
                                                <option value="{{ $item->id }}" {{ (collect(old('marca'))->contains($item->id)) ? 'selected' : '' }}>
                                                    @lang(optional($item->details)->name)
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('marca') @lang($message) @enderror
                                        </div>
                                    </div>

                                    <div class="input-box col-md-6">
                                        <input class="form-control @error('email') is-invalid @enderror" type="email"
                                            name="email" value="{{ old('email') }}" placeholder="@lang('Email')" />
                                        <div class="invalid-feedback">
                                            @error('email') @lang($message) @enderror
                                        </div>
                                    </div>

                                    <div class="input-box col-md-6">
                                        <input class="form-control @error('phone') is-invalid @enderror" type="tel"
                                            id="phone" name="phone" value="{{ old('phone') }}" placeholder="(787) 382-0627"
                                            maxlength="14" />
                                        <div class="invalid-feedback">
                                            @error('phone') @lang($message) @enderror
                                        </div>
                                    </div>

                                    <div class="input-box col-md-6">
                                        <input class="form-control @error('price') is-invalid @enderror" type="text"
                                            id="price_display"
                                            value="{{ old('price') ? number_format(old('price'), 0, '.', ',') : '' }}"
                                            placeholder="@lang('Price')" />
                                        <input type="hidden" name="price" id="price_hidden"
                                            value="{{ old('price') ? number_format(old('price'), 0, '.', '') : '' }}" />
                                        <div class="invalid-feedback">
                                            @error('price') @lang($message) @enderror
                                        </div>
                                    </div>

                                    <div class="input-box col-md-6 condition-field" style="display: none;">
                                        <select id="condition" class="form-control @error('condition') is-invalid @enderror"
                                            name="condition">
                                            <option value="" {{ old('condition') ? '' : 'selected' }}>@lang('Select Condition')</option>
                                            <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>@lang('New')</option>
                                            <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>@lang('Used')</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('condition') @lang($message) @enderror
                                        </div>
                                    </div>

                                    <div class="input-box col-md-6 boat-fields" style="display: none;">
                                        <input class="form-control @error('length') is-invalid @enderror" type="text"
                                            id="length_display"
                                            value="{{ old('length') ? number_format(old('length'), 2, '.', ',') : '' }}"
                                            placeholder="@lang('Length (Feet)')" />
                                        <input type="hidden" name="length" id="length_hidden" value="{{ old('length') }}" />
                                        <div class="invalid-feedback">
                                            @error('length') @lang($message) @enderror
                                        </div>
                                    </div>

                                    <div class="input-box col-md-6 boat-fields" style="display: none;">
                                        <select id="year" class="form-control @error('year') is-invalid @enderror"
                                            name="year">
                                            <option value="" disabled {{ old('year') ? '' : 'selected' }}>
                                                @lang('Select Year')
                                            </option>
                                            @for ($y = date('Y'); $y >= 1960; $y--)
                                                <option value="{{ $y }}" {{ old('year') == $y ? 'selected' : '' }}>{{ $y }}
                                                </option>
                                            @endfor
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('year') @lang($message) @enderror
                                        </div>
                                    </div>

                                    <div class="input-box col-12">
                                        <p class="mb-2 text-muted" style="font-size: 0.9rem;">En la descripción favor de
                                            colocar: Horas, combustible, caballaje de motores.</p>
                                    </div>

                                    <div class="input-box col-12 bg-white p-0">
                                        <textarea class="form-control summernote @error('description') is-invalid @enderror"
                                            name="description" id="summernote" rows="15" value="{{ old('description') }}"
                                            placeholder="@lang('Description')">{{ old('description') }}</textarea>
                                        <div class="invalid-feedback">
                                            @error('description') @lang($message) @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12">
                        <h3 class="mb-3">@lang('Location')</h3>
                        <div class="map-box">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form">
                                        <div class="row g-3 location-form">
                                            <div class="input-box col-md-6">
                                                <select
                                                    class="js-example-basic-single place_id form-control @error('country_id') is-invalid @enderror"
                                                    id="country_id" name="country_id">
                                                    <option selected disabled>@lang('Select Country')</option>
                                                    @foreach ($countries as $item)
                                                        <option value="{{ $item->id }}" data-name="{{ $item->name }}"
                                                            data-code="{{ $item->iso2 }}" {{ old('country_id') == $item->id ? 'selected' : '' }}>@lang($item->name)</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    @error('country_id') @lang($message) @enderror
                                                </div>
                                            </div>

                                            <div class="input-box col-md-6">
                                                <select
                                                    class="js-example-basic-single place_id form-control @error('state_id') is-invalid @enderror"
                                                    id="state_id" name="state_id">
                                                    <option selected disabled>@lang('Select State')</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @error('state_id') @lang($message) @enderror
                                                </div>
                                            </div>

                                            <div class="input-box col-md-6">
                                                <select
                                                    class="js-example-basic-single place_id form-control @error('city_id') is-invalid @enderror"
                                                    id="city_id" name="city_id">
                                                    <option selected disabled>@lang('Select City')</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @error('city_id') @lang($message) @enderror
                                                </div>
                                            </div>

                                            <div class="input-box col-md-6">
                                                <input id="address-search"
                                                    class="form-control @error('address') is-invalid @enderror"
                                                    name="address" value="{{ old('address') }}" type="text"
                                                    placeholder="@lang('Search Location')" autocomplete="off"
                                                    data-lat="33.93911" data-long="67.709953" data-code="AF" />
                                                <div class="invalid-feedback">
                                                    @error('address') @lang($message) @enderror
                                                </div>
                                            </div>

                                            <div class="input-box col-md-6 invisible">
                                                <input class="form-control @error('lat') is-invalid @enderror" id="lat"
                                                    name="lat" value="{{ old('lat') }}" type="text"
                                                    placeholder="@lang('Lat')" />
                                                <div class="invalid-feedback">
                                                    @error('lat') @lang($message) @enderror
                                                </div>
                                            </div>

                                            <div class="input-box col-md-6 invisible">
                                                <input class="form-control @error('long') is-invalid @enderror" id="lng"
                                                    name="long" value="{{ old('long') }}" placeholder="@lang('Long')"
                                                    type="text" />
                                                <div class="invalid-feedback">
                                                    @error('long') @lang($message) @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    @if(basicControl()->is_google_map == 1)
                                        <div id="map"></div>
                                        <div id="infowindow-content">
                                            <img id="place-image" src="#" alt="" style="display: none;">
                                            <p id="place-name" class="title"></p>
                                            <span id="place-address"></span>
                                        </div>
                                    @else
                                        <div id="map">
                                            <p>
                                                @lang('You can also set location moving marker')
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($single_package_infos->is_business_hour == 1)
                        <div class="col-xl-6">
                            <h3 class="mb-3">@lang('Business Hours')</h3>
                            <p class="text-muted mb-2" style="font-size: 0.85rem;">
                                @lang('Presiona el botón de (+) para agregar más días.')
                            </p>
                            <div class="form business-hour">
                                <div
                                    class="d-sm-flex justify-content-between delete_this @error('working_day.0') is-invalid @enderror">
                                    <div class="input-box w-100 my-1 mx-sm-1">
                                        <select class="js-example-basic-single form-control" name="working_day[]">
                                            <option value="Monday" {{ old('working_day.0') == 'Monday' ? 'selected' : '' }}>
                                                @lang('Monday')
                                            </option>
                                            <option value="Tuesday" {{ old('working_day.0') == 'Tuesday' ? 'selected' : '' }}>
                                                @lang('Tuesday')
                                            </option>
                                            <option value="Wednesday" {{ old('working_day.0') == 'Wednesday' ? 'selected' : '' }}>
                                                @lang('Wednesday')
                                            </option>
                                            <option value="Thursday" {{ old('working_day.0') == 'Thursday' ? 'selected' : '' }}>
                                                @lang('Thursday')
                                            </option>
                                            <option value="Friday" {{ old('working_day.0') == 'Friday' ? 'selected' : '' }}>
                                                @lang('Friday')
                                            </option>
                                            <option value="Saturday" {{ old('working_day.0') == 'Saturday' ? 'selected' : '' }}>
                                                @lang('Saturday')
                                            </option>
                                            <option value="Sunday" {{ old('working_day.0') == 'Sunday' ? 'selected' : '' }}>
                                                @lang('Sunday')
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('working_day.0') @lang($message) @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex">
                                        <div class="input-box w-100 my-1 me-1">
                                            <input type="time" name="start_time[]" value="{{ old('start_time.0') }}"
                                                class="form-control @error('start_time.0') is-invalid @enderror"
                                                placeholder="@lang('Start Hour')" />
                                            <div class="invalid-feedback">
                                                @error('start_time.0') @lang($message) @enderror
                                            </div>
                                        </div>

                                        <div class="input-box w-100 my-1 me-1">
                                            <input type="time" name="end_time[]" value="{{ old('end_time.0') }}"
                                                class="form-control @error('end_time.0') is-invalid @enderror"
                                                placeholder="@lang('End Hour')" />
                                            <div class="invalid-feedback">
                                                @error('end_time.0') @lang($message) @enderror
                                            </div>
                                        </div>

                                        <div class="input-box my-1 me-1">
                                            <button class="btn-custom customButton add-new" type="button"
                                                id="add_business_hour">
                                                <i class="fal fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="new_business_hour_form">
                                    @php
                                        $oldWorkingDaysCount = old('working_day') ? count(old('working_day')) : 0;
                                    @endphp
                                    @if($oldWorkingDaysCount > 1)
                                        @for($i = 1; $i < $oldWorkingDaysCount; $i++)
                                            <div
                                                class="d-sm-flex justify-content-between delete_this removeBusinessHourInputField @error("working_day.$i") is-invalid @enderror">
                                                <div class="input-box w-100 my-1 mx-sm-1">
                                                    <select class="js-example-basic-single form-control" name="working_day[]">
                                                        <option value="Monday" {{ old("working_day.$i") == 'Monday' ? 'selected' : '' }}>
                                                            @lang('Monday')
                                                        </option>
                                                        <option value="Tuesday" {{ old("working_day.$i") == 'Tuesday' ? 'selected' : '' }}>@lang('Tuesday')</option>
                                                        <option value="Wednesday" {{ old("working_day.$i") == 'Wednesday' ? 'selected' : '' }}>@lang('Wednesday')</option>
                                                        <option value="Thursday" {{ old("working_day.$i") == 'Thursday' ? 'selected' : '' }}>@lang('Thursday')</option>
                                                        <option value="Friday" {{ old("working_day.$i") == 'Friday' ? 'selected' : '' }}>
                                                            @lang('Friday')
                                                        </option>
                                                        <option value="Saturday" {{ old("working_day.$i") == 'Saturday' ? 'selected' : '' }}>@lang('Saturday')</option>
                                                        <option value="Sunday" {{ old("working_day.$i") == 'Sunday' ? 'selected' : '' }}>
                                                            @lang('Sunday')
                                                        </option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        @error("working_day.$i") @lang($message) @enderror
                                                    </div>
                                                </div>

                                                <div class="d-flex">
                                                    <div class="input-box w-100 my-1 me-1">
                                                        <input type="time" name="start_time[]" value="{{ old("start_time.$i") }}"
                                                            class="form-control @error("start_time.$i") is-invalid @enderror"
                                                            placeholder="@lang('Start Hour')" />
                                                        <div class="invalid-feedback">
                                                            @error("start_time.$i") @lang($message) @enderror
                                                        </div>
                                                    </div>

                                                    <div class="input-box w-100 my-1 me-1">
                                                        <input type="time" name="end_time[]" value="{{ old("end_time.$i") }}"
                                                            class="form-control @error("end_time.$i") is-invalid @enderror"
                                                            placeholder="@lang('End Hour')" />
                                                        <div class="invalid-feedback">
                                                            @error("end_time.$i") @lang($message) @enderror
                                                        </div>
                                                    </div>

                                                    <div class="input-box my-1 me-1">
                                                        <button
                                                            class="btn btn-outline-danger h-100 add-new remove_business_hour_input_field_block"
                                                            type="button">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-xl-6">
                        <h3 class="mb-3">@lang('Websites And Social Links')</h3>
                        <div class="form website_social_links">
                            <div class="d-flex justify-content-between">
                                <div class="input-group mt-1">
                                    <input type="text" name="social_icon[]"
                                        class="form-control demo__icon__picker iconpicker1 bg-white @error('social_icon.0') is-invalid @enderror"
                                        placeholder="Pick a icon" aria-label="Pick a icon" aria-describedby="basic-addon1"
                                        readonly>
                                    <div class="invalid-feedback">
                                        @error('social_icon.0') @lang($message) @enderror
                                    </div>
                                </div>

                                <div class="input-box w-100 my-1 me-1">
                                    <input type="url" name="social_url[]" value="{{ old('social_url.0') }}"
                                        class="form-control @error('social_url.0') is-invalid @enderror"
                                        placeholder="@lang('URL')" />
                                    @error('social_url.0')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="my-1 me-1">
                                    <button class="btn-custom customButton add-new" type="button" id="add_social_links">
                                        <i class="fal fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="new_social_links_form">
                                @php
                                    $oldSocialCounts = old('social_icon') ? count(old('social_icon')) : 0;
                                @endphp
                                @if($oldSocialCounts > 1)
                                    @for($i = 1; $i < $oldSocialCounts; $i++)
                                        <div class="d-flex justify-content-between append_new_social_form removeSocialLinksInput">
                                            <div class="input-group mt-1">
                                                <input type="text" name="social_icon[]" value="{{ old("social_icon.$i") }}"
                                                    class="form-control bg-white demo__icon__picker iconpicker{{$i}} iconpicker @error("social_icon.$i") is-invalid @enderror"
                                                    placeholder="Pick a icon" aria-label="Pick a icon"
                                                    aria-describedby="basic-addon1" readonly>
                                                <div class="invalid-feedback">
                                                    @error("social_icon.$i") @lang($message) @enderror
                                                </div>
                                            </div>

                                            <div class="input-box w-100 my-1 me-1">
                                                <input type="url" name="social_url[]" value="{{ old("social_url.$i") }}"
                                                    class="form-control @error("social_url.$i") is-invalid @enderror"
                                                    placeholder="@lang('URL')" />
                                                @error("social_url.$i")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="my-1 me-1">
                                                <button class="btn btn-outline-danger h-100 add-new remove_social_link_input_field"
                                                    type="button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($single_package_infos->is_video == 1)
                <div id="tab2" class="add-listing-form content">
                    <div class="main row gy-4">
                        <div class="col-xl-6">
                            <h3 class="mb-3">
                                @lang('Video') <span class="optional">(@lang('Youtube Video Id'))</span>
                            </h3>
                            <div class="form">
                                <div class="row g-3">
                                    <div class="input-box col-md-12">
                                        <input class="form-control @error('social_url') is-invalid @enderror" type="text"
                                            placeholder="@lang('URL')" value="{{ old('youtube_video_id') }}"
                                            name="youtube_video_id" />
                                        @error('youtube_video_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="youtube nk-plain-video">
                                            <span class="nk-video-plain-toggle">
                                                <span class="nk-video-icon">
                                                    <svg class="svg-inline--fa fa-play fa-w-14 pl-5" aria-hidden="true"
                                                        data-prefix="fa" data-icon="play" role="img"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg>
                                                        <path fill="#184af9"
                                                            d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z" />
                                                    </svg>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div id="tab3" class="add-listing-form content">
                <div class="main row gy-4">
                    @if($single_package_infos->is_image == 1)
                        <div class="col-xl-7 custom-margin">
                            <h3 class="mb-3">@lang('Add images')</h3>
                            <div class="listing-image no_of_listing_image"
                                data-listingimage="{{ $single_package_infos->is_image == 1 && $single_package_infos->no_of_img_per_listing == null ? 'unlimited' : $single_package_infos->no_of_img_per_listing }}">
                            </div>
                            <span class="text-danger"> @error('listing_image.*') @lang($message) @enderror</span>
                        </div>
                    @endif
                </div>
            </div>

            @if($single_package_infos->is_amenities == 1)
                <div id="tab4" class="add-listing-form content">
                    <div class="main row gy-4">
                        <div class="col-xl-6">
                            <h3 class="mb-3">@lang('Amenities')</h3>
                            <div class="form">
                                <div class="row g-3">
                                    <div class="input-box col-md-12">
                                        <select class="amenities_select2 form-control @error('amenity_id') is-invalid @enderror"
                                            name="amenity_id[]" multiple
                                            data-amenities="{{ $single_package_infos->no_of_amenities_per_listing }}">
                                            @foreach ($all_amenities as $item)
                                                <option value="{{ $item->id }}" {{ (collect(old('amenity_id'))->contains($item->id)) ? 'selected' : '' }}>{{ $item->details->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="invalid-feedback">
                                        @error('amenity_id.0') {{ $message }} @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($single_package_infos->is_product == 1)
                <div id="tab5" class="add-listing-form content">
                    <div class="main row gy-4 new_product_form">
                        <div class="d-flex justify-content-start">
                            <h3 class="me-3">@lang('Product')</h3>
                            <button class="btn-custom-product add-new-product" type="button" id="add_products"
                                data-products="{{ $single_package_infos->no_of_product == null ? 'unlimited' : $single_package_infos->no_of_product - 1 }}">
                                <i class="fal fa-plus"></i> @lang('Add More') (<span
                                    class="product_count">@if($single_package_infos->no_of_product == null)
                                        @lang('unlimited')
                                    @else
                                        {{ $single_package_infos->no_of_product - 1 }}
                                    @endif </span>)
                            </button>
                        </div>

                        <div class="col-xl-6 col-md-6 col-sm-12">
                            <div class="form new__product__form">
                                <div class="row g-3">
                                    <div class="input-box col-md-6">
                                        <input class="form-control @error('product_title.0') is-invalid @enderror" type="text"
                                            name="product_title[]" placeholder="@lang('Title')"
                                            value="{{ old('product_title.0') }}" />
                                        @error('product_title.0')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-box col-md-6">
                                        <input class="form-control @error('product_price.0') is-invalid @enderror" type="number"
                                            step="0.1" name="product_price[]" placeholder="@lang('Price')"
                                            value="{{ old('product_price.0') }}" />
                                        @error('product_price.0')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-box col-12 bg-white p-0">
                                        <textarea class="form-control @error('product_description.0') is-invalid @enderror"
                                            cols="30" rows="3" name="product_description[]"
                                            placeholder="@lang('Description')">{{ old('product_description.0') }}</textarea>
                                        @error('product_description.0')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>



                                    <div class="pe-2">
                                        <div class="input-box col-12 no-of-img-per-product">
                                            <div class="product-image no_of_product_image" id="product-image1"
                                                data-productimage="{{ $single_package_infos->is_product == 1 && $single_package_infos->no_of_img_per_product == null ? 500 : $single_package_infos->no_of_img_per_product }}">
                                            </div>
                                            <span class="text-danger"> @error('product_image.1.*') @lang($message)
                                            @enderror</span>
                                        </div>
                                    </div>

                                    <div class="upload-img thumbnail">
                                        <div class="form">
                                            <div class="img-box product-thumbnail">
                                                <input accept="image/*" type="file" onchange="previewImage('product_thumbnail')"
                                                    name="product_thumbnail[]" />
                                                <span class="select-file">@lang('Product Thumbnail')</span>
                                                <img id="product_thumbnail"
                                                    src="{{ asset(getFile(config('location.default'))) }}" class="img-fluid" />
                                            </div>
                                        </div>

                                        @error('product_thumbnail.0')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $productCounts = old('product_title') ? count(old('product_title')) : 0;
                        @endphp
                        @if($productCounts > 1)
                            @for($i = 1; $i < $productCounts; $i++)
                                <div class="col-xl-6 removeProductForm">
                                    <div class="form new__product__form">
                                        <span class="product-form-close"> <i class="fa fa-times"></i> </span>
                                        <div class="row g-3">
                                            <div class="input-box col-md-6">
                                                <input class="form-control @error('product_title.' . $i) is-invalid @enderror"
                                                    type="text" name="product_title[]" placeholder="@lang('Title')"
                                                    value="{{ old('product_title.' . $i) }}" />
                                                @error('product_title.' . $i)
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="input-box col-md-6">
                                                <input class="form-control @error('product_price.' . $i) is-invalid @enderror"
                                                    type="number" step="0.1" name="product_price[]" placeholder="@lang('Price')"
                                                    value="{{ old('product_price.' . $i) }}" />
                                                @error('product_price.' . $i)
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="input-box col-12">
                                                <textarea class="form-control @error('product_description.' . $i) is-invalid @enderror"
                                                    cols="30" rows="3" name="product_description[]"
                                                    placeholder="@lang('Description')">{{ old('product_description.' . $i) }}</textarea>
                                                @error('product_description.' . $i)
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="pe-2">
                                                <div class="input-box col-12 no-of-img-per-product">
                                                    <div class="product-image no_of_product_image" id="product-image{{ $i + 1 }}"
                                                        data-productimage="{{ $single_package_infos->is_product == 1 && $single_package_infos->no_of_img_per_product == null ? 500 : $single_package_infos->no_of_img_per_product }}">
                                                    </div>
                                                    <span class="text-danger"> @error('product_image.' . ($i + 1) . '.*')
                                                        @lang($message)
                                                    @enderror</span>
                                                </div>
                                            </div>

                                            <div class="upload-img thumbnail">
                                                <div class="form">
                                                    <div class="img-box product-thumbnail">
                                                        <input accept="image/*" type="file" onchange="previewImage('product_thumbnail')"
                                                            name="product_thumbnail[]" />
                                                        <span class="select-file">@lang('Product Thumbnail')</span>
                                                        <img id="product_thumbnail"
                                                            src="{{ asset(getFile(config('location.default'))) }}" class="img-fluid" />
                                                    </div>
                                                </div>

                                                @error('product_thumbnail.' . $i)
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @endif
                    </div>
                </div>
            @endif

            @if($single_package_infos->seo == 1)
                <div id="tab6" class="add-listing-form content">
                    <div class="row mt-2 ms-1">
                        <h3 class="mb-3">@lang('SEO & META Keywords')</h3>
                    </div>
                    <div class="main row">
                        <div class="col-xl-5">
                            <div class="upload-img thumbnail">
                                <div class="form">
                                    <div class="img-box">
                                        <input accept="image/*" type="file" onchange="previewImage('meta_image')"
                                            name="seo_image" />
                                        <span class="select-file">@lang('Select Image')</span>
                                        <img id="meta_image" src="{{ asset(getFile(config('location.default'))) }}"
                                            class="img-fluid" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7">
                            <div class="form">
                                <div class="row g-3">
                                    <div class="input-box col-md-12">
                                        <input class="form-control @error('meta_title') is-invalid @enderror" type="text"
                                            name="meta_title" value="{{ old('meta_title') }}" placeholder="@lang('title')" />
                                        <div class="invalid-feedback">
                                            @error('meta_title') {{ $message }} @enderror
                                        </div>
                                    </div>

                                    <div class="input-box col-md-12">
                                        <input class="form-control mb-1 tags_input @error('meta_keywords') is-invalid @enderror"
                                            type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
                                            data-role="tagsinput" placeholder="@lang('keywords')" />
                                        @error('meta_keywords')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="input-box col-md-12">
                                        <input class="form-control mb-1 tags_input @error('meta_robots') is-invalid @enderror"
                                            type="text" name="meta_robots" value="{{ old('meta_robots') }}"
                                            data-role="tagsinput" placeholder="@lang('meta robots')" />
                                        @error('meta_robots')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-box col-12">
                                        <textarea class="form-control" cols="30" rows="3" name="meta_description"
                                            value="{{ old('meta_description') }}"
                                            placeholder="@lang('Description')">{{ old('meta_description') }}</textarea>
                                        @error('meta_description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="input-box col-12">
                                        <textarea class="form-control" cols="30" rows="3" name="og_description"
                                            value="{{ old('og_description') }}"
                                            placeholder="@lang('OG Description')">{{ old('og_description') }}</textarea>
                                        @error('og_description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($single_package_infos->is_whatsapp == 1 || $single_package_infos->is_whatsapp == 1)
                <div id="tab7" class="add-listing-form content">
                    @if($single_package_infos->is_messenger == 1)
                        <div class="main row gy-4">
                            <div class="col-xl-6 col-md-6">
                                <h3 class="mb-3">@lang('FB Messenger Control')</h3>
                                <div class="form">
                                    <div class="basic-form p-4">
                                        <div class="row g-3">
                                            <div class="input-box col-md-6">
                                                <input class="form-control @error('fb_app_id') is-invalid @enderror" type="text"
                                                    name="fb_app_id" value="{{ old('fb_app_id') }}" placeholder="@lang('App Id')" />
                                                <div class="invalid-feedback">
                                                    @error('fb_app_id') @lang($message) @enderror
                                                </div>
                                            </div>

                                            <div class="input-box col-md-6">
                                                <input class="form-control @error('fb_page_id') is-invalid @enderror" type="text"
                                                    name="fb_page_id" value="{{ old('fb_page_id') }}"
                                                    placeholder="@lang('Page Id')" />
                                                <div class="invalid-feedback">
                                                    @error('fb_page_id') @lang($message) @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h3 class="opacity-0">@lang('test')</h3>
                                <div class="card card-primary shadow">
                                    <div
                                        class="card-header bg-primary text-white py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h5 class="m-0 font-weight-bold text-white">@lang('Instructions')</h5>
                                        <div class="col-md-6 d-flex justify-content-end">
                                            <a href="https://www.youtube.com/watch?v=MQszEDuWFeQ" target="_blank"
                                                class="btn btn-dark btn-sm text-white float-right " type="button">
                                                <span class="btn-label"><i class="fab fa-youtube"></i></span>
                                                @lang('How to set up it?')
                                            </a>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        Step One: Visit The Facebook Developers Page. To start with, navigate your browser to the
                                        Facebook Developers page. ...
                                        Step Three: Add Products In Your App. Now you have to add “Facebook Login” product in your
                                        app. ...
                                        Step Four: Set Up Your Product. ...
                                        Step Five: Make Your App Live.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($single_package_infos->is_whatsapp == 1)
                        <div class="main row gy-4">
                            <div class="col-xl-12">
                                <h3 class="mb-3">@lang('Whatsapp Chat Control')</h3>
                                <div class="form">
                                    <div class="basic-form p-4">
                                        <div class="row g-3">
                                            <div class="input-box col-md-6">
                                                <input class="form-control @error('whatsapp_number') is-invalid @enderror"
                                                    type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}"
                                                    placeholder="@lang('whatsapp number')" />
                                                <div class="invalid-feedback">
                                                    @error('whatsapp_number') @lang($message) @enderror
                                                </div>
                                            </div>

                                            <div class="input-box col-md-6">
                                                <input class="form-control @error('replies_text') is-invalid @enderror" type="text"
                                                    name="replies_text" value="{{ old('replies_text') }}"
                                                    placeholder="@lang('Typically replies within a day')" />
                                                <div class="invalid-feedback">
                                                    @error('replies_text') @lang($message) @enderror
                                                </div>
                                            </div>

                                            <div class="input-box col-md-12 bg-white p-0">
                                                <textarea class="form-control summernote @error('body_text') is-invalid @enderror"
                                                    name="body_text" id="summernote"
                                                    rows="15">@lang('Hi there 👋 <br> <br> How can i help you?')</textarea>
                                                <div class="invalid-feedback">
                                                    @error('body_text') @lang($message) @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if($single_package_infos->is_create_from == 1)
                <div id="tab8" class="add-listing-form content">
                    <div class="main row gy-4">
                        <div class="col-xl-12">
                            <div class="form">
                                <div class="basic-form p-4">
                                    <div class="row g-3 showField">
                                        <div class="input-box col-6">
                                            <input
                                                class="form-control change_name_input @error('form_name') is-invalid @enderror"
                                                type="text" name="form_name" value="{{ old('form_name') }}"
                                                placeholder="@lang('From Name')" />
                                            <div class="invalid-feedback">
                                                @error('form_name') @lang($message) @enderror
                                            </div>
                                        </div>

                                        <div class="input-box col-6">
                                            <input
                                                class="form-control change_name_input @error('form_btn_text') is-invalid @enderror"
                                                type="text" name="form_btn_text" value="{{ old('form_btn_text') }}"
                                                placeholder="@lang('From Button Text')" />
                                            <div class="invalid-feedback">
                                                @error('form_btn_text') @lang($message) @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-2 copyField">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button"
                                                            class="cmn-btn py-1 rounded copyFormData">Copy</button>
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger removeContentDiv d-none">Remove</button>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <div class="row mb-2">
                                                        <input type="hidden" class="copyFieldLength" value="1">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Field Name</label>
                                                            <input type="text" name="field_name[1]"
                                                                class="form-control nameClass" placeholder="Enter Field Name">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Validation Type</label>
                                                            <select class="form-select validationClass" name="is_required[1]">
                                                                <option value="required">Required</option>
                                                                <option value="optional">Optional</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-md-12">
                                                            <label class="form-label">Input Type</label>
                                                            <select class="form-select typeClass" name="input_type[1]">
                                                                <option value="text">Text</option>
                                                                <option value="textarea">Textarea</option>
                                                                <option value="file">File</option>
                                                                <option value="number">Number</option>
                                                                <option value="date">Date</option>
                                                                <option value="select">Select</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="additional-options"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="wizard-nav-buttons">
                <button type="button" class="btn-wizard btn-wizard-prev" id="wizardPrev" style="display: none;">
                    <i class="fal fa-arrow-left me-2"></i>@lang('Anterior')
                </button>
                <div></div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn-wizard btn-wizard-next" id="wizardNext">
                        @lang('Siguiente') <i class="fal fa-arrow-right ms-2"></i>
                    </button>
                    <button type="submit" class="btn-wizard btn-wizard-save" id="wizardSave" style="display: none;">
                        <i class="fal fa-check-circle me-2"></i>@lang('Guardar')
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
    @if(basicControl()->is_google_map == 1)
        <script src="{{ asset('assets/global/js/google_map.js') }}"></script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ basicControl()->google_map_app_key }}&callback=initMap&libraries=places&v=weekly"
            defer></script>
    @else
        <script src="{{ asset('assets/global/js/map.js') }}"></script>
    @endif
    <script src="{{ asset('assets/global/js/tagsinput.js') }}"></script>
    <script src="{{ asset('assets/global/js/image-uploader.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrapicon-iconpicker.js') }}"></script>
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js')}}"></script>

    <script>
        "use strict";

        $('.summernote').summernote({
            height: 300,
            callbacks: {
                onBlurCodeview: function () {
                    let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                    $(this).val(codeviewHtml);
                }
            },
            placeholder: 'Escribe los detalles aqui...',
        });

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

        $(document).ready(function (e) {
            let maximum_no_of_image_per_listing = $('.no_of_listing_image').data('listingimage');
            let listingImageOptions = {
                imagesInputName: 'listing_image',
                label: 'Drag & Drop files here or click to browse images',
                extensions: ['.jpg', '.jpeg', '.png'],
                mimes: ['image/jpeg', 'image/png'],
                maxSize: 5242880
            };

            if (maximum_no_of_image_per_listing != 'unlimited') {
                listingImageOptions.maxFiles = maximum_no_of_image_per_listing;
            }
            $('.listing-image').imageUploader(listingImageOptions);


            let maximum_no_of_image_per_product = $('.no_of_product_image').data('productimage');
            let productImageOptions = {
                imagesInputName: 'product_image[1]',
                label: 'Drag & Drop files here or click to browse images',
                extensions: ['.jpg', '.jpeg', '.png'],
                mimes: ['image/jpeg', 'image/png'],
                maxSize: 5242880
            };
            if (maximum_no_of_image_per_product != 'unlimited') {
                productImageOptions.maxFiles = maximum_no_of_image_per_product;
            }
            let totaloldProducts = $('.product-image').length
            for (let i = 1; i <= totaloldProducts; i++) {
                $(`#product-image${i}`).imageUploader(productImageOptions);
            }

            $("#add_products").on('click', function () {
                let productLenght = $('.new__product__form').length + 1;
                var string = Math.random().toString(10).substring(2, 12);
                let dataProducts = $('#add_products').data('products');

                if (dataProducts >= 1 || dataProducts == 'unlimited') {
                    var productForm = `<div class="col-xl-6 removeProductForm">
                                                                            <div class="form new__product__form">
                                                                                <span class="product-form-close"> <i class="fa fa-times"></i> </span>
                                                                                <div class="row g-3">
                                                                                    <div class="input-box col-md-6">
                                                                                        <input class="form-control" name="product_title[]" type="text" placeholder="@lang('Title')"
                                                                                        />
                                                                                    </div>
                                                                                    <div class="input-box col-md-6">
                                                                                        <input class="form-control" name="product_price[]" type="number" step="0.1" placeholder="@lang('Price')"/>
                                                                                    </div>

                                                                                    <div class="input-box col-12">
                                                                                         <textarea class="form-control" name="product_description[]" cols="30" rows="3" placeholder="@lang('Description')"
                                                                                         ></textarea>
                                                                                    </div>
                                                                                    <div class="pe-2">
                                                                                        <div class="input-box col-12 no-of-img-per-product">
                                                                                            <div class="product-image no_of_product_image" id="product-image${productLenght}" data-productimage="{{ $single_package_infos->is_product == 1 && $single_package_infos->no_of_img_per_product == null ? 500 : $single_package_infos->no_of_img_per_product }}"></div>
                                                                                            <span class="text-danger"> @error('product_image.*') @lang($message) @enderror</span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="upload-img thumbnail">
                                                                                        <div class="form">
                                                                                            <div class="img-box product-thumbnail">
                                                                                                <input accept="image/*" type="file" onchange="previewImage('product_thumbnail` + string + `')" name="product_thumbnail[]"/>
                                                                                                <span class="select-file">@lang('Product Thumbnail')</span>
                                                                                                <img id="product_thumbnail` + string + `" src="{{ getFile(config('location.default')) }}" class="img-fluid"/>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>`;

                    $('.new_product_form').append(productForm)

                    if (dataProducts != 'unlimited') {
                        let newDataProducts = dataProducts - 1;
                        $('#add_products').data('products', newDataProducts);
                        $('.product_count').text(newDataProducts);
                    }
                    let maximum_no_of_image_per_product = $('.no_of_product_image').data('productimage');

                    let productImageOptions = {
                        imagesInputName: `product_image[${productLenght}]`,
                        label: 'Drag & Drop files here or click to browse images',
                        extensions: ['.jpg', '.jpeg', '.png'],
                        mimes: ['image/jpeg', 'image/png'],
                        maxSize: 5242880
                    };
                    if (maximum_no_of_image_per_product != 'unlimited') {
                        productImageOptions.maxFiles = maximum_no_of_image_per_product;
                    }
                    $(`#product-image${productLenght}`).imageUploader(productImageOptions);

                } else {
                    Notiflix.Notify.Warning("No more add products");
                }
            });

            $(document).on('click', '.product-form-close', function () {
                $(this).parents('.removeProductForm').remove();

                let dataProducts = $('#add_products').data('products');
                if (dataProducts != 'unlimited') {
                    let addNewDataProducts = $('#add_products').data('products') + 1
                    $('#add_products').data('products', addNewDataProducts);
                    $('.product_count').text(addNewDataProducts);
                }
            });

            $("#add_business_hour").on('click', function () {
                var form = `<div class="d-sm-flex justify-content-between removeBusinessHourInputField">
                                                                                    <div class="input-box w-100 my-1 mx-sm-1">
                                                                                        <select class="js-example-basic-single form-control" name="working_day[]">
                                                                                            <option value="Monday">@lang('Monday')</option>
                                                                                            <option value="Tuesday">@lang('Tuesday')</option>
                                                                                            <option value="Wednesday">@lang('Wednesday')</option>
                                                                                            <option value="Thursday">@lang('Thursday')</option>
                                                                                            <option value="Friday">@lang('Friday')</option>
                                                                                            <option value="Saturday">@lang('Saturday')</option>
                                                                                            <option value="Sunday">@lang('Sunday')</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="d-flex input-box-two">
                                                                                        <div class="input-box w-100 my-1 me-1">
                                                                                            <input type="time" name="start_time[]" class="form-control" placeholder="@lang('Start Hour')" />
                                                                                        </div>
                                                                                        <div class="input-box w-100 my-1 me-1">
                                                                                            <input type="time" name="end_time[]" class="form-control" placeholder="@lang('End Hour')" />
                                                                                        </div>
                                                                                        <div class="input-box my-1 me-1">
                                                                                            <button class="btn btn-outline-danger h-100 add-new remove_business_hour_input_field_block" type="button">
                                                                                                <i class="fa fa-times"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>`;

                $('.new_business_hour_form').append(form)
            });

            $(document).on('click', '.remove_business_hour_input_field_block', function () {
                $(this).parents('.removeBusinessHourInputField').remove();
            });

            let maxSelectAmenities = $('.amenities_select2').data('amenities');
            $(".amenities_select2").select2({
                width: '100%',
                placeholder: '@lang("Select amenities")',
                maximumSelectionLength: maxSelectAmenities,
            });

            $('.tags_input').tagsinput({
                tagClass: function (item) {
                    return 'badge badge-info';
                },
                focusClass: 'focus',
            });


            setIconpicker('.iconpicker1');

            function setIconpicker(selector = '.iconpicker1') {
                $(selector).iconpicker({
                    title: 'Search Social Icons',
                    selected: false,
                    defaultValue: false,
                    placement: "top",
                    collision: "none",
                    animation: true,
                    hideOnSelect: true,
                    showFooter: false,
                    searchInFooter: false,
                    mustAccept: false,
                    icons: [{
                        title: "bi bi-facebook",
                        searchTerms: ["facebook", "text"]
                    }, {
                        title: "bi bi-twitter",
                        searchTerms: ["twitter", "text"]
                    }, {
                        title: "bi bi-linkedin",
                        searchTerms: ["linkedin", "text"]
                    }, {
                        title: "bi bi-youtube",
                        searchTerms: ["youtube", "text"]
                    }, {
                        title: "bi bi-instagram",
                        searchTerms: ["instagram", "text"]
                    }, {
                        title: "bi bi-whatsapp",
                        searchTerms: ["whatsapp", "text"]
                    }, {
                        title: "bi bi-discord",
                        searchTerms: ["discord", "text"]
                    }, {
                        title: "bi bi-globe",
                        searchTerms: ["website", "text"]
                    }, {
                        title: "bi bi-google",
                        searchTerms: ["google", "text"]
                    }, {
                        title: "bi bi-camera-video",
                        searchTerms: ["vimeo", "text"]
                    }, {
                        title: "bi bi-skype",
                        searchTerms: ["skype", "text"]
                    }, {
                        title: "bi bi-camera-video-fill",
                        searchTerms: ["tiktalk", "text"]
                    }, {
                        title: "bi bi-badge-tm-fill",
                        searchTerms: ["tumbler", "text"]
                    }, {
                        title: "bi bi-blockquote-left",
                        searchTerms: ["blogger", "text"]
                    }, {
                        title: "bi bi-file-word-fill",
                        searchTerms: ["wordpress", "text"]
                    }, {
                        title: "bi bi-badge-wc",
                        searchTerms: ["weixin", "text"]
                    }, {
                        title: "bi bi-telegram",
                        searchTerms: ["telegram", "text"]
                    }, {
                        title: "bi bi-bell-fill",
                        searchTerms: ["snapchat", "text"]
                    }, {
                        title: "bi bi-three-dots",
                        searchTerms: ["flickr", "text"]
                    }, {
                        title: "bi bi-file-ppt",
                        searchTerms: ["pinterest", "text"]
                    }],
                    selectedCustomClass: "bg-primary",
                    fullClassFormatter: function (e) {
                        return e;
                    },
                    input: "input,.iconpicker-input",
                    inputSearch: false,
                    container: false,
                    component: ".input-group-addon,.iconpicker-component",
                })
            }

            let newSocialForm = $('.append_new_social_form').length + 1;
            for (let i = 2; i <= newSocialForm; i++) {
                setIconpicker(`#iconpicker${i}`);
            }

            $("#add_social_links").on('click', function () {
                let newSocialForm = $('.append_new_social_form').length + 2;

                var form = `<div class="d-flex justify-content-between append_new_social_form removeSocialLinksInput">
                                                                                    <div class="input-group mt-1">
                                                                                        <input type="text" name="social_icon[]" class="form-control bg-white demo__icon__picker iconpicker${newSocialForm}" placeholder="Pick a icon" aria-label="Pick a icon"
                                                                                               aria-describedby="basic-addon1" readonly>
                                                                                    </div>

                                                                                    <div class="input-box w-100 my-1 me-1">
                                                                                        <input type="url" name="social_url[]" class="form-control" placeholder="@lang('URL')"/>
                                                                                    </div>
                                                                                    <div class="my-1 me-1">
                                                                                        <button class="btn btn-outline-danger h-100 add-new remove_social_link_input_field" type="button">
                                                                                            <i class="fa fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>`;

                $('.new_social_links_form').append(form)
                setIconpicker(`.iconpicker${newSocialForm}`);
            });

            $(document).on('click', '.remove_social_link_input_field', function () {
                $(this).parents('.removeSocialLinksInput').remove();
            });

            let maxSelectCategories = $('.listing__category__select2').data('categories');
            $(".listing__category__select2").select2({
                width: '100%',
                placeholder: '@lang("Select Categories")',
                maximumSelectionLength: maxSelectCategories,
            });

            function filterSubcategoriesResults(option) {
                if (!option.id) {
                    return option.text;
                }

                let selectedCategories = $('#category_id').val() || [];
                let parentId = $(option.element).data('parent');

                if (!parentId) {
                    return option.text;
                }

                if (selectedCategories.length === 0) {
                    return null;
                }

                let isRelated = selectedCategories.some(function (catId) {
                    return String(catId) === String(parentId);
                });

                return isRelated ? option.text : null;
            }

            $('#subcategory_id').select2({
                width: '100%',
                placeholder: '@lang("Select Subcategories")',
                templateResult: filterSubcategoriesResults,
            });

            $('#marca').select2({
                width: '100%',
                placeholder: '@lang("Select Brand")',
            });

            function filterSubcategories() {
                let selectedCategories = $('#category_id').val() || [];
                let $subcategorySelect = $('#subcategory_id');

                if (selectedCategories.length === 0) {
                    $('.subcategory-field').hide();
                    $subcategorySelect.val(null).trigger('change');
                    return;
                }

                let hasRelatedSubcategories = false;
                $subcategorySelect.find('option').each(function () {
                    let $option = $(this);
                    let parentId = $option.data('parent');

                    if (parentId) {
                        let isRelated = selectedCategories.some(function (catId) {
                            return String(catId) === String(parentId);
                        });

                        if (isRelated) {
                            hasRelatedSubcategories = true;
                            return false;
                        }
                    }
                });

                if (!hasRelatedSubcategories) {
                    $('.subcategory-field').hide();
                    $subcategorySelect.val(null).trigger('change');
                    return;
                }

                $('.subcategory-field').show();

                // Limpiar selecciones que ya no son válidas
                $subcategorySelect.find('option').each(function () {
                    let $option = $(this);
                    let parentId = $option.data('parent');

                    if (parentId) {
                        let isRelated = selectedCategories.some(function (catId) {
                            return String(catId) === String(parentId);
                        });

                        if (!isRelated) {
                            $option.prop('selected', false);
                        }
                    }
                });

                $subcategorySelect.trigger('change');
            }

            function toggleBoatFields() {
                let selectedCategories = $('#category_id').select2('data');
                let hasBotes = selectedCategories.some(function (cat) {
                    return cat.text.toLowerCase().includes('botes');
                });

                if (hasBotes) {
                    $('.boat-fields').show();
                } else {
                    $('.boat-fields').hide();
                    $('.boat-fields input').val('');
                    $('.boat-fields select').val('');
                }
            }

            function toggleConditionField() {
                let selectedCategories = $('#category_id').select2('data');
                let isBotes = selectedCategories.some(function (cat) {
                    let categoryName = (cat.text || '').trim().toLowerCase();
                    return categoryName.includes('botes');
                });

                if (isBotes) {
                    $('.condition-field').show();
                    return;
                }

                $('.condition-field').hide();
                $('#condition').val('');
            }

            function isBotesSelected() {
                let selectedCategories = $('#category_id').select2('data');
                return selectedCategories.some(function (cat) {
                    return cat.text.toLowerCase().includes('botes');
                });
            }

            function generateBoatDetails() {
                return;
                if (!isBotesSelected()) return;

                let marca = $('#marca').select2('data').map(function (item) { return item.text.trim(); }).join(', ') || '';
                let title = $('.change_name_input').val() || '';
                let priceVal = $('#price_display').val() || '';
                let lengthVal = $('#length_display').val() || '';
                let subcategory = $('#subcategory_id').select2('data').map(function (item) { return item.text.trim(); }).join(', ') || '';
                let city = $('#city_id').select2('data').length ? $('#city_id').select2('data')[0].text.trim() : '';
                let country = $('#country_id').select2('data').length ? $('#country_id').select2('data')[0].text.trim() : '';
                let ubicacion = [city, country].filter(Boolean).join(', ');

                let priceFormatted = priceVal ? 'US$' + priceVal : '';

                // Convert feet to meters for eslora display
                let esloraMetros = '';
                if (lengthVal) {
                    let feet = parseFloat(lengthVal.replace(/,/g, ''));
                    if (!isNaN(feet)) {
                        esloraMetros = (feet * 0.3048).toFixed(2) + ' m';
                    }
                }

                let html = '<p><strong>Detalles de la embarcación</strong></p>' +
                    '<p>Marca: ' + marca + '</p>' +
                    '<p>Tipo: ' + title + '</p>' +
                    '<p>Construcción: </p>' +
                    '<p>Estado del barco: </p>' +
                    '<p>Precio: ' + priceFormatted + '</p>' +
                    '<p>Clase de oferta: </p>' +
                    '<p>Categoría: ' + subcategory + '</p>' +
                    '<p>Eslora: ' + esloraMetros + '</p>' +
                    '<p>Combustible: </p>' +
                    '<p>Material del casco: </p>' +
                    '<p>Ubicación de la embarcación: ' + ubicacion + '</p>' +
                    '<br>' +
                    '<p><strong>Medidas</strong></p>' +
                    '<p>Eslora total: ' + esloraMetros + '</p>' +
                    '<p>Manga: </p>' +
                    '<br>' +
                    '<p><strong>Propulsión</strong></p>' +
                    '<p>Tipo de motor: </p>' +
                    '<p>Marca Motor: </p>' +
                    '<p>Combustible: </p>' +
                    '<p>Propulsión: </p>' +
                    '<p>Tipo de transmisión: </p>' +
                    '<p>Localización del motor: </p>' +
                    '<p>Horas de motor: </p>' +
                    '<br>' +
                    '<p><strong>Características adicionales</strong></p>' +
                    '<p>Astillero: </p>' +
                    '<p>Forma del casco: </p>';

                $('#summernote').summernote('code', html);
            }

            $('#category_id').on('change', function () {
                filterSubcategories();
                toggleBoatFields();
                toggleConditionField();
                generateBoatDetails();
            });

            // Update boat details when relevant fields change
            $(document).on('change', '#marca, #subcategory_id, #city_id, #country_id', function () {
                generateBoatDetails();
            });
            $(document).on('input', '#price_display, #length_display', function () {
                generateBoatDetails();
            });

            filterSubcategories();
            toggleBoatFields();
            toggleConditionField();

            $(document).on('input', ".change_name_input", function (e) {
                let inputValue = $(this).val();
                let final_value = inputValue.toLowerCase().replace(/\s+/g, '-');
                $('.set-slug').val(final_value);
                generateBoatDetails();
            });
        });

        @if(old('country_id'))
            setTimeout(function () {
                $('#country_id').val('{{ old('country_id') }}').trigger('change');
            }, 100); // Delay to ensure the select element is ready
        @endif

        @if(old('state_id'))
            setTimeout(function () {
                $('#state_id').val('{{ old('state_id') }}').trigger('change');
            }, 100);
        @endif

        @if(old('city_id'))
            $('#city_id').val('{{ old('city_id') }}');
        @endif

        $(document).on('change', '#city_id', function () {
            let value = $("#city_id").find('option:selected').data("name");
            let lat = $("#city_id").select2().find(":selected").data("lat");
            let long = $("#city_id").select2().find(":selected").data("long");
            $('#lat').val(lat);
            $('#lng').val(long);
            $('#address-search').attr('data-lat', lat);
            $('#address-search').attr('data-long', long);
            $('#address-search').val(value);
            @if(basicControl()->is_google_map == 1)
                initMap();
            @else
                $('#address-search').val($("#city_id").select2().find(":selected").data("name"));
            @endif

                                                            });

        //get state of selected Country
        $(document).on('change', '#country_id', function () {
            let countryId = this.value;
            let countryCode = $("#country_id").select2().find(":selected").data("code");
            $('#address-search').attr('data-code', countryCode);
            @if(basicControl()->is_google_map == 1)
                initMap();
            @endif

            $("#state_id").html('');
            $.ajax({
                url: "{{route('get.states')}}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "country_id": countryId,
                },
                dataType: 'json',
                success: function (result) {
                    $('#state_id').html('<option value="">Select State</option>');
                    $.each(result.states, function (key, value) {
                        $("#state_id").append('<option value="' + value
                            .id + '">' + value.name + '</option>');
                    });
                    @if(old('state_id'))
                        $('#state_id').val('{{ old('state_id') }}').trigger('change');
                    @endif
                    $('#city_id').html('<option value="">Select City</option>');
                }
            });
        });

        //get city of selected state
        $(document).on('change', '#state_id', function () {
            let stateId = this.value;
            $("#city_id").html('');
            $.ajax({
                url: "{{route('get.cities')}}",
                type: "POST",
                data: {
                    state_id: stateId,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $('#city_id').html('<option value="">Select City</option>');
                    $.each(res.cities, function (key, value) {
                        $("#city_id").append('<option value="' + value.id + '" ' +
                            'data-name="' + value.name + '" ' +
                            'data-lat="' + value.latitude + '" ' +
                            'data-long="' + value.longitude + '" ' +
                            'data-code="' + value.country_code + '">' +
                            value.name + '</option>');
                    });
                    @if(old('city_id'))
                        $('#city_id').val('{{ old('city_id') }}');
                    @endif
                                                                    }
            });
        });



        //Dynamic form
        var highestFieldLength = 0;
        $('.copyField').each(function () {
            var currentLength = parseInt($(this).find('.copyFieldLength').val());
            highestFieldLength = Math.max(highestFieldLength, currentLength);
        });


        $('.showField').on('click', '.copyFormData', function () {
            highestFieldLength++;
            var newRow = $(this).closest('.copyField').clone();

            newRow.find('.copyFieldLength').val(highestFieldLength);
            newRow.find('.nameClass').attr('name', `field_name[${highestFieldLength}]`);
            newRow.find('.validationClass').attr('name', `is_required[${highestFieldLength}]`);
            newRow.find('.typeClass').attr('name', `input_type[${highestFieldLength}]`);
            newRow.find('.removeContentDiv').removeClass('d-none');

            var selectedType = $(this).closest('.copyField').find('.typeClass').val();
            newRow.find('.typeClass').val(selectedType);
            $(".showField").append(newRow);
            newRow.find('.typeClass').trigger('change');
        });

        $('.showField').on('click', '.removeContentDiv', function () {
            $(this).closest('.copyField').remove();
        });

        var optionIndex = 1;
        $('.showField').on('change', '.typeClass', function () {
            var $this = $(this);
            var additionalOptionsDiv = $this.closest('.card-body').find('.additional-options');
            var fieldIndex = $this.closest('.copyField').find('.copyFieldLength').val();
            optionIndex = 1;

            if ($this.val() == 'select') {
                additionalOptionsDiv.html(`
                                                                    <div class="row mb-2 optionRow">
                                                                        <div class="col-md-5">
                                                                            <input type="text" name="option_name[${fieldIndex}][${optionIndex}]" class="form-control" placeholder="Enter Option Name">
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <input type="text" name="option_value[${fieldIndex}][${optionIndex}]" class="form-control" placeholder="Enter Option Value">
                                                                        </div>
                                                                        <div class="col-md-2 d-flex align-items-center">
                                                                            <button type="button" class="btn btn-sm btn-success addOptionField">+</button>
                                                                        </div>
                                                                    </div>
                                                                `);
            } else {
                additionalOptionsDiv.empty();
            }
        });

        $('.showField').on('click', '.addOptionField', function () {
            var fieldIndex = $(this).closest('.copyField').find('.copyFieldLength').val();
            optionIndex++;
            var newOptionRow = `
                                                                <div class="row mb-2 optionRow">
                                                                    <div class="col-md-5">
                                                                        <input type="text" name="option_name[${fieldIndex}][${optionIndex}]" class="form-control" placeholder="Enter Option Name">
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <input type="text" name="option_value[${fieldIndex}][${optionIndex}]" class="form-control" placeholder="Enter Option Value">
                                                                    </div>
                                                                    <div class="col-md-2 d-flex align-items-center">
                                                                        <button type="button" class="btn btn-sm btn-danger removeOptionField">-</button>
                                                                    </div>
                                                                </div>`;
            $(this).closest('.additional-options').append(newOptionRow);
        });

        $('.showField').on('click', '.removeOptionField', function () {
            $(this).closest('.optionRow').remove();
        });

        // Format number fields with commas, optionally keeping decimals.
        function formatNumberInput(displayId, hiddenId, allowDecimals = true) {
            var $display = $('#' + displayId);
            var $hidden = $('#' + hiddenId);

            $display.on('input', function () {
                var raw = $(this).val().replace(allowDecimals ? /[^0-9.]/g : /[^0-9]/g, '');

                if (allowDecimals) {
                    // Allow only one decimal point
                    var parts = raw.split('.');
                    if (parts.length > 2) {
                        raw = parts[0] + '.' + parts.slice(1).join('');
                        parts = raw.split('.');
                    }
                } else {
                    raw = raw.replace(/^0+(?=\d)/, '');
                    var parts = [raw];
                }

                $hidden.val(raw);

                // Format the integer part with commas
                if (raw) {
                    var intPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    var formatted = allowDecimals && parts.length > 1 ? intPart + '.' + parts[1] : intPart;
                    $(this).val(formatted);
                } else {
                    $(this).val('');
                }
            });

            $display.on('blur', function () {
                var raw = $hidden.val();

                if (!raw) {
                    $(this).val('');
                    return;
                }

                if (allowDecimals && !isNaN(parseFloat(raw))) {
                    var num = parseFloat(raw).toFixed(2);
                    $hidden.val(num);
                    var parts = num.split('.');
                    var intPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    $(this).val(intPart + '.' + parts[1]);
                } else if (!allowDecimals && !isNaN(parseInt(raw, 10))) {
                    var num = parseInt(raw, 10).toString();
                    $hidden.val(num);
                    $(this).val(num.replace(/\B(?=(\d{3})+(?!\d))/g, ','));
                }
            });
        }

        formatNumberInput('price_display', 'price_hidden', false);
        formatNumberInput('length_display', 'length_hidden');

        // Phone format: (XXX) XXX-XXXX
        $('#phone').on('input', function () {
            var digits = $(this).val().replace(/\D/g, '').substring(0, 10);
            var formatted = '';
            if (digits.length > 0) {
                formatted = '(' + digits.substring(0, 3);
            }
            if (digits.length >= 3) {
                formatted += ') ' + digits.substring(3, 6);
            }
            if (digits.length >= 6) {
                formatted += '-' + digits.substring(6, 10);
            }
            $(this).val(formatted);
        });

        // ===== WIZARD NAVIGATION =====
        (function() {
            var stepLabels = {
                'tab1': '@lang("Información Básica")',
                'tab2': '@lang("Video")',
                'tab3': '@lang("Fotos")',
                'tab4': '@lang("Amenidades")',
                'tab5': '@lang("Productos")',
                'tab6': '@lang("SEO")',
                'tab7': '@lang("Comunicación")',
                'tab8': '@lang("Formulario")'
            };

            // Only show these tabs in the wizard
            var allowedTabs = ['tab1', 'tab2', 'tab3'];
            var allContents = document.querySelectorAll('.add-listing-form.content');
            var wizardPanels = [];
            allContents.forEach(function(panel) {
                if (allowedTabs.indexOf(panel.id) !== -1) {
                    wizardPanels.push(panel.id);
                } else {
                    panel.style.display = 'none';
                    panel.classList.remove('active');
                }
            });

            var currentStep = 0;

            function buildStepIndicator() {
                var container = document.getElementById('wizardSteps');
                container.innerHTML = '';
                wizardPanels.forEach(function(panelId, index) {
                    if (index > 0) {
                        var line = document.createElement('div');
                        line.className = 'wizard-step-line';
                        line.setAttribute('data-line-index', index - 1);
                        container.appendChild(line);
                    }
                    var step = document.createElement('div');
                    step.className = 'wizard-step' + (index === 0 ? ' active' : '');
                    step.setAttribute('data-step-index', index);
                    step.innerHTML = '<span class="step-circle">' + (index + 1) + '</span>' +
                        '<span class="step-label">' + (stepLabels[panelId] || panelId) + '</span>';
                    container.appendChild(step);
                });
            }

            function updateWizard() {
                // Update panels
                allContents.forEach(function(panel) {
                    panel.classList.remove('active');
                });
                var activePanel = document.getElementById(wizardPanels[currentStep]);
                if (activePanel) activePanel.classList.add('active');

                // Update step indicators
                document.querySelectorAll('.wizard-step').forEach(function(step, index) {
                    step.classList.remove('active', 'completed');
                    if (index === currentStep) {
                        step.classList.add('active');
                    } else if (index < currentStep) {
                        step.classList.add('completed');
                    }
                });

                // Update lines
                document.querySelectorAll('.wizard-step-line').forEach(function(line, index) {
                    line.classList.toggle('completed', index < currentStep);
                });

                // Update tab buttons (keep in sync for error indicators)
                var tabButtons = document.querySelectorAll('.switcher.navigator .tab');
                tabButtons.forEach(function(btn) { btn.classList.remove('active'); });
                var activeTabId = wizardPanels[currentStep];
                tabButtons.forEach(function(btn) {
                    if (btn.getAttribute('tab-id') === activeTabId) {
                        btn.classList.add('active');
                    }
                });

                // Show/hide navigation buttons
                var prevBtn = document.getElementById('wizardPrev');
                var nextBtn = document.getElementById('wizardNext');
                var saveBtn = document.getElementById('wizardSave');

                prevBtn.style.display = currentStep > 0 ? 'inline-block' : 'none';
                nextBtn.style.display = currentStep < wizardPanels.length - 1 ? 'inline-block' : 'none';
                saveBtn.style.display = currentStep === wizardPanels.length - 1 ? 'inline-block' : 'none';

                // Scroll to top of form
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            buildStepIndicator();
            updateWizard();

            document.getElementById('wizardNext').addEventListener('click', function() {
                if (currentStep < wizardPanels.length - 1) {
                    currentStep++;
                    updateWizard();
                }
            });

            document.getElementById('wizardPrev').addEventListener('click', function() {
                if (currentStep > 0) {
                    currentStep--;
                    updateWizard();
                }
            });

            // Allow clicking on completed steps to navigate back
            document.getElementById('wizardSteps').addEventListener('click', function(e) {
                var stepEl = e.target.closest('.wizard-step');
                if (!stepEl) return;
                var index = parseInt(stepEl.getAttribute('data-step-index'));
                if (index <= currentStep) {
                    currentStep = index;
                    updateWizard();
                }
            });
        })();
    </script>
@endpush
