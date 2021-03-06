@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Cart Page</h2>
            @if(count($cartProducts) <= 0)
                <p>Sorry No Product Found</p>
            @else
                <table class="table table-responsive">
                    <tr>
                        <th class="col-md-8">Product</th>
                        <th class="col-md-1" style="text-align: center">Quantity</th>
                        <th class="col-md-1 text-center">Price</th>
                        <th class="col-md-1 text-center">Total</th>
                        <th class="col-md-1"> </th>
                    </tr>
                    <?php $total = 0; $taxTotal = 0;$giftCouponAmount = 0; ?>
                    @foreach($cartProducts as $product)
                        {!! Form::open(['method' => 'put', 'action' => route('cart.update') , 'id' => 'cart-form-update']) !!}
                        <tr>

                            <td class="col-md-8">
                                <div class="media">


                                    <img alt="{{ $product['title'] }}"
                                         class="img-responsive"
                                         style="height: 72px;"
                                         src="{{ asset( $product['image']) }}"/>


                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <a href="{{ route('product.view', $product['slug'])}}">
                                                {{ $product['title'] }}

                                            </a>
                                        </h4>

                                        <p>Status: <span class="text-success"><strong>In Stock</strong></span></p>

                                        <?php $attributeText = ""; ?>
                                        @if(isset($product['attributes']) && count($product['attributes']) > 0)
                                            @foreach($product['attributes'] as $attribute)
                                                @if($loop->last)
                                                    <?php $attributeText .= $attribute['variation_display_text']; ?>
                                                @else
                                                    <?php $attributeText .= $attribute['variation_display_text'] . ": "; ?>
                                                @endif
                                            @endforeach
                                        @endif
                                        <p>Attributes: <span
                                                    class="text-success"><strong>{{ $attributeText}}</strong></span></p>

                                    </div>
                                </div>
                            </td>
                            <td class="col-md-1">
                                <input type="text" class="form-control" name="qty"
                                       value="{{ $product['qty']}}">

                                <input type="hidden" name="id" value="{{$product['id']}}"/>
                            </td>
                            <?php $total += ($product['price'] * $product['qty']) ?>
                            <?php $giftCouponAmount += (isset($product['gift_coupon_amount'])) ? $product['gift_coupon_amount'] : 0.00 ?>
                            <?php $taxTotal += ($product['tax_amount'] * $product['qty']) ?>
                            <td class="col-sm-1 col-md-1 text-center">
                                <strong>${{ number_format($product['price'],2) }}</strong></td>
                            <td class="col-sm-1 col-md-1 text-center">
                                <strong>${{ ($product['price'] * $product['qty'] )}}</strong></td>
                            <td class="col-sm-1 col-md-1">
                                <div class="btn-group">
                                    <a class="btn btn-warning" href="#"

                                       onclick="jQuery('#cart-form-update').submit()">
                                        Update
                                    </a>
                                    <button type="button"
                                            class="btn dropdown-toggle"
                                            data-toggle="dropdown">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="btn" href="{{ route('cart.destroy', $product['id'])}}">
                                                Remove
                                            </a></li>
                                    </ul>
                                </div>

                            </td>

                        </tr>
                        {!! Form::close() !!}
                    @endforeach

                    <tr>
                        <td class="col-md-6">

                            <div class="input-group">
                                <input type="text" name="code" class="form-control gift-coupon-code-textbox"
                                       placeholder="Code"/>
                                <a href="#" class="input-group-addon code-apply-button">Apply</a>
                            </div>
                             
                        </td>
                        <td class="col-md-1">&nbsp;  </td>
                        <td class="col-md-1"> &nbsp;  </td>
                        <td class="col-md-1"><h6>Coupon Discount</h6></td>
                        <td class="col-md-3 text-right">
                            <strong>${{$giftCouponAmount}}</strong>
                        </td>
                    </tr>


                    <tr>
                        <td class="col-md-8"> &nbsp; </td>
                        <td class="col-md-1">&nbsp;  </td>
                        <td class="col-md-1"> &nbsp;  </td>
                        <td class="col-md-1"><h6>Subtotal</h6></td>
                        <td class="col-md-1 text-right"><h6><strong>${{ $total }}</strong></h6></td>
                    </tr>
                    <tr>
                        <td class="col-md-8">  </td>
                        <td class="col-md-1">  </td>
                        <td class="col-md-1">  </td>
                        <td class="col-md-1"><h6>Tax</h6></td>
                        <td class="col-md-1 text-right"><h6><strong>${{ number_format($taxTotal,2) }}</strong></h6></td>
                    </tr>
                    <tr>
                        <td class="col-md-8"> &nbsp; </td>
                        <td class="col-md-1">&nbsp;  </td>
                        <td class="col-md-1"> &nbsp;  </td>
                        <td class="col-md-1"><h6>Total</h6></td>
                        <td class="col-md-1 text-right"><h6>
                                <strong>${{ number_format(($total + $taxTotal),2) }}</strong></h6></td>
                    </tr>
                    <tr>
                        <td class="col-md-8">  </td>
                        <td class="col-md-1">  </td>
                        <td class="col-md-1">  </td>
                        <td class="col-md-1">
                            <a href="{{ route('home') }}" class="btn btn-default">
                                <span class="glyphicon glyphicon-shopping-cart"></span> Continue Shopping
                            </a>
                        </td>
                        <td class="col-md-1 text-right">
                            <a href="{{ route('checkout.index') }}" class="btn btn-success">
                                Checkout <span class="glyphicon glyphicon-play"></span>
                            </a>
                        </td>
                    </tr>
                </table>
            @endif

        </div>
    </div>
    </div>
@endsection

@push('scripts')
<script>
    jQuery(document).ready(function () {
        jQuery(document).on('click', '.code-apply-button', function (e) {
            e.preventDefault();

            if (jQuery('.gift-coupon-code-textbox').val() == "") {
                alert('please enter valid coupon code');
                return;
            }
            var data = {
                _token: '{{ csrf_token() }}',
                code: jQuery('.gift-coupon-code-textbox').val()
            }

            jQuery.ajax({
                url: '{{ route('get.code-discount') }}',
                dataType: 'json',
                type: 'post',
                data: data,
                success: function (res) {
                    x = res;
                    if (true == res.success) {
                        location.reload();
                    } else {
                        alert(res.message);
                    }
                }
            });
        });
    });
</script>
@endpush