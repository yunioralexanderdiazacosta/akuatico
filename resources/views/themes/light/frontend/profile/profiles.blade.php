@extends(template().'layouts.app')
@section('title',trans('Profiles'))

@section('banner_heading')
    @lang('All Profiles')
@endsection

@section('content')
    <section class="listing-section pb-0">
        <div class="container">
            <div class="row g-4">
                <div class="col-xl-12 col-lg-12">
                    <div class="main-content pb-4">
                        <div class="listing-topbar">
                            <div class="row align-items-center">
                                <div class="col">
                                    <button class="mt-2 mb-2 cmn-btn3" type="button" data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvasWithBothOptions"
                                            aria-controls="offcanvasWithBothOptions">
                                        <i class="fas fa-filter"></i> @lang('Filters')
                                    </button>
                                </div>
                                <div class="col justify-content-end d-flex">
                                    <div id="results-count">
                                        Showing <strong>{{ $all_profiles->firstItem() }} – {{ $all_profiles->lastItem() }}</strong> of <strong>{{ $all_profiles->total() }}</strong> results
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if( count($all_profiles) > 0)
                            <div class="row g-4">
                                @foreach($all_profiles as $key => $profile)
                                    <div class="col-xxl-4 col-md-6">
                                        <div class="card p-0 overflow-hidden border-0 shadow-sm">
                                            <div class="creator p-0">
                                                <div class="img-box position-relative">
                                                    <img class="cover w-100" src="{{ getFile($profile->cover_image_driver, $profile->cover_image) }}" alt="image" style="height: 120px; object-fit: cover;">
                                                    <div class="position-absolute start-50 translate-middle-x" style="bottom: -30px;">
                                                        <a href="{{ route('profile', $profile->username) }}">
                                                            <img class="profile-img rounded-circle border border-4 border-white" src="{{ getFile($profile->image_driver, $profile->image) }}" alt="" style="width: 80px; height: 80px; object-fit: cover;">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="text-box mt-40 p-3 text-center">
                                                    <h5 class="mb-1">
                                                        <a href="{{ route('profile', $profile->username) }}" class="text-dark">
                                                            @lang($profile->firstname) @lang($profile->lastname)
                                                        </a>
                                                        @if($profile->identity_verify ==  2 && $profile->address_verify ==  2)
                                                            <i class="fas fa-check-circle text-primary small" aria-hidden="true"></i>
                                                        @endif
                                                    </h5>
                                                    <p class="mb-1 text-muted small">@lang('Member since') {{ dateTime($profile->created_at) }}</p>
                                                    
                                                    @if($profile->category_id)
                                                        <p class="mb-2 text-primary small">
                                                            <i class="fas fa-tags me-1"></i>
                                                            <span class="">@lang('Category') : </span> {{ Str::limit($profile->getCategoriesName(), 40) }}
                                                        </p>
                                                    @endif
                                                    @if($profile->getSubCategoriesName())
                                                        <p class="mb-2 text-primary small">
                                                            <i class="fas fa-tags me-1"></i>
                                                            <span class="">@lang('Subcategory') : </span> {{ Str::limit($profile->getSubCategoriesName(), 40) }}
                                                        </p>
                                                    @endif

                                                    <div class="d-flex justify-content-around mt-3 border-top pt-3">
                                                        <div class="text-center">
                                                            <h6 class="mb-0">{{ $profile->get_listing_count }}</h6>
                                                            <span class="text-muted small">@lang('Listings')</span>
                                                        </div>
                                                        <div class="text-center">
                                                            <h6 class="mb-0">{{ $profile->total_views_count }}</h6>
                                                            <span class="text-muted small">@lang('Views')</span>
                                                        </div>
                                                        <div class="text-center">
                                                            <h6 class="mb-0">{{ $profile->follower_count }}</h6>
                                                            <span class="text-muted small">@lang('Followers')</span>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3">
                                                        <a href="{{ route('profile', $profile->username) }}" class="cmn-btn w-100 btn-sm">@lang('View Profile')</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="custom-not-found">
                                <img src="{{ asset(template(true).'img/no_data_found.png') }}" alt="image" class="img-fluid">
                            </div>
                        @endif
                    </div>
                    <div class="d-flex justify-content-center mb-4">
                        <nav aria-label="Page navigation example mt-3">
                            {{ $all_profiles->appends($_GET)->links(template().'partials.pagination') }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
         aria-labelledby="offcanvasWithBothOptionsLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">@lang('Filters')</h5>
            <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fas fa-arrow-left"></i></button>
        </div>
        <form action="{{ route('profiles') }}" method="get" id="profile-filter-form">
            <div class="offcanvas-body">
                <div class="widget-title">
                    <h6>@lang('Search')</h6>
                </div>
                <div class="row g-4">
                    <div class="col-12">
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', request()->name) }}" autocomplete="off"
                               placeholder="@lang('Profile name')"/>
                    </div>
                </div>
                <hr class="cmn-hr2">
                <div class="widget-title">
                    <h6>@lang('Filter by Category')</h6>
                </div>
                <div class="row g-4">
                    <div class="col-12">
                        <div id="formModal">
                            <select id="category_id" class="listing__category__select2 form-control" name="category[]" multiple>
                                <option value="all" @if(request()->category && in_array('all', request()->category)) selected @endif>@lang('All Category')</option>
                                @foreach($all_categories as $category)
                                    <option value="{{ $category->id }}"
                                            @if(request()->category && in_array($category->id, request()->category)) selected @endif> @lang(optional($category->details)->name)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <hr class="cmn-hr2">
                <div class="widget-title">
                    <h6>@lang('Filter by Subcategory')</h6>
                </div>
                <div class="row g-4">
                    <div class="col-12">
                        <div id="formModal">
                            <select id="subcategory_id" class="listing__subcategory__select2 form-control" name="subcategory[]" multiple>
                                <option value="all" @if(request()->subcategory && in_array('all', request()->subcategory)) selected @endif>@lang('All Subcategory')</option>
                                @foreach($all_subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}"
                                            data-parent="{{ $subcategory->parent_id }}"
                                            @if(request()->subcategory && in_array($subcategory->id, request()->subcategory)) selected @endif> @lang(optional($subcategory->details)->name)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <hr class="cmn-hr2">
                <button class="btn-custom cmn-btn w-100" type="submit">@lang('submit')</button>
            </div>
        </form>
    </div>
@endsection

@push('style')
    <style>
        .mt-40 {
            margin-top: 40px;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $(".listing__category__select2").select2({
                width: '100%',
                placeholder: '@lang("Select Categories")',
            });

            $(".listing__subcategory__select2").select2({
                width: '100%',
                placeholder: '@lang("Select Subcategories")',
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
        });
    </script>
@endpush
