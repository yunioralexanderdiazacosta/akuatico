@extends(template().'layouts.app')
@section('title', 'directorio')

@section('content')
   @if (count($categories) > 0)
      <section class="category-filter-section">
         <div class="container">
            <div class="row g-3 mt-5" id="renderCategory">
                @include(template().'frontend.category.partials.renderCategory')
            </div>
         </div>
      </section>
      @else
       <div class="custom-not-found2">
           <img src="{{ asset(template(true).'img/no_data_found.png') }}" alt="{{ basicControl()->site_title }}"
                class="img-fluid">
       </div>
   @endif

@endsection

