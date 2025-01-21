{{--<div class="aiz-category-menu bg-white rounded-0 border-top" id="category-sidebar" style="width:270px;">
    <ul class="list-unstyled categories no-scrollbar mb-0 text-left">
        @foreach (get_level_zero_categories()->take(10) as $key => $category)
            @php
                $category_name = $category->getTranslation('name');
            @endphp
            <li class="category-nav-element border border-top-0" data-id="{{ $category->id }}">
                <a href="{{ route('products.category', $category->slug) }}"
                    class="text-truncate text-dark px-4 fs-14 d-block hov-column-gap-1">
                    <img class="cat-image lazyload mr-2 opacity-60" src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ isset($category->catIcon->file_name) ? my_asset($category->catIcon->file_name) : static_asset('assets/img/placeholder.jpg') }}" width="16" alt="{{ $category_name }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    <span class="cat-name has-transition">{{ $category_name }}</span>
                </a>
                
                <div class="sub-cat-menu c-scrollbar-light border p-4 shadow-none">
                    <div class="c-preloader text-center absolute-center">
                        <i class="las la-spinner la-spin la-3x opacity-70"></i>
                    </div>
                </div>

            </li>
        @endforeach
    </ul>
</div> --}}
                    <div class="dropdown_nav" style="margin-top: 1px;">
                      <ul class="dropdown-menu-content text-left p-0 pb-2" aria-labelledby="dropdownMenuButton">
                            @foreach (get_level_zero_categories()->where('featured',1)->take(13) as $key => $category)
                                @php
                                    $category_name = $category->getTranslation('name');
                                @endphp
                                <li class="list-inline-item m-2 mb-0" data-id="{{ $category->id }}">
                                    <a href="{{ route('products.category', $category->slug) }}"
                                        class="">
                                        <div>
                                        <img class="cat-image lazyload mr-2 opacity-60" src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ isset($category->catIcon->file_name) ? my_asset($category->catIcon->file_name) : static_asset('assets/img/placeholder.jpg') }}" width="16" alt="{{ $category_name }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        <span class="">{{ $category_name }}</span>
                                        </div>
                                         @if($category->childrenCategories->count())
                                            <i class="float-right la la-angle-right"></i>
                                          @endif
                                    </a>
                                    
                                       <ul class="sub_dropdown text-left p-0 pb-2" aria-labelledby="dropdownMenuButton">
                                           @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $first_level_id)
                                              @php
                                             $subcat=\App\Models\Category::find($first_level_id);
                                             @endphp
                                       <li class="list-inline-item m-2 mb-0" data-id="8">
                                            <a href="{{ route('products.category', \App\Models\Category::find($first_level_id)->slug) }}" class="">
                                                <div class="d-flex">
                                                    <img
                                                    class="cat-image lazyload mr-2 opacity-60"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ uploaded_asset( $subcat->icon) }}"
                                                    width="16"
                                                    alt="{{  $subcat->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                >
                                                    {{ \App\Models\Category::find($first_level_id)->getTranslation('name') }}
                                                        
                                                    </div>
                                                  @if(count(\App\Utility\CategoryUtility::get_immediate_children_ids($first_level_id)))
                                                    <i class="float-right la la-angle-right"></i>
                                                    @endif
                                                   
                                              </a>
                                              <ul class="sub__sub_dropdown text-left p-0 pb-2" aria-labelledby="dropdownMenuButton">
                                                       @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($first_level_id) as $key => $second_level_id)
                                                        @php
                                                         $subsubcat=\App\Models\Category::find($second_level_id);
                                                         @endphp
                                                   <li class="list-inline-item m-2 mb-0" data-id="8">
                                                        <a href="{{ route('products.category', \App\Models\Category::find($second_level_id)->slug) }}" class="">
                                                            <div class="d-flex">
                                                                 <img
                                                                    class="cat-image lazyload mr-2 opacity-60"
                                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                                    data-src="{{ uploaded_asset($subsubcat->icon) }}"
                                                                    width="16"
                                                                    alt="{{ $subsubcat->getTranslation('name') }}"
                                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                                >
                                                                    {{ \App\Models\Category::find($second_level_id)->getTranslation('name') }}
                                                                    
                                                                </div>
                                                             
                                                               
                                                          </a>
                                                        
                                                    </li>
                                                        @endforeach
                                                  </ul>
                                            
                                        </li>
                                            @endforeach
                                      </ul>
                                </li>
                            @endforeach
                            
                      </ul>
                     
                    </div>