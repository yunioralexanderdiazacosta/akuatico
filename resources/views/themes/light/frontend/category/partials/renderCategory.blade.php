@foreach ($categories as $category)
    <div class="col-lg-3 col-md-4 col-6 ">
        <a href="{{ route('listings',$category->id) }}">
            <div class="category-box d-flex justify-content-start">
                <div class="icon-box p-0">
                    @if(isset($category->mobile_app_image) && $category->mobile_app_image) <img class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2" src="{{ getFile($category->image_driver, $category->mobile_app_image) }}" alt="{{ optional($category->details)->name }}"/> @else <i class="{{ $category->icon }}"></i> @endif
                </div>
                <div>
                    <h5>@lang(optional($category->details)->name)</h5>
                    <span>{{ $category->getCategoryCount() }} @lang('Listings')</span>
                </div>
            </div>
        </a>
    </div>
@endforeach
