@extends('backend.layouts.app')

@section('content')

  @php
    CoreComponentRepository::instantiateShopRepository();
    CoreComponentRepository::initializeCache();
  @endphp

  <div class="page-content">
    <div class="aiz-titlebar px-md-2rem border-bottom border-gray mt-2 px-3 pb-2 text-left">
      <div class="row align-items-center">
        <div class="col">
          <h1 class="h3">{{ translate('Add New Product') }}</h1>
        </div>
        {{-- <div class="col text-right">
                <a class="btn has-transition btn-xs p-0 hov-svg-danger" href="{{ route('home') }}" 
                    target="_blank" data-toggle="tooltip" data-placement="top" data-title="{{ translate('View Tutorial Video') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19.887" height="16" viewBox="0 0 19.887 16">
                        <path id="_42fbab5a39cb8436403668a76e5a774b" data-name="42fbab5a39cb8436403668a76e5a774b" d="M18.723,8H5.5A3.333,3.333,0,0,0,2.17,11.333v9.333A3.333,3.333,0,0,0,5.5,24h13.22a3.333,3.333,0,0,0,3.333-3.333V11.333A3.333,3.333,0,0,0,18.723,8Zm-3.04,8.88-5.47,2.933a1,1,0,0,1-1.473-.88V13.067a1,1,0,0,1,1.473-.88l5.47,2.933a1,1,0,0,1,0,1.76Zm-5.61-3.257L14.5,16l-4.43,2.377Z" transform="translate(-2.17 -8)" fill="#9da3ae"/>
                    </svg>
                </a>
            </div> --}}
      </div>
    </div>

    <div class="d-sm-flex">
      <!-- page side nav -->
      <div class="page-side-nav c-scrollbar-light px-3 py-2">
        <ul class="nav nav-tabs flex-sm-column border-0" role="tablist" aria-orientation="vertical">
          <!-- General -->
          <li class="nav-item">
            <a class="nav-link" id="general-tab" data-toggle="tab" data-target="#general" type="button"
              href="#general" role="tab" aria-controls="general" aria-selected="true">
              {{ translate('General') }}
            </a>
          </li>
          <!-- Files & Media -->
          <li class="nav-item">
            <a class="nav-link" id="files-and-media-tab" data-toggle="tab" data-target="#files_and_media"
              type="button" href="#files_and_media" role="tab" aria-controls="files_and_media"
              aria-selected="false">
              {{ translate('Files & Media') }}
            </a>
          </li>
          <!-- Price & Stock -->
          <li class="nav-item">
            <a class="nav-link" id="price-and-stocks-tab" data-toggle="tab" data-target="#price_and_stocks"
              type="button" href="#price_and_stocks" role="tab" aria-controls="price_and_stocks"
              aria-selected="false">
              {{ translate('Price & Stock') }}
            </a>
          </li>
          <!-- SEO -->
          <li class="nav-item">
            <a class="nav-link" id="seo-tab" data-toggle="tab" data-target="#seo" type="button"
              href="#seo" role="tab" aria-controls="seo" aria-selected="false">
              {{ translate('SEO') }}
            </a>
          </li>
          <!-- Shipping -->
          <li class="nav-item">
            <a class="nav-link" id="shipping-tab" data-toggle="tab" data-target="#shipping" type="button"
              href="#shipping" role="tab" aria-controls="shipping" aria-selected="false">
              {{ translate('Shipping') }}
            </a>
          </li>
        </ul>
      </div>

      <!-- tab content -->
      <div class="flex-grow-1 p-sm-3 p-lg-2rem mb-2rem mb-md-0">
        <!-- Error Meassages -->
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form id="choice_form" action="{{ route('products.store') }}" method="POST"
          enctype="multipart/form-data" enctype="multipart/form-data">
          @csrf
          <div class="tab-content">
            <!-- General -->
            <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general-tab">
              <div class="p-sm-2rem bg-white p-3">
                <!-- Product Information -->
                <h5 class="fs-17 fw-700 mb-3 pb-3" style="border-bottom: 1px dashed #e4e5eb;">
                  {{ translate('Product Information') }}</h5>
                <div class="w-100">
                  <div class="row">
                    <div class="col-xxl-7 col-xl-6">
                      <!-- Product Name -->
                      <div class="form-group row">
                        <label class="col-xxl-3 col-from-label fs-13">{{ translate('Product Name') }} <span
                            class="text-danger">*</span></label>
                        <div class="col-xxl-9">
                          <input class="form-control" name="name" type="text" value="{{ old('name') }}"
                            placeholder="{{ translate('Product Name') }}" onchange="update_sku()">
                        </div>
                      </div>
                      <!-- Brand -->
                      <div class="form-group row" id="brand">
                        <label class="col-xxl-3 col-from-label fs-13">{{ translate('Brand') }}</label>
                        <div class="col-xxl-9">
                          <select class="form-control aiz-selectpicker" id="brand_id" name="brand_id"
                            data-live-search="true">
                            <option value="">{{ translate('Select Brand') }}</option>
                            @foreach (\App\Models\Brand::all() as $brand)
                              <option value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>
                                {{ $brand->getTranslation('name') }}</option>
                            @endforeach
                          </select>
                          <small
                            class="text-muted">{{ translate("You can choose a brand if you'd like to display your product by brand.") }}</small>
                        </div>
                      </div>
                      <!-- Unit -->
                      <div class="form-group row">
                        <label class="col-xxl-3 col-from-label fs-13">{{ translate('Unit') }} <span
                            class="text-danger">*</span></label>
                        <div class="col-xxl-9">
                          <input class="form-control" name="unit" type="text"
                            value="{{ old('unit') }}"
                            placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}">
                        </div>
                      </div>
                      <!-- Weight -->
                      <div class="form-group row">
                        <label class="col-xxl-3 col-from-label fs-13">{{ translate('Weight') }}
                          <small>({{ translate('In Kg') }})</small></label>
                        <div class="col-xxl-9">
                          <input class="form-control" name="weight" type="number" value="0.00"
                            step="0.01" placeholder="0.00">
                        </div>
                      </div>
                      <!-- Quantity -->
                      <div class="form-group row">
                        <label class="col-xxl-3 col-from-label fs-13">{{ translate('Minimum Purchase Qty') }}
                          <span class="text-danger">*</span></label>
                        <div class="col-xxl-9">
                          <input class="form-control" name="min_qty" type="number" value="1"
                            lang="en" min="1">
                          <small
                            class="text-muted">{{ translate('The minimum quantity needs to be purchased by your customer.') }}</small>
                        </div>
                      </div>
                      <!-- Tags -->
                      <div class="form-group row">
                        <label class="col-xxl-3 col-from-label fs-13">{{ translate('Tags') }} <span
                            class="text-danger">*</span></label>
                        <div class="col-xxl-9">
                          <input class="form-control aiz-tag-input" name="tags[]" type="text"
                            placeholder="{{ translate('Type and hit enter to add a tag') }}">
                          <small
                            class="text-muted">{{ translate('This is used for search. Input those words by which cutomer can find this product.') }}</small>
                        </div>
                      </div>

                      @if (addon_is_activated('pos_system'))
                        <!-- Barcode -->
                        <div class="form-group row">
                          <label class="col-xxl-3 col-from-label fs-13">{{ translate('Barcode') }}</label>
                          <div class="col-xxl-9">
                            <input class="form-control" name="barcode" type="text"
                              value="{{ old('barcode') }}" placeholder="{{ translate('Barcode') }}">
                          </div>
                        </div>
                      @endif

                      @if (addon_is_activated('refund_request'))
                        <!-- refund_request -->
                        <div class="form-group row mb-4 mt-4">
                          <label class="col-xxl-3 col-from-label fs-13">{{ translate('Refundable') }}</label>
                          <div class="col-xxl-9">
                            <label class="aiz-switch aiz-switch-success mb-0">
                              <input name="refundable" type="checkbox" value="1" checked>
                              <span></span>
                            </label>
                          </div>
                        </div>
                      @endif
                    </div>

                    <!-- Product Category -->
                    <div class="col-xxl-5 col-xl-6">
                      <div class="card">
                        <div class="card-header">
                          <h5 class="h6 mb-0">{{ translate('Product Category') }}</h5>
                          <h6 class="fs-13 float-right mb-0">
                            {{ translate('Select Main') }}
                            <span class="position-relative main-category-info-icon">
                              <i class="las la-question-circle fs-18 text-info"></i>
                              <span
                                class="main-category-info bg-soft-info position-absolute d-none border p-2">{{ translate('This will be used for commission based calculations and homepage category wise product Show.') }}</span>
                            </span>
                          </h6>
                        </div>
                        <div class="card-body">
                          <div class="h-300px c-scrollbar-light overflow-auto">
                            <ul class="hummingbird-treeview-converter list-unstyled"
                              data-checkbox-name="category_ids[]" data-radio-name="category_id">
                              @foreach ($categories as $category)
                                <li id="{{ $category->id }}">{{ $category->getTranslation('name') }}</li>
                                @foreach ($category->childrenCategories as $childCategory)
                                  @include('backend.product.products.child_category', [
                                      'child_category' => $childCategory,
                                  ])
                                @endforeach
                              @endforeach
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Description -->
                  <div class="form-group">
                    <label class="fs-13">{{ translate('Description') }}</label>
                    <div class="">
                      <textarea class="aiz-text-editor" name="description">{{ old('description') }}</textarea>
                    </div>
                  </div>
                </div>

                <!-- Status -->
                <h5 class="fs-17 fw-700 mb-3 mt-5 pb-3" style="border-bottom: 1px dashed #e4e5eb;">
                  {{ translate('Status') }}</h5>
                <div class="w-100">
                  <!-- Featured -->
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{ translate('Featured') }}</label>
                    <div class="col-md-9">
                      <label class="aiz-switch aiz-switch-success d-block mb-0">
                        <input name="featured" type="checkbox" value="1">
                        <span></span>
                      </label>
                      <small
                        class="text-muted">{{ translate('If you enable this, this product will be granted as a featured product.') }}</small>
                    </div>
                  </div>
                  <!-- Todays Deal -->
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{ translate('Todays Deal') }}</label>
                    <div class="col-md-9">
                      <label class="aiz-switch aiz-switch-success d-block mb-0">
                        <input name="todays_deal" type="checkbox" value="1">
                        <span></span>
                      </label>
                      <small
                        class="text-muted">{{ translate('If you enable this, this product will be granted as a todays deal product.') }}</small>
                    </div>
                  </div>
                </div>

                <!-- Flash Deal -->
                <h5 class="fs-17 fw-700 mb-3 mt-4 pb-3" style="border-bottom: 1px dashed #e4e5eb;">
                  {{ translate('Flash Deal') }}
                  <small
                    class="text-muted">({{ translate('If you want to select this product as a flash deal, you can use it') }})</small>
                </h5>
                <div class="w-100">
                  <!-- Add To Flash -->
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{ translate('Add To Flash') }}</label>
                    <div class="col-xxl-9">
                      <select class="form-control aiz-selectpicker" id="flash_deal" name="flash_deal_id">
                        <option value="">{{ translate('Choose Flash Title') }}</option>
                        @foreach (\App\Models\FlashDeal::where('status', 1)->get() as $flash_deal)
                          <option value="{{ $flash_deal->id }}">
                            {{ $flash_deal->title }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <!-- Discount -->
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{ translate('Discount') }}</label>
                    <div class="col-xxl-9">
                      <input class="form-control" name="flash_discount" type="number" value="0"
                        min="0" step="0.01">
                    </div>
                  </div>
                  <!-- Discount Type -->
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{ translate('Discount Type') }}</label>
                    <div class="col-xxl-9">
                      <select class="form-control aiz-selectpicker" id="flash_discount_type"
                        name="flash_discount_type">
                        <option value="">{{ translate('Choose Discount Type') }}</option>
                        <option value="amount">{{ translate('Flat') }}</option>
                        <option value="percent">{{ translate('Percent') }}</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Vat & TAX -->
                <h5 class="fs-17 fw-700 mb-3 mt-4 pb-3" style="border-bottom: 1px dashed #e4e5eb;">
                  {{ translate('Vat & TAX') }}</h5>
                <div class="w-100">
                  @foreach (\App\Models\Tax::where('tax_status', 1)->get() as $tax)
                    <label for="name">
                      {{ $tax->name }}
                      <input name="tax_id[]" type="hidden" value="{{ $tax->id }}">
                    </label>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <input class="form-control" name="tax[]" type="number" value="0"
                          lang="en" min="0" step="0.01"
                          placeholder="{{ translate('Tax') }}">
                      </div>
                      <div class="form-group col-md-6">
                        <select class="form-control aiz-selectpicker" name="tax_type[]">
                          <option value="amount">{{ translate('Flat') }}</option>
                          <option value="percent">{{ translate('Percent') }}</option>
                        </select>
                      </div>
                    </div>
                  @endforeach
                </div>

                <!-- Product Commission -->
                <h5 class="fs-17 fw-700 mb-3 mt-4 pb-3" style="border-bottom: 1px dashed #e4e5eb;">
                  {{ translate('Product commission') }}</h5>
                <div class="w-100">
                  <label for="name">
                    Commission
                  </label>

                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <input class="form-control" name="commission" type="number" value="0"
                        lang="en" min="0" step="0.01" placeholder="{{ translate('Product Commission') }}">
                    </div>
                    <div class="form-group col-md-6">
                      <select class="form-control aiz-selectpicker" name="commission_type">
                        <option value="flat">{{ translate('Flat') }}</option>
                        <option value="percent">{{ translate('Percent') }}</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Files & Media -->
            <div class="tab-pane fade" id="files_and_media" role="tabpanel"
              aria-labelledby="files-and-media-tab">
              <div class="p-sm-2rem bg-white p-3">
                <!-- Product Files & Media -->
                <h5 class="fs-17 fw-700 mb-3 pb-3" style="border-bottom: 1px dashed #e4e5eb;">
                  {{ translate('Product Files & Media') }}</h5>
                <div class="w-100">
                  <!-- Gallery Images -->
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label"
                      for="signinSrEmail">{{ translate('Gallery Images') }} <small>(600x600)</small></label>
                    <div class="col-md-9">
                      <div class="input-group" data-toggle="aizuploader" data-type="image"
                        data-multiple="true">
                        <div class="input-group-prepend">
                          <div class="input-group-text bg-soft-secondary font-weight-medium">
                            {{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input class="selected-files" name="photos" type="hidden">
                      </div>
                      <div class="file-preview box sm">
                      </div>
                      <small
                        class="text-muted">{{ translate('These images are visible in product details page gallery. Use 600x600 sizes images.') }}</small>
                    </div>
                  </div>
                  <!-- Thumbnail Image -->
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label"
                      for="signinSrEmail">{{ translate('Thumbnail Image') }} <small>(300x300)</small></label>
                    <div class="col-md-9">
                      <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                          <div class="input-group-text bg-soft-secondary font-weight-medium">
                            {{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input class="selected-files" name="thumbnail_img" type="hidden">
                      </div>
                      <div class="file-preview box sm">
                      </div>
                      <small
                        class="text-muted">{{ translate('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.') }}</small>
                    </div>
                  </div>
                </div>
                <!-- Video Provider -->
                <div class="form-group row">
                  <label class="col-md-3 col-from-label">{{ translate('Video Provider') }}</label>
                  <div class="col-md-9">
                    <select class="form-control aiz-selectpicker" id="video_provider" name="video_provider">
                      <option value="youtube" @selected(old('video_provider') == 'youtube')>{{ translate('Youtube') }}</option>
                      <option value="dailymotion" @selected(old('video_provider') == 'dailymotion')>{{ translate('Dailymotion') }}
                      </option>
                      <option value="vimeo" @selected(old('video_provider') == 'vimeo')>{{ translate('Vimeo') }}</option>
                    </select>
                  </div>
                </div>
                <!-- Video Link -->
                <div class="form-group row">
                  <label class="col-md-3 col-from-label">{{ translate('Video Link') }}</label>
                  <div class="col-md-9">
                    <input class="form-control" name="video_link" type="text"
                      value="{{ old('video_link') }}" placeholder="{{ translate('Video Link') }}">
                    <small
                      class="text-muted">{{ translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.") }}</small>
                  </div>
                </div>
                <!-- PDF Specification -->
                <div class="form-group row">
                  <label class="col-md-3 col-form-label"
                    for="signinSrEmail">{{ translate('PDF Specification') }}</label>
                  <div class="col-md-9">
                    <div class="input-group" data-toggle="aizuploader" data-type="document">
                      <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                          {{ translate('Browse') }}</div>
                      </div>
                      <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                      <input class="selected-files" name="pdf" type="hidden">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Price & Stock -->
            <div class="tab-pane fade" id="price_and_stocks" role="tabpanel"
              aria-labelledby="price-and-stocks-tab">
              <div class="p-sm-2rem bg-white p-3">
                <!-- tab Title -->
                <h5 class="fs-17 fw-700 mb-3 pb-3" style="border-bottom: 1px dashed #e4e5eb;">
                  {{ translate('Product price & stock') }}</h5>
                <div class="w-100">
                  <!-- Colors -->
                  <div class="form-group row gutters-5">
                    <div class="col-md-3">
                      <input class="form-control" type="text" value="{{ translate('Colors') }}" disabled>
                    </div>
                    <div class="col-md-8">
                      <select class="form-control aiz-selectpicker" id="colors" name="colors[]"
                        data-live-search="true" data-selected-text-format="count" multiple disabled>
                        @foreach (\App\Models\Color::orderBy('name', 'asc')->get() as $key => $color)
                          <option
                            data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"
                            value="{{ $color->code }}"></option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-1">
                      <label class="aiz-switch aiz-switch-success mb-0">
                        <input name="colors_active" type="checkbox" value="1">
                        <span></span>
                      </label>
                    </div>
                  </div>
                  <!-- Attributes -->
                  <div class="form-group row gutters-5">
                    <div class="col-md-3">
                      <input class="form-control" type="text" value="{{ translate('Attributes') }}"
                        disabled>
                    </div>
                    <div class="col-md-8">
                      <select class="form-control aiz-selectpicker" id="choice_attributes"
                        name="choice_attributes[]" data-selected-text-format="count" data-live-search="true"
                        data-placeholder="{{ translate('Choose Attributes') }}" multiple>
                        @foreach (\App\Models\Attribute::all() as $key => $attribute)
                          <option value="{{ $attribute->id }}">{{ $attribute->getTranslation('name') }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div>
                    <p>
                      {{ translate('Choose the attributes of this product and then input values of each attribute') }}
                    </p>
                    <br>
                  </div>

                  <!-- choice options -->
                  <div class="customer_choice_options mb-4" id="customer_choice_options">

                  </div>

                  <!-- Unit price -->
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{ translate('Unit price') }} <span
                        class="text-danger">*</span></label>
                    <div class="col-md-6">
                      <input class="form-control" name="unit_price" type="number" value="0"
                        lang="en" min="0" step="0.01"
                        placeholder="{{ translate('Unit price') }}">
                    </div>
                  </div>
                  <!-- Discount Date Range -->
                  <div class="form-group row">
                    <label class="col-sm-3 control-label"
                      for="start_date">{{ translate('Discount Date Range') }}</label>
                    <div class="col-sm-9">
                      <input class="form-control aiz-date-range" name="date_range" data-time-picker="true"
                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " type="text"
                        placeholder="{{ translate('Select Date') }}" autocomplete="off">
                    </div>
                  </div>
                  <!-- Discount -->
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{ translate('Discount') }} <span
                        class="text-danger">*</span></label>
                    <div class="col-md-6">
                      <input class="form-control" name="discount" type="number" value="0"
                        lang="en" min="0" step="0.01"
                        placeholder="{{ translate('Discount') }}">
                    </div>
                    <div class="col-md-3">
                      <select class="form-control aiz-selectpicker" name="discount_type">
                        <option value="amount" @selected(old('discount_type') == 'amount')>{{ translate('Flat') }}</option>
                        <option value="percent" @selected(old('discount_type') == 'percent')>{{ translate('Percent') }}
                        </option>
                      </select>
                    </div>
                  </div>

                  @if (addon_is_activated('club_point'))
                    <!-- club point -->
                    <div class="form-group row">
                      <label class="col-md-3 col-from-label">
                        {{ translate('Set Point') }}
                      </label>
                      <div class="col-md-6">
                        <input class="form-control" name="earn_point" type="number" value="0"
                          lang="en" min="0" step="1" placeholder="{{ translate('1') }}">
                      </div>
                    </div>
                  @endif

                  <div id="show-hide-div">
                    <!-- Quantity -->
                    <div class="form-group row">
                      <label class="col-md-3 col-from-label">{{ translate('Quantity') }} <span
                          class="text-danger">*</span></label>
                      <div class="col-md-6">
                        <input class="form-control" name="current_stock" type="number" value="0"
                          lang="en" min="0" step="1"
                          placeholder="{{ translate('Quantity') }}">
                      </div>
                    </div>
                    <!-- SKU -->
                    <div class="form-group row">
                      <label class="col-md-3 col-from-label">
                        {{ translate('SKU') }}
                      </label>
                      <div class="col-md-6">
                        <input class="form-control" name="sku" type="text"
                          value="{{ old('sku') }}" placeholder="{{ translate('SKU') }}">
                      </div>
                    </div>
                  </div>
                  <!-- External link -->
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">
                      {{ translate('External link') }}
                    </label>
                    <div class="col-md-9">
                      <input class="form-control" name="external_link" type="text"
                        value="{{ old('external_link') }}" placeholder="{{ translate('External link') }}">
                      <small
                        class="text-muted">{{ translate('Leave it blank if you do not use external site link') }}</small>
                    </div>
                  </div>
                  <!-- External link button text -->
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">
                      {{ translate('External link button text') }}
                    </label>
                    <div class="col-md-9">
                      <input class="form-control" name="external_link_btn" type="text"
                        value="{{ old('external_link_btn') }}"
                        placeholder="{{ translate('External link button text') }}">
                      <small
                        class="text-muted">{{ translate('Leave it blank if you do not use external site link') }}</small>
                    </div>
                  </div>
                  <br>
                  <!-- sku combination -->
                  <div class="sku_combination" id="sku_combination">

                  </div>
                </div>

                <!-- Low Stock Quantity -->
                <h5 class="fs-17 fw-700 mb-3 pb-3" style="border-bottom: 1px dashed #e4e5eb;">
                  {{ translate('Low Stock Quantity Warning') }}</h5>
                <div class="w-100 mb-3">
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">
                      {{ translate('Quantity') }}
                    </label>
                    <div class="col-md-9">
                      <input class="form-control" name="low_stock_quantity" type="number" value="1"
                        min="0" step="1">
                    </div>
                  </div>
                </div>

                <!-- Stock Visibility State -->
                <h5 class="fs-17 fw-700 mb-3 pb-3" style="border-bottom: 1px dashed #e4e5eb;">
                  {{ translate('Stock Visibility State') }}</h5>
                <div class="w-100">
                  <!-- Show Stock Quantity -->
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{ translate('Show Stock Quantity') }}</label>
                    <div class="col-md-9">
                      <label class="aiz-switch aiz-switch-success mb-0">
                        <input name="stock_visibility_state" type="radio" value="quantity" checked>
                        <span></span>
                      </label>
                    </div>
                  </div>
                  <!-- Show Stock With Text Only -->
                  <div class="form-group row">
                    <label
                      class="col-md-3 col-from-label">{{ translate('Show Stock With Text Only') }}</label>
                    <div class="col-md-9">
                      <label class="aiz-switch aiz-switch-success mb-0">
                        <input name="stock_visibility_state" type="radio" value="text">
                        <span></span>
                      </label>
                    </div>
                  </div>
                  <!-- Hide Stock -->
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{ translate('Hide Stock') }}</label>
                    <div class="col-md-9">
                      <label class="aiz-switch aiz-switch-success mb-0">
                        <input name="stock_visibility_state" type="radio" value="hide">
                        <span></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- SEO -->
            <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
              <div class="p-sm-2rem bg-white p-3">
                <!-- tab Title -->
                <h5 class="fs-17 fw-700 mb-3 pb-3" style="border-bottom: 1px dashed #e4e5eb;">
                  {{ translate('SEO Meta Tags') }}</h5>
                <div class="w-100">
                  <!-- Meta Title -->
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                    <div class="col-md-9">
                      <input class="form-control" name="meta_title" type="text"
                        value="{{ old('meta_title') }}" placeholder="{{ translate('Meta Title') }}">
                    </div>
                  </div>
                  <!-- Description -->
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                    <div class="col-md-9">
                      <textarea class="form-control" name="meta_description" rows="8">{{ old('meta_description') }}</textarea>
                    </div>
                  </div>
                  <!--Meta Image -->
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label"
                      for="signinSrEmail">{{ translate('Meta Image') }}</label>
                    <div class="col-md-9">
                      <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                          <div class="input-group-text bg-soft-secondary font-weight-medium">
                            {{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input class="selected-files" name="meta_img" type="hidden">
                      </div>
                      <div class="file-preview box sm">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Shipping -->
            <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
              <div class="p-sm-2rem bg-white p-3">
                <!-- Shipping Configuration -->
                <h5 class="fs-17 fw-700 mb-3 pb-3" style="border-bottom: 1px dashed #e4e5eb;">
                  {{ translate('Shipping Configuration') }}</h5>
                <div class="w-100">
                  <!-- Cash On Delivery -->
                  @if (get_setting('cash_payment') == '1')
                    <div class="form-group row">
                      <label class="col-md-3 col-from-label">{{ translate('Cash On Delivery') }}</label>
                      <div class="col-md-9">
                        <label class="aiz-switch aiz-switch-success mb-0">
                          <input name="cash_on_delivery" type="checkbox" value="1" checked="">
                          <span></span>
                        </label>
                      </div>
                    </div>
                  @else
                    <p>
                      {{ translate('Cash On Delivery option is disabled. Activate this feature from here') }}
                      <a class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index', 'shipping_configuration.edit', 'shipping_configuration.update']) }}"
                        href="{{ route('activation.index') }}">
                        <span class="aiz-side-nav-text">{{ translate('Cash Payment Activation') }}</span>
                      </a>
                    </p>
                  @endif

                  @if (get_setting('shipping_type') == 'product_wise_shipping')
                    <!-- Free Shipping -->
                    <div class="form-group row">
                      <label class="col-md-3 col-from-label">{{ translate('Free Shipping') }}</label>
                      <div class="col-md-9">
                        <label class="aiz-switch aiz-switch-success mb-0">
                          <input name="shipping_type" type="radio" value="free" checked>
                          <span></span>
                        </label>
                      </div>
                    </div>
                    <!-- Flat Rate -->
                    <div class="form-group row">
                      <label class="col-md-3 col-from-label">{{ translate('Flat Rate') }}</label>
                      <div class="col-md-9">
                        <label class="aiz-switch aiz-switch-success mb-0">
                          <input name="shipping_type" type="radio" value="flat_rate">
                          <span></span>
                        </label>
                      </div>
                    </div>
                    <!-- Shipping cost -->
                    <div class="flat_rate_shipping_div" style="display: none">
                      <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{ translate('Shipping cost') }}</label>
                        <div class="col-md-9">
                          <input class="form-control" name="flat_shipping_cost" type="number"
                            value="0" lang="en" min="0" step="0.01"
                            placeholder="{{ translate('Shipping cost') }}">
                        </div>
                      </div>
                    </div>
                    <!-- Is Product Quantity Mulitiply -->
                    <div class="form-group row">
                      <label
                        class="col-md-3 col-from-label">{{ translate('Is Product Quantity Mulitiply') }}</label>
                      <div class="col-md-9">
                        <label class="aiz-switch aiz-switch-success mb-0">
                          <input name="is_quantity_multiplied" type="checkbox" value="1">
                          <span></span>
                        </label>
                      </div>
                    </div>
                  @else
                    <p>
                      {{ translate('Product wise shipping cost is disable. Shipping cost is configured from here') }}
                      <a class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index', 'shipping_configuration.edit', 'shipping_configuration.update']) }}"
                        href="{{ route('shipping_configuration.index') }}">
                        <span class="aiz-side-nav-text">{{ translate('Shipping Configuration') }}</span>
                      </a>
                    </p>
                  @endif
                </div>

                <!-- Estimate Shipping Time -->
                <h5 class="fs-17 fw-700 mb-3 mt-4 pb-3" style="border-bottom: 1px dashed #e4e5eb;">
                  {{ translate('Estimate Shipping Time') }}</h5>
                <div class="w-100">
                  <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{ translate('Shipping Days') }}</label>
                    <div class="col-md-9">
                      <div class="input-group">
                        <input class="form-control" name="est_shipping_days" type="number" min="1"
                          step="1" placeholder="{{ translate('Shipping Days') }}">
                        <div class="input-group-prepend">
                          <span class="input-group-text"
                            id="inputGroupPrepend">{{ translate('Days') }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Save Button -->
            <div class="mt-4 text-right">
              <button
                class="btn btn-light w-230px btn-md rounded-2 fs-14 fw-700 shadow-secondary border-soft-secondary action-btn mx-2"
                name="button" type="submit"
                value="unpublish">{{ translate('Save & Unpublish') }}</button>
              <button
                class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success action-btn mx-2"
                name="button" type="submit" value="publish">{{ translate('Save & Publish') }}</button>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

@section('script')
  <!-- Treeview js -->
  <script src="{{ static_asset('assets/js/hummingbird-treeview.js') }}"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $("#treeview").hummingbird();

      var main_id = '{{ old('category_id') }}';
      var selected_ids = [];
      @if (old('category_ids'))
        selected_ids = @json(old('category_ids'));
      @endif
      for (let i = 0; i < selected_ids.length; i++) {
        const element = selected_ids[i];
        $('#treeview input:checkbox#' + element).prop('checked', true);
        $('#treeview input:checkbox#' + element).parents("ul").css("display", "block");
        $('#treeview input:checkbox#' + element).parents("li").children('.las').removeClass("la-plus")
          .addClass('la-minus');
      }
      if (main_id) {
        $('#treeview input:radio[value=' + main_id + ']').prop('checked', true);
      }
    });

    $('form').bind('submit', function(e) {
      if ($(".action-btn").attr('attempted') == 'true') {
        //stop submitting the form because we have already clicked submit.
        e.preventDefault();
      } else {
        $(".action-btn").attr("attempted", 'true');
      }
      // Disable the submit button while evaluating if the form should be submitted
      // $("button[type='submit']").prop('disabled', true);

      // var valid = true;

      // if (!valid) {
      // e.preventDefault();

      ////Reactivate the button if the form was not submitted
      // $("button[type='submit']").button.prop('disabled', false);
      // }
    });

    $("[name=shipping_type]").on("change", function() {
      $(".flat_rate_shipping_div").hide();

      if ($(this).val() == 'flat_rate') {
        $(".flat_rate_shipping_div").show();
      }

    });

    function add_more_customer_choice_option(i, name) {
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: '{{ route('products.add-more-choice-option') }}',
        data: {
          attribute_id: i
        },
        success: function(data) {
          var obj = JSON.parse(data);
          $('#customer_choice_options').append('\
                  <div class="form-group row">\
                      <div class="col-md-3">\
                          <input type="hidden" name="choice_no[]" value="' + i + '">\
                          <input type="text" class="form-control" name="choice[]" value="' + name +
            '" placeholder="{{ translate('Choice Title') }}" readonly>\
                      </div>\
                      <div class="col-md-8">\
                          <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_' + i + '[]" multiple>\
                              ' + obj + '\
                          </select>\
                      </div>\
                  </div>');
          AIZ.plugins.bootstrapSelect('refresh');
        }
      });


    }

    $('input[name="colors_active"]').on('change', function() {
      if (!$('input[name="colors_active"]').is(':checked')) {
        $('#colors').prop('disabled', true);
        AIZ.plugins.bootstrapSelect('refresh');
      } else {
        $('#colors').prop('disabled', false);
        AIZ.plugins.bootstrapSelect('refresh');
      }
      update_sku();
    });

    $(document).on("change", ".attribute_choice", function() {
      update_sku();
    });

    $('#colors').on('change', function() {
      update_sku();
    });

    $('input[name="unit_price"]').on('keyup', function() {
      update_sku();
    });

    $('input[name="name"]').on('keyup', function() {
      update_sku();
    });

    function delete_row(em) {
      $(em).closest('.form-group row').remove();
      update_sku();
    }

    function delete_variant(em) {
      $(em).closest('.variant').remove();
    }

    function update_sku() {
      $.ajax({
        type: "POST",
        url: '{{ route('products.sku_combination') }}',
        data: $('#choice_form').serialize(),
        success: function(data) {
          $('#sku_combination').html(data);
          AIZ.uploader.previewGenerate();
          AIZ.plugins.fooTable();
          if (data.trim().length > 1) {
            $('#show-hide-div').hide();
          } else {
            $('#show-hide-div').show();
          }
        }
      });
    }

    $('#choice_attributes').on('change', function() {
      $('#customer_choice_options').html(null);
      $.each($("#choice_attributes option:selected"), function() {
        add_more_customer_choice_option($(this).val(), $(this).text());
      });

      update_sku();
    });
  </script>
  <script>
    $(document).ready(function() {
      var hash = document.location.hash;
      if (hash) {
        $('.nav-tabs a[href="' + hash + '"]').tab('show');
      } else {
        $('.nav-tabs a[href="#general"]').tab('show');
      }

      // Change hash for page-reload
      $('.nav-tabs a').on('shown.bs.tab', function(e) {
        window.location.hash = e.target.hash;
      });
    });
  </script>
@endsection
