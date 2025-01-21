@extends('frontend.layouts.app')

@section('content')


<!-- Shipping Info -->
<section class="mb-4 mt-4 gry-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12">
                <form action="{{ route('payment.checkout') }}" class="form-default" role="form" method="POST">
                    @csrf
                    
                    @php
                    $admin_products = array();
                    $seller_products = array();
                    $admin_product_variation = array();
                    $seller_product_variation = array();
                    foreach ($carts as $key => $cartItem){
                        $product = get_single_product($cartItem['product_id']);

                        if($product->added_by == 'admin'){
                            array_push($admin_products, $cartItem['product_id']);
                            $admin_product_variation[] = $cartItem['variation'];
                        }
                        else{
                            $product_ids = array();
                            if(isset($seller_products[$product->user_id])){
                                $product_ids = $seller_products[$product->user_id];
                            }
                            array_push($product_ids, $cartItem['product_id']);
                            $seller_products[$product->user_id] = $product_ids;
                            $seller_product_variation[] = $cartItem['variation'];
                        }
                    }
                    
                    $pickup_point_list = array();
                    if (get_setting('pickup_point') == 1) {
                        $pickup_point_list = get_all_pickup_points();
                    }
                @endphp
                
                <input required type="hidden" name="checkout_type" value="logged">
                
                
                     <div class="card card-body c-scrollbar-light">
                        <div class="p-3">
                            <h4 class="text-primary">SHIPPING/PICKUP</h4>
                            <div class="row">
                                
                                @if (!empty($admin_products))
                                 <!-- Choose Delivery Type -->
                                <ul class="d-none">
                                        @php
                                            $physical = false;
                                        @endphp
                                        @foreach ($admin_products as $key => $cartItem)
                                            @php
                                                $product = get_single_product($cartItem);
                                                if ($product->digital == 0) {
                                                    $physical = true;
                                                }
                                            @endphp
                                            {{-- <li class="list-group-item">
                                                <div class="d-flex align-items-center">
                                                    <span class="mr-2 mr-md-3">
                                                        <img src="{{ get_image($product->thumbnail) }}"
                                                            class="img-fit size-60px"
                                                            alt="{{  $product->getTranslation('name')  }}"
                                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    </span>
                                                    <span class="fs-14 fw-400 text-dark">
                                                        {{ $product->getTranslation('name') }}
                                                        <br>
                                                        @if ($admin_product_variation[$key] != '')
                                                            <span class="fs-12 text-secondary">{{ translate('Variation') }}: {{ $admin_product_variation[$key] }}</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </li> --}}
                                        @endforeach
                                </ul>
                                @if ($physical)
                                
                                
                                {{--<div class="col-md-12">
                                    <div class="row">
                                       
                                        <div class="col-md-12 mb-3">
                                            <div class="row gutters-5">
                                                <!-- Home Delivery -->
                                                @if (get_setting('shipping_type') != 'carrier_wise_shipping')
                                                <div class="col-6">
                                                    <label class="aiz-megabox d-block bg-white mb-0">
                                                        <input required
                                                            type="radio"
                                                            name="shipping_type_{{ get_admin()->id }}"
                                                            value="home_delivery"
                                                            onchange="show_pickup_point(this, 'admin')"
                                                            data-target=".pickup_point_id_admin"
                                                            checked
                                                            onclick="$('.shipping').addClass('ship_show').removeClass('ship_hide');$('#country_id').attr('required',true);$('#state_id').attr('required',true);$('#city_id').attr('required',true);$('#postal_code').attr('required',true);$('#address').attr('required',true);"
                                                        >
                                                        <span class="d-flex aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                                                            <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                            <span class="flex-grow-1 pl-3 fw-600">{{  translate('Address') }}</span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <!-- Carrier -->
                                                @else
                                                <div class="col-6">
                                                    <label class="aiz-megabox d-block bg-white mb-0">
                                                        <input required
                                                            type="radio"
                                                            name="shipping_type_{{ get_admin()->id }}"
                                                            value="carrier"
                                                            onchange="show_pickup_point(this, 'admin')"
                                                            data-target=".pickup_point_id_admin"
                                                            checked
                                                        >
                                                        <span class="d-flex aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                                                            <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                            <span class="flex-grow-1 pl-3 fw-600">{{  translate('Carrier') }}</span>
                                                        </span>
                                                    </label>
                                                </div>
                                                @endif
                                                <!-- Local Pickup -->
                                                @if ($pickup_point_list)
                                                <div class="col-6">
                                                    <label class="aiz-megabox d-block bg-white mb-0">
                                                        <input required
                                                            type="radio"
                                                            name="shipping_type_{{ get_admin()->id }}"
                                                            value="pickup_point"
                                                            onchange="show_pickup_point(this, 'admin')"
                                                            data-target=".pickup_point_id_admin"
                                                            
                                                            onclick="$('.shipping').addClass('ship_hide').removeClass('ship_show');$('#country_id').attr('required',false);$('#state_id').attr('required',false);$('#city_id').attr('required',false);$('#postal_code').attr('required',false);$('#address').attr('required',false);"
                                                        >
                                                        <span class="d-flex aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                                                            <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                            <span class="flex-grow-1 pl-3 fw-600">{{  translate('Local Pickup') }}</span>
                                                        </span>
                                                    </label>
                                                </div>
                                                @endif
                                            </div>
                                            
                                        
                                            <!-- Pickup Point List -->
                                            @if ($pickup_point_list)
                                                <div class="mt-3 pickup_point_id_admin d-none">
                                                    <select required
                                                        class="form-control aiz-selectpicker rounded-0"
                                                        name="pickup_point_id_{{ get_admin()->id }}"
                                                        data-live-search="true"
                                                    >
                                                            <option>{{ translate('Select your nearest pickup point')}}</option>
                                                        @foreach ($pickup_point_list as $pick_up_point)
                                                            <option
                                                                value="{{ $pick_up_point->id }}"
                                                                data-content="<span class='d-block'>
                                                                                <span class='d-block fs-16 fw-600 mb-2'>{{ $pick_up_point->getTranslation('name') }}</span>
                                                                                <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point->getTranslation('address') }}</span>
                                                                                <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i>{{ $pick_up_point->phone }}</span>
                                                                            </span>"
                                                            >
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Carrier Wise Shipping -->
                                        @if (get_setting('shipping_type') == 'carrier_wise_shipping')
                                            <div class="row pt-3 carrier_id_admin">
                                                @foreach($carrier_list as $carrier_key => $carrier)
                                                    <div class="col-md-12 mb-2">
                                                        <label class="aiz-megabox d-block bg-white mb-0">
                                                            <input required
                                                                type="radio"
                                                                name="carrier_id_{{ get_admin()->id }}"
                                                                value="{{ $carrier->id }}"
                                                                @if($carrier_key == 0) checked @endif
                                                            >
                                                            <span class="d-flex p-3 aiz-megabox-elem rounded-0">
                                                                <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                                                <span class="flex-grow-1 pl-3 fw-600">
                                                                    <img src="{{ uploaded_asset($carrier->logo)}}" alt="Image" class="w-50px img-fit">
                                                                </span>
                                                                <span class="flex-grow-1 pl-3 fw-700">{{ $carrier->name }}</span>
                                                                <span class="flex-grow-1 pl-3 fw-600">{{ translate('Transit in').' '.$carrier->transit_time }}</span>
                                                                <span class="flex-grow-1 pl-3 fw-600">{{ single_price(carrier_base_price($carts, $carrier->id, get_admin()->id)) }}</span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>--}}
                                    
                                @endif
                            @endif
                            
                                
                                
                                 <div class="col-md-12 mb-3">
                                    <label>{{ translate('Full Name')}}</label>
                                    <input required type="text" class="form-control mb-1 rounded-0" placeholder="{{ translate('Full Name')}}" name="name" value="" >
                                </div>
                                
                                
                                <div class="col-md-6 mb-3">
                                    <label>{{ translate('Email')}}</label>
                                    <input required type="email" class="form-control mb-3 rounded-0" placeholder="{{ translate('Email')}}" name="email" value="" >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>{{ translate('Phone')}}</label>
                                    <input required type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('phone')}}" name="phone" value="" >
                                </div>
                              
                            
                               
                                
                                <div class="col-6  mb-3">
                                    <label class="aiz-megabox d-block bg-white mb-0">
                                        <input required
                                            type="radio"
                                            name="city_id"
                                            value="7291"
                                            checked required>
                                        <span class="d-flex aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                                            <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                            <span class="flex-grow-1 pl-3 fw-600">{{  translate('Inside Dhaka') }}</span>
                                        </span>
                                    </label>
                                </div>
                                
                                <div class="col-6  mb-3">
                                    <label class="aiz-megabox d-block bg-white mb-0">
                                        <input required
                                            type="radio"
                                            name="city_id"
                                            value="7292" required>
                                        <span class="d-flex aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                                            <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                            <span class="flex-grow-1 pl-3 fw-600">{{  translate('Outside Dhaka') }}</span>
                                        </span>
                                    </label>
                                </div>
                                
                                <div class="col-md-12">
                                    <label>{{ translate('Address')}}</label>
                                    <textarea id="address" required class="form-control rounded-0" placeholder="{{ translate('Your Address')}}" rows="2" name="address"></textarea>
                                </div>
                                
                                <input type="hidden" name="country_id" value="18">
                                <input type="hidden" name="state_id" value="348">
                                
                                
                                <style>
                                    .ship_show{
                                        display:block;   
                                    }
                                    .ship_hide{
                                        display:none;
                                    }
                                   
                                </style>
            
            
                                <div class="shipping">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <!--<div class="col-md-12 mt-3">-->
                                            <!--    <label>{{ translate('Country')}}</label>-->
                                            <!--    <select id="country_id" required class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" >-->
                                            <!--        <option value="">{{ translate('Select your country') }}</option>-->
                                            <!--        @foreach (get_active_countries() as $key => $country)-->
                                            <!--            <option value="{{ $country->id }}">{{ $country->name }}</option>-->
                                            <!--        @endforeach-->
                                            <!--    </select>-->
                                            <!--</div>-->
                                            <!--<div class="col-md-4">-->
                                            <!--    <label>{{ translate('State')}}</label>-->
                                            <!--     <select id="state_id" required  class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="state_id" >-->
                
                                            <!--    </select>-->
                                            <!--</div>-->
                                            <!--<div class="col-md-4">-->
                                            <!--    <label>{{ translate('City')}}</label>-->
                                            <!--    <select id="city_id" required  class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="city_id" >-->
                
                                            <!--    </select>-->
                                            <!--</div>-->
                                            
                                            
                                            <!--<div class="col-md-4">-->
                                            <!--    <label>{{ translate('Zip code')}}</label>-->
                                            <!--    <input id="postal_code" required type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Zip Code')}}" name="postal_code" value="" >-->
                                            <!--</div>-->
                                            
                                            
                                            <!-- Address -->
                                            
                                        </div>
                                    </div>
                                    
                                </div>
                                
                            </div>

                        </div>
                        
                      
                        <!--Payment option-->
                        <input required type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">

                        <div class="card rounded-0 border-0 shadow-none">

                            <div class="card-header p-4 border-bottom-0">
                                <h3 class="fs-16 fw-700 text-dark mb-0">
                                    {{ translate('Select a payment option') }}
                                </h3>
                            </div>
                            <!-- Payment Options -->
                            <div class="card-body text-center px-4 pt-0">
                                <div class="row gutters-10">
                                    <!-- Paypal -->
                                    @if (get_setting('paypal_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="paypal" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/paypal.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span class="d-block fw-600 fs-15">{{ translate('Paypal') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!--Stripe -->
                                    @if (get_setting('stripe_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="stripe" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/stripe.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span class="d-block fw-600 fs-15">{{ translate('Stripe') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- Mercadopago -->
                                    @if (get_setting('mercadopago_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="mercadopago" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/mercadopago.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Mercadopago') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- sslcommerz -->
                                    @if (get_setting('sslcommerz_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="sslcommerz" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/sslcommerz.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('sslcommerz') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- instamojo -->
                                    @if (get_setting('instamojo_payment') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="instamojo" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/instamojo.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Instamojo') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- razorpay -->
                                    @if (get_setting('razorpay') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="razorpay" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/rozarpay.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Razorpay') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- paystack -->
                                    @if (get_setting('paystack') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="paystack" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/paystack.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Paystack') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- voguepay -->
                                    @if (get_setting('voguepay') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="voguepay" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/vogue.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('VoguePay') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- payhere -->
                                    @if (get_setting('payhere') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="payhere" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/payhere.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('payhere') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- ngenius -->
                                    @if (get_setting('ngenius') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="ngenius" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/ngenius.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('ngenius') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- iyzico -->
                                    @if (get_setting('iyzico') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="iyzico" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/iyzico.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Iyzico') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- nagad -->
                                    @if (get_setting('nagad') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="nagad" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/nagad.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span class="d-block fw-600 fs-15">{{ translate('Nagad') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- bkash -->
                                    @if (get_setting('bkash') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="bkash" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/bkash.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span class="d-block fw-600 fs-15">{{ translate('Bkash') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- aamarpay -->
                                    @if (get_setting('aamarpay') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="aamarpay" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/aamarpay.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Aamarpay') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- authorizenet -->
                                    @if (get_setting('authorizenet') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="authorizenet" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/authorizenet.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-600 fs-15">{{ translate('Authorize Net') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- payku -->
                                    @if (get_setting('payku') == 1)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input required value="payku" class="online_payment" type="radio"
                                                    name="payment_option" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('assets/img/cards/payku.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span class="d-block fw-600 fs-15">{{ translate('Payku') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                    <!-- African Payment Getaway -->
                                    @if (addon_is_activated('african_pg'))
                                        <!-- flutterwave -->
                                        @if (get_setting('flutterwave') == 1)
                                            <div class="col-6 col-xl-3 col-md-4">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input required value="flutterwave" class="online_payment" type="radio"
                                                        name="payment_option" checked>
                                                    <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                        <img src="{{ static_asset('assets/img/cards/flutterwave.png') }}"
                                                            class="img-fit mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('flutterwave') }}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                        <!-- payfast -->
                                        @if (get_setting('payfast') == 1)
                                            <div class="col-6 col-xl-3 col-md-4">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input required value="payfast" class="online_payment" type="radio"
                                                        name="payment_option" checked>
                                                    <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                        <img src="{{ static_asset('assets/img/cards/payfast.png') }}"
                                                            class="img-fit mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('payfast') }}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    {{-- Asian Payment gateways --}}
                                    <!--paytm -->
                                    @if (addon_is_activated('paytm'))
                                        @if (get_setting('paytm_payment') == 1)
                                            <div class="col-6 col-xl-3 col-md-4">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input required value="paytm" class="online_payment" type="radio"
                                                        name="payment_option" checked>
                                                    <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                        <img src="{{ static_asset('assets/img/cards/paytm.jpg') }}"
                                                            class="img-fit mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Paytm') }}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                        <!-- toyyibpay -->
                                        @if (get_setting('toyyibpay_payment') == 1)
                                            <div class="col-6 col-xl-3 col-md-4">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input required value="toyyibpay" class="online_payment" type="radio"
                                                        name="payment_option" checked>
                                                    <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                        <img src="{{ static_asset('assets/img/cards/toyyibpay.png') }}"
                                                            class="img-fit mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('ToyyibPay') }}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                        <!-- myfatoorah -->
                                        @if (get_setting('myfatoorah') == 1)
                                            <div class="col-6 col-xl-3 col-md-4">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input required value="myfatoorah" class="online_payment" type="radio"
                                                        name="payment_option" checked>
                                                    <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                        <img src="{{ static_asset('assets/img/cards/myfatoorah.png') }}"
                                                            class="img-fit mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('MyFatoorah') }}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                        <!-- khalti -->
                                        @if (get_setting('khalti_payment') == 1)
                                            <div class="col-6 col-xl-3 col-md-4">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input required value="Khalti" class="online_payment" type="radio"
                                                        name="payment_option" checked>
                                                    <span class="d-block aiz-megabox-elem p-3">
                                                        <img src="{{ static_asset('assets/img/cards/khalti.png') }}"
                                                            class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Khalti') }}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                        <!-- phonepe -->
                                        @if (get_setting('phonepe_payment') == 1)
                                            <div class="col-6 col-xl-3 col-md-4">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input required value="phonepe" class="online_payment" type="radio"
                                                        name="payment_option" checked>
                                                    <span class="d-block aiz-megabox-elem p-3">
                                                        <img src="{{ static_asset('assets/img/cards/phonepe.png') }}"
                                                            class="img-fluid mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Phonepe') }}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    <!-- Cash Payment -->
                                    @if (get_setting('cash_payment') == 1)
                                        @php
                                            $digital = 0;
                                            $cod_on = 1;
                                            foreach ($carts as $cartItem) {
                                                $product = get_single_product($cartItem['product_id']);
                                                if ($product['digital'] == 1) {
                                                    $digital = 1;
                                                }
                                                if ($product['cash_on_delivery'] == 0) {
                                                    $cod_on = 0;
                                                }
                                            }
                                        @endphp
                                        @if ($digital != 1 && $cod_on == 1)
                                            <div class="col-6 col-xl-3 col-md-4">
                                                <label class="aiz-megabox d-block mb-3">
                                                    <input required value="cash_on_delivery" class="online_payment" type="radio"
                                                        name="payment_option" checked>
                                                    <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                        <img src="{{ static_asset('assets/img/cards/cod.png') }}"
                                                            class="img-fit mb-2">
                                                        <span class="d-block text-center">
                                                            <span
                                                                class="d-block fw-600 fs-15">{{ translate('Cash on Delivery') }}</span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    @if (Auth::check())
                                        <!-- Offline Payment -->
                                        @if (addon_is_activated('offline_payment'))
                                            @foreach (get_all_manual_payment_methods() as $method)
                                                <div class="col-6 col-xl-3 col-md-4">
                                                    <label class="aiz-megabox d-block mb-3">
                                                        <input required value="{{ $method->heading }}" type="radio"
                                                            name="payment_option" class="offline_payment_option"
                                                            onchange="toggleManualPaymentData({{ $method->id }})"
                                                            data-id="{{ $method->id }}" checked>
                                                        <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                            <img src="{{ uploaded_asset($method->photo) }}"
                                                                class="img-fit mb-2">
                                                            <span class="d-block text-center">
                                                                <span
                                                                    class="d-block fw-600 fs-15">{{ $method->heading }}</span>
                                                            </span>
                                                        </span>
                                                    </label>
                                                </div>
                                            @endforeach

                                            @foreach (get_all_manual_payment_methods() as $method)
                                                <div id="manual_payment_info_{{ $method->id }}" class="d-none">
                                                    @php echo $method->description @endphp
                                                    @if ($method->bank_info != null)
                                                        <ul>
                                                            @foreach (json_decode($method->bank_info) as $key => $info)
                                                                <li>{{ translate('Bank Name') }} -
                                                                    {{ $info->bank_name }},
                                                                    {{ translate('Account Name') }} -
                                                                    {{ $info->account_name }},
                                                                    {{ translate('Account Number') }} -
                                                                    {{ $info->account_number }},
                                                                    {{ translate('Routing Number') }} -
                                                                    {{ $info->routing_number }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    @endif
                                </div>

                                <!-- Offline Payment Fields -->
                                {{--@if (addon_is_activated('offline_payment'))
                                    <div class="d-none mb-3 rounded border bg-white p-3 text-left">
                                        <div id="manual_payment_description">

                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>{{ translate('Transaction ID') }} <span
                                                        class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-9">
                                                <input required type="text" class="form-control mb-3" name="trx_id"
                                                    id="trx_id" placeholder="{{ translate('Transaction ID') }}"
                                                    >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ translate('Photo') }}</label>
                                            <div class="col-md-9">
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                            {{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose image') }}
                                                    </div>
                                                    <input required type="hidden" name="photo" class="selected-files">
                                                </div>
                                                <div class="file-preview box sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif--}}

                                <!-- Wallet Payment -->
                                {{--@if (Auth::check() && get_setting('wallet_system') == 1)
                                    <div class="py-4 px-4 text-center bg-soft-secondary-base mt-4">
                                        <div class="fs-14 mb-3">
                                            <span class="opacity-80">{{ translate('Or, Your wallet balance :') }}</span>
                                            <span class="fw-700">{{ single_price(Auth::user()->balance) }}</span>
                                        </div>
                                        @if (Auth::user()->balance < $total)
                                            <button type="button" class="btn btn-secondary" disabled>
                                                {{ translate('Insufficient balance') }}
                                            </button>
                                        @else
                                            <button type="button" onclick="use_wallet()"
                                                class="btn btn-primary fs-14 fw-700 px-5 rounded-0">
                                                {{ translate('Pay with wallet') }}
                                            </button>
                                        @endif
                                    </div>
                                @endif--}}
                            </div>
                             <!-- Additional Info -->
                            <!--<div class="card-header p-4 border-bottom-0">-->
                            <!--    <h3 class="fs-16 fw-700 text-dark mb-0">-->
                            <!--        {{ translate('Any additional info?') }}-->
                            <!--    </h3>-->
                            <!--</div>-->
                            <!--<div class="form-group px-4">-->
                            <!--    <textarea name="additional_info" rows="5" class="form-control rounded-0"-->
                            <!--        placeholder="{{ translate('Type your text...') }}"></textarea>-->
                            <!--</div>-->
                            <!-- Agree Box -->
                            <div class="pt-3 px-4 fs-14">
                                <label class="aiz-checkbox">
                                    <input required checked type="checkbox"  id="agree_checkbox">
                                    <span class="aiz-square-check"></span>
                                    <span>{{ translate('I agree to the') }}</span>
                                </label>
                                <a href="{{ route('terms') }}"
                                    class="fw-700">{{ translate('terms and conditions') }}</a>,
                                <a href="{{ route('returnpolicy') }}"
                                    class="fw-700">{{ translate('return policy') }}</a> &
                                <a href="{{ route('privacypolicy') }}"
                                    class="fw-700">{{ translate('privacy policy') }}</a>
                            </div>

                            <div class="row align-items-center pt-3 px-4 mb-4">
                                <!-- Return to shop -->
                                <div class="col-6">
                                    <a href="{{ route('home') }}" class="btn btn-link fs-14 fw-700 px-0">
                                        <i class="las la-arrow-left fs-16"></i>
                                        {{ translate('Return to shop') }}
                                    </a>
                                </div>
                                <!-- Complete Ordert -->
                                <div class="col-6 text-right">
                                    <button type="submit"
                                        class="btn btn-primary fs-14 fw-700 rounded-0 px-4">{{ translate('Complete Order') }}</button>
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="col-lg-4 col-12">
                <div class="card card-body">

                    <div class="card-header pt-4 pb-1 border-0">
                            <h3 class="fs-16 fw-700 mb-0">{{ translate('Summary') }}</h3>
                            <div class="text-right">
                                <!-- Items Count -->
                                <span class="badge badge-inline badge-primary fs-12 rounded-0 px-2">
                                    {{ count($carts) }}
                                    {{ translate('Items') }}
                                </span>
                                
                                <!-- Minimum Order Amount -->
                                @php
                                    $coupon_discount = 0;
                                @endphp
                                @if (Auth::check() && get_setting('coupon_system') == 1)
                                    @php
                                        $coupon_code = null;
                                    @endphp
                    
                                    @foreach ($carts as $key => $cartItem)
                                        @php
                                            $product = get_single_product($cartItem['product_id']);
                                        @endphp
                                        @if ($cartItem->coupon_applied == 1)
                                            @php
                                                $coupon_code = $cartItem->coupon_code;
                                                break;
                                            @endphp
                                        @endif
                                    @endforeach
                    
                                    @php
                                        $coupon_discount = carts_coupon_discount($coupon_code);
                                    @endphp
                                @endif
                    
                                @php $subtotal_for_min_order_amount = 0; @endphp
                                @foreach ($carts as $key => $cartItem)
                                    @php $subtotal_for_min_order_amount += cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity']; @endphp
                                @endforeach
                                @if (get_setting('minimum_order_amount_check') == 1 && $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
                                    <span class="badge badge-inline badge-primary fs-12 rounded-0 px-2">
                                        {{ translate('Minimum Order Amount') . ' ' . single_price(get_setting('minimum_order_amount')) }}
                                    </span>
                                @endif
                                
                            </div>
                        </div>
                        <!-- Club point -->
                        @if (addon_is_activated('club_point'))
                        <div class="px-4 pt-1 w-100 d-flex align-items-center justify-content-between">
                            <h3 class="fs-14 fw-700 mb-0">{{ translate('Total Clubpoint') }}</h3>
                            <div class="text-right">
                                <span class="badge badge-inline badge-warning fs-12 rounded-0 px-2 text-white">
                                    @php
                                        $total_point = 0;
                                    @endphp
                                    @foreach ($carts as $key => $cartItem)
                                        @php
                                            $product = get_single_product($cartItem['product_id']);
                                            $total_point += $product->earn_point * $cartItem['quantity'];
                                        @endphp
                                    @endforeach
                    
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" class="mr-2">
                                        <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                                          <circle id="Ellipse_39" data-name="Ellipse 39" cx="6" cy="6" r="6" transform="translate(973 633)" fill="#fff"/>
                                          <g id="Group_23920" data-name="Group 23920" transform="translate(973 633)">
                                            <path id="Path_28698" data-name="Path 28698" d="M7.667,3H4.333L3,5,6,9,9,5Z" transform="translate(0 0)" fill="#f3af3d"/>
                                            <path id="Path_28699" data-name="Path 28699" d="M5.33,3h-1L3,5,6,9,4.331,5Z" transform="translate(0 0)" fill="#f3af3d" opacity="0.5"/>
                                            <path id="Path_28700" data-name="Path 28700" d="M12.666,3h1L15,5,12,9l1.664-4Z" transform="translate(-5.995 0)" fill="#f3af3d"/>
                                          </g>
                                        </g>
                                    </svg>
                                    {{ $total_point }}
                                </span>
                            </div>
                        </div>
                        @endif
                    
                        <div class="card-body">
                            <!-- Products Info -->
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="product-name border-top-0 border-bottom-1 pl-0 fs-12 fw-400 opacity-60">{{ translate('Product') }}</th>
                                        <th class="product-total text-right border-top-0 border-bottom-1 pr-0 fs-12 fw-400 opacity-60">{{ translate('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $subtotal = 0;
                                        $tax = 0;
                                        $shipping = 0;
                                        $product_shipping_cost = 0;
                                    
                                    @endphp
                                    @foreach ($carts as $key => $cartItem)
                                        @php
                                            $product = get_single_product($cartItem['product_id']);
                                            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                                            $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                                            $product_shipping_cost = $cartItem['shipping_cost'];
                                            
                                            $shipping += $product_shipping_cost;
                                            
                                            $product_name_with_choice = $product->getTranslation('name');
                                            if ($cartItem['variant'] != null) {
                                                $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variant'];
                                            }
                                        @endphp
                                        <tr class="cart_item">
                                            <td class="product-name pl-0 fs-14 text-dark fw-400 border-top-0 border-bottom">
                                                {{ $product_name_with_choice }}
                                                <strong class="product-quantity">
                                                    × {{ $cartItem['quantity'] }}
                                                </strong>
                                            </td>
                                            <td class="product-total text-right pr-0 fs-14 text-primary fw-600 border-top-0 border-bottom">
                                                <span
                                                    class="pl-4 pr-0">{{ single_price(cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity']) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    
                            <input required type="hidden" id="sub_total" value="{{ $subtotal }}">
                    
                            <table class="table" style="margin-top: 2rem!important;">
                                <tfoot>
                                    <!-- Subtotal -->
                                    <tr class="cart-subtotal">
                                        <th class="pl-0 fs-14 pt-0 pb-2 text-dark fw-600 border-top-0">{{ translate('Subtotal') }}</th>
                                        <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                                            <span class="fw-600">{{ single_price($subtotal) }}</span>
                                        </td>
                                    </tr>
                                    <!-- Tax -->
                                    <tr class="cart-shipping">
                                        <th class="pl-0 fs-14 pt-0 pb-2 text-dark fw-600 border-top-0">{{ translate('Tax') }}</th>
                                        <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                                            <span class="fw-600">{{ single_price($tax) }}</span>
                                        </td>
                                    </tr>
                                    <!-- Total Shipping -->
                                    <tr class="cart-shipping">
                                        <th class="pl-0 fs-14 pt-0 pb-2 text-dark fw-600 border-top-0">{{ translate('Total Shipping') }}</th>
                                        <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                                            <span class="fw-600">{{ single_price($shipping) }}</span>
                                        </td>
                                    </tr>
                                    <!-- Redeem point -->
                                    @if (Session::has('club_point'))
                                        <tr class="cart-shipping">
                                            <th class="pl-0 fs-14 pt-0 pb-2 text-dark fw-600 border-top-0">{{ translate('Redeem point') }}</th>
                                            <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                                                <span class="fw-600">{{ single_price(Session::get('club_point')) }}</span>
                                            </td>
                                        </tr>
                                    @endif
                                    <!-- Coupon Discount -->
                                    @if ($coupon_discount > 0)
                                        <tr class="cart-shipping">
                                            <th class="pl-0 fs-14 pt-0 pb-2 text-dark fw-600 border-top-0">{{ translate('Coupon Discount') }}</th>
                                            <td class="text-right pr-0 fs-14 pt-0 pb-2 fw-600 text-primary border-top-0">
                                                <span class="fw-600">{{ single_price($coupon_discount) }}</span>
                                            </td>
                                        </tr>
                                    @endif
                    
                                    @php
                                        $total = $subtotal + $tax + $shipping;
                                        if (Session::has('club_point')) {
                                            $total -= Session::get('club_point');
                                        }
                                        if ($coupon_discount > 0) {
                                            $total -= $coupon_discount;
                                        }
                                    @endphp
                                    <!-- Total -->
                                    <tr class="cart-total">
                                        <th class="pl-0 fs-14 text-dark fw-600"><span class="strong-600">{{ translate('Total') }}</span></th>
                                        <td class="text-right pr-0 fs-14 fw-600 text-primary">
                                            <strong><span>{{ single_price($total) }}</span></strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                    
                            <!-- Remove Redeem Point -->
                            {{--@if (addon_is_activated('club_point'))
                                @if (Session::has('club_point'))
                                    <div class="mt-3">
                                        <form class="" action="{{ route('checkout.remove_club_point') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="input-group">
                                                <div class="form-control">{{ Session::get('club_point') }}</div>
                                                <div class="input-group-append">
                                                    <button type="submit"
                                                        class="btn btn-primary">{{ translate('Remove Redeem Point') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            @endif--}}
                    
                            <!-- Coupon System -->
                            @if (Auth::check() && get_setting('coupon_system') == 1)
                                @if ($coupon_discount > 0 && $coupon_code)
                                    <div class="mt-3">
                                        <form class="" id="remove-coupon-form" enctype="multipart/form-data">
                                            @csrf
                                            <div class="input-group">
                                                <div class="form-control">{{ $coupon_code }}</div>
                                                <div class="input-group-append">
                                                    <button type="button" id="coupon-remove"
                                                        class="btn btn-primary">{{ translate('Change Coupon') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <div class="mt-3">
                                        <form class="" id="apply-coupon-form" enctype="multipart/form-data">
                                            @csrf
                                            <input required type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
                                            <div class="input-group">
                                                <input required type="text" class="form-control rounded-0" name="code"
                                                    onkeydown="return event.key != 'Enter';"
                                                    placeholder="{{ translate('Have coupon code? Apply here') }}" >
                                                <div class="input-group-append">
                                                    <button type="button" id="coupon-apply"
                                                        class="btn btn-primary rounded-0">{{ translate('Apply') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            @endif
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <style>
        .checkout_left{
               box-shadow: 0 0px 27px rgb(40 42 53 / 20%);
                border: 2px solid #008570;
                border-radius: 8px;
        }
    </style>
@endsection

@section('modal')
    <!-- Address Modal -->
    @include('frontend.'.get_setting('homepage_select').'.partials.address_modal')
@endsection