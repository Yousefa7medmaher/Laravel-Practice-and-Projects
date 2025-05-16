@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="row g-4">
                <!-- Left: Checkout Form -->
                <div class="col-md-7">
                    <form method="POST" action="{{ route('checkout.process') }}">
                        @csrf
                        <div class="mb-4">
                            <h5 class="fw-bold">Contact</h5>
                            <input type="email" class="form-control mb-2" name="email" placeholder="Email or mobile phone number" value="{{ old('email', $user->email ?? '') }}" required>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="newsletter" id="newsletter" checked>
                                <label class="form-check-label" for="newsletter">Email me with news and offers</label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-bold">Delivery</h5>
                            <div class="row g-2 mb-2">
                                <div class="col-12">
                                    <select class="form-select" name="country" required>
                                        <option value="Egypt" selected>Egypt</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control" name="first_name" placeholder="First name" required>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control" name="last_name" placeholder="Last name" required>
                                </div>
                                <div class="col-12">
                                    <input type="text" class="form-control" name="address" placeholder="Address" required>
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control" name="city" placeholder="City" required>
                                </div>
                                <div class="col-4">
                                    <select class="form-select" name="governorate" required>
                                        <option value="Cairo" selected>Cairo</option>
                                        <option value="Giza">Giza</option>
                                        <option value="Alexandria">Alexandria</option>
                                        <option value="Dakahlia">Dakahlia</option>
                                        <!-- Add more as needed -->
                                    </select>
                                </div>
                                <div class="col-3">
                                    <input type="text" class="form-control" name="postal_code" placeholder="Postal code (optional)">
                                </div>
                                <div class="col-12">
                                    <input type="text" class="form-control" name="phone" placeholder="Phone" required>
                                </div>
                            </div>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="checkbox" name="save_info" id="save_info">
                                <label class="form-check-label" for="save_info">Save this information for next time</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="sms_news" id="sms_news">
                                <label class="form-check-label" for="sms_news">Text me with news and offers</label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-bold">Shipping method</h5>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="shipping_method" id="home_delivery" value="home" checked>
                                <label class="form-check-label d-flex justify-content-between w-100" for="home_delivery">
                                    <span>توصيل للمنزل</span>
                                    <span class="fw-bold">E£65.00</span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h5 class="fw-bold">Payment</h5>
                            <p class="text-muted small mb-2">Your payment method's billing address must match the shipping address above. All transactions are secure and encrypted.</p>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                <label class="form-check-label" for="cod">Cash on Delivery (COD)</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="card" value="card">
                                <label class="form-check-label" for="card">
                                    Credit card
                                    <img src="https://img.icons8.com/color/24/000000/visa.png" alt="Visa" class="ms-1">
                                    <img src="https://img.icons8.com/color/24/000000/mastercard-logo.png" alt="Mastercard">
                                    <img src="https://img.icons8.com/color/24/000000/amex.png" alt="Amex">
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 py-2 fw-bold">Complete order</button>
                    </form>
                </div>
                <!-- Right: Order Summary -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            @forelse($cart as $item)
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://via.placeholder.com/60x60?text=Product' }}" class="rounded me-2" style="width:60px;height:60px;object-fit:cover;">
                                <div>
                                    <div class="fw-semibold">{{ $item['name'] }}</div>
                                    <div class="text-muted small">x{{ $item['quantity'] }}</div>
                                </div>
                                <span class="badge bg-dark ms-auto">{{ $item['quantity'] }}</span>
                            </div>
                            @empty
                            <div class="text-muted">No products in cart.</div>
                            @endforelse
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Discount code">
                                <button class="btn btn-outline-secondary w-100 mt-2">Apply</button>
                            </div>
                            @php
                                $subtotal = collect($cart)->sum(function($item) {
                                    return $item['price'] * $item['quantity'];
                                });
                                $shipping = 65;
                                $total = $subtotal + $shipping;
                            @endphp
                            <ul class="list-unstyled mb-2">
                                <li class="d-flex justify-content-between mb-1">
                                    <span>Subtotal</span>
                                    <span>E£{{ number_format($subtotal, 2) }}</span>
                                </li>
                                <li class="d-flex justify-content-between mb-1">
                                    <span>Shipping</span>
                                    <span>E£{{ number_format($shipping, 2) }}</span>
                                </li>
                            </ul>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Total</span>
                                <span class="fs-5 fw-bold">EGP <span style="letter-spacing:1px;">£{{ number_format($total, 2) }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 