@extends('layouts.customer.app')

@section('content')

<style>
    /* =========================================
       CUSTOM CAKE BOOKING - PREMIUM DESIGN
    ========================================= */

    .custom-booking {
        --cake-primary: #8b5945;
        --cake-dark: #493329;
        --cake-light: #fbf5ef;
        --cake-border: #eee3da;
        --cake-muted: #8c8179;

        background: #fbf8f5;
        padding: 55px 0 75px;
        min-height: 100vh;
        color: var(--cake-dark);
    }

    .custom-booking * {
        box-sizing: border-box;
    }

    .booking-container {
        max-width: 1050px;
        margin: auto;
        padding: 0 18px;
    }

    /* Hero */

    .booking-hero {
        text-align: center;
        margin-bottom: 38px;
    }

    .booking-hero .eyebrow {
        display: inline-block;
        background: #f1e4d9;
        color: var(--cake-primary);
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 17px;
    }

    .booking-hero h1 {
        font-family: Georgia, serif;
        font-size: clamp(32px, 5vw, 46px);
        font-weight: 700;
        color: #493329;
        margin-bottom: 13px;
    }

    .booking-hero p {
        max-width: 620px;
        margin: auto;
        color: #81756d;
        font-size: 15px;
        line-height: 1.8;
    }

    .booking-decoration {
        width: 65px;
        height: 3px;
        background: #bd8d70;
        margin: 22px auto 0;
        border-radius: 10px;
    }

    /* Information strip */

    .booking-info {
        background: #f3e8df;
        border: 1px solid #ead9cb;
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .booking-info-icon {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fffaf6;
        border-radius: 12px;
        font-size: 20px;
    }

    .booking-info strong {
        display: block;
        color: #654633;
        font-size: 14px;
        margin-bottom: 3px;
    }

    .booking-info span {
        color: #89766a;
        font-size: 13px;
    }

    /* Alerts */

    .booking-alert {
        border-radius: 12px;
        padding: 15px 18px;
        margin-bottom: 22px;
        font-size: 14px;
    }

    /* Form cards */

    .booking-card {
        background: #fff;
        border: 1px solid var(--cake-border);
        border-radius: 17px;
        margin-bottom: 22px;
        overflow: hidden;
        box-shadow: 0 5px 22px rgba(75, 48, 31, 0.045);
    }

    .booking-card-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 21px 26px;
        border-bottom: 1px solid #f1e9e3;
        background: #fffdfb;
    }

    .booking-number {
        width: 39px;
        height: 39px;
        border-radius: 12px;
        background: #f1e5da;
        color: var(--cake-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 15px;
        flex-shrink: 0;
    }

    .booking-card-header h3 {
        font-size: 17px;
        font-weight: 700;
        color: #493329;
        margin: 0 0 4px;
    }

    .booking-card-header p {
        margin: 0;
        color: #968981;
        font-size: 12px;
    }

    .booking-card-body {
        padding: 25px 26px 28px;
    }

    /* Fields */

    .custom-booking .form-label {
        font-size: 13px;
        font-weight: 700;
        color: #58463c;
        margin-bottom: 9px;
    }

    .required-star {
        color: #bd594d;
    }

    .custom-booking .form-control,
    .custom-booking .form-select {
        min-height: 47px;
        border: 1px solid #e8ded6;
        border-radius: 10px;
        padding: 11px 14px;
        color: #493c34;
        font-size: 14px;
        background-color: #fff;
        box-shadow: none;
        transition: all .2s ease;
    }

    .custom-booking textarea.form-control {
        min-height: auto;
        resize: vertical;
    }

    .custom-booking .form-control::placeholder {
        color: #b5aaa2;
        font-size: 13px;
    }

    .custom-booking .form-control:focus,
    .custom-booking .form-select:focus {
        border-color: #b8896c;
        box-shadow: 0 0 0 3px rgba(184, 137, 108, .12);
    }

    .field-hint {
        color: #9a8d84;
        font-size: 11px;
        margin-top: 7px;
        display: block;
    }

    /* Delivery */

    #delivery_section {
        display: none;
    }

    /* Upload */

    .upload-note {
        background: #faf5f0;
        padding: 10px 13px;
        border-radius: 9px;
        margin-top: 9px;
        color: #8d7b70;
        font-size: 11px;
    }

    /* Submit area */

    .booking-submit-area {
        background: #fff;
        border: 1px solid var(--cake-border);
        border-radius: 16px;
        padding: 23px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        box-shadow: 0 5px 22px rgba(75, 48, 31, 0.045);
    }

    .booking-submit-area strong {
        display: block;
        color: #493329;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .booking-submit-area small {
        color: #968981;
        font-size: 12px;
    }

    .btn-book-cake {
        background: #85543f;
        border: 1px solid #85543f;
        color: #fff;
        border-radius: 10px;
        padding: 14px 28px;
        font-size: 14px;
        font-weight: 700;
        transition: all .25s ease;
        white-space: nowrap;
    }

    .btn-book-cake:hover {
        background: #67402f;
        border-color: #67402f;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 7px 17px rgba(103, 64, 47, .2);
    }

    .btn-book-cake:disabled {
        opacity: .7;
        cursor: not-allowed;
        transform: none;
    }

    .booking-footer-note {
        text-align: center;
        color: #9a8d84;
        font-size: 12px;
        margin-top: 23px;
    }

    /* Responsive */

    @media(max-width: 767px) {
        .custom-booking {
            padding: 35px 0 50px;
        }

        .booking-card-header {
            padding: 17px;
        }

        .booking-card-body {
            padding: 20px 17px;
        }

        .booking-submit-area {
            flex-direction: column;
            align-items: stretch;
            padding: 20px;
        }

        .btn-book-cake {
            width: 100%;
        }

        .booking-info {
            align-items: flex-start;
        }
    }
</style>


<div class="custom-booking">

    <div class="booking-container">

        {{-- HERO --}}
        <div class="booking-hero">

            <div class="eyebrow">
                ✦ Made Specially For You ✦
            </div>

            <h1>Design Your Dream Cake</h1>

            <p>
                Every celebration deserves something special.
                Tell us about your dream cake, choose your favourite flavours
                and share your design ideas. We'll take care of the rest.
            </p>

            <div class="booking-decoration"></div>

        </div>


        {{-- INFORMATION --}}
        <div class="booking-info">

            <div class="booking-info-icon">🎂</div>

            <div>
                <strong>Your Cake, Your Design, Your Celebration</strong>
                <span>
                    Submit your cake design request. Our bakery team will review it and share a quotation with you.
                </span>
            </div>

        </div>


        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="alert alert-success booking-alert">
                <strong>Custom Cake Request Submitted!</strong>
                {{ session('success') }}
            </div>
        @endif


        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="alert alert-danger booking-alert">
                <strong>Please correct the following details:</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST"
              action="{{ route('customer.custom-cake-orders.store') }}"
              enctype="multipart/form-data"
              id="customCakeBookingForm">

            @csrf


            {{-- SECTION 1 --}}
            <div class="booking-card">

                <div class="booking-card-header">

                    <div class="booking-number">01</div>

                    <div>
                        <h3>Occasion & Schedule</h3>
                        <p>When and why are you celebrating?</p>
                    </div>

                </div>

                <div class="booking-card-body">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="form-label">
                                Occasion <span class="required-star">*</span>
                            </label>

                            <input type="text"
                                   name="occasion"
                                   class="form-control"
                                   value="{{ old('occasion') }}"
                                   placeholder="Birthday, wedding, anniversary..."
                                   maxlength="255"
                                   required>
                        </div>


                        <div class="col-md-3">
                            <label class="form-label">
                                Required Date <span class="required-star">*</span>
                            </label>

                            <input type="date"
                                   name="required_date"
                                   class="form-control"
                                   value="{{ old('required_date') }}"
                                   min="{{ now()->toDateString() }}"
                                   required>
                        </div>


                        <div class="col-md-3">
                            <label class="form-label">
                                Required Time <span class="required-star">*</span>
                            </label>

                            <input type="time"
                                   name="required_time"
                                   class="form-control"
                                   value="{{ old('required_time') }}"
                                   required>
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">
                                Order Fulfilment <span class="required-star">*</span>
                            </label>

                            <select name="fulfilment_type"
                                    id="fulfilment_type"
                                    class="form-select"
                                    required>

                                <option value="">Choose an option</option>

                                <option value="pickup"
                                    @selected(old('fulfilment_type') === 'pickup')>
                                    Store Pickup
                                </option>

                                <option value="delivery"
                                    @selected(old('fulfilment_type') === 'delivery')>
                                    Home Delivery
                                </option>

                            </select>
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">
                                Number of Servings <span class="required-star">*</span>
                            </label>

                            <input type="number"
                                   name="servings"
                                   class="form-control"
                                   min="1"
                                   value="{{ old('servings') }}"
                                   placeholder="Example: 10"
                                   required>
                        </div>

                    </div>

                </div>

            </div>


            {{-- SECTION 2 --}}
            <div class="booking-card">

                <div class="booking-card-header">

                    <div class="booking-number">02</div>

                    <div>
                        <h3>Cake Specifications</h3>
                        <p>Choose the flavour, weight and appearance of your cake.</p>
                    </div>

                </div>

                <div class="booking-card-body">

                    <div class="row g-4">

                        <div class="col-md-4">
                            <label class="form-label">
                                Cake Flavour <span class="required-star">*</span>
                            </label>

                            <input type="text"
                                   name="flavour"
                                   class="form-control"
                                   value="{{ old('flavour') }}"
                                   placeholder="Chocolate, vanilla..."
                                   maxlength="255"
                                   required>
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">
                                Cake Weight (KG) <span class="required-star">*</span>
                            </label>

                            <input type="number"
                                   name="weight"
                                   class="form-control"
                                   min="0.5"
                                   max="100"
                                   step="0.25"
                                   value="{{ old('weight') }}"
                                   placeholder="Example: 1.5"
                                   required>
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">
                                Cake Shape <span class="required-star">*</span>
                            </label>

                            <input type="text"
                                   name="shape"
                                   class="form-control"
                                   value="{{ old('shape') }}"
                                   placeholder="Round, heart, custom..."
                                   maxlength="255"
                                   required>

                            <span class="field-hint">
                                You can request any custom shape.
                            </span>
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">Cake Theme</label>

                            <input type="text"
                                   name="theme"
                                   class="form-control"
                                   value="{{ old('theme') }}"
                                   placeholder="Floral, cartoon, wedding...">
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">Preferred Colours</label>

                            <input type="text"
                                   name="colour"
                                   class="form-control"
                                   value="{{ old('colour') }}"
                                   placeholder="Pink, white, golden...">
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">Cream Type</label>

                            <input type="text"
                                   name="cream_type"
                                   class="form-control"
                                   value="{{ old('cream_type') }}"
                                   placeholder="Buttercream, whipped cream...">
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">Decorations</label>

                            <textarea name="decorations"
                                      class="form-control"
                                      rows="2"
                                      placeholder="Describe the decorations you want...">{{ old('decorations') }}</textarea>
                        </div>

                    </div>

                </div>

            </div>


            {{-- SECTION 3 --}}
            <div class="booking-card">

                <div class="booking-card-header">

                    <div class="booking-number">03</div>

                    <div>
                        <h3>Personal Touch</h3>
                        <p>Add your message and share design inspiration.</p>
                    </div>

                </div>

                <div class="booking-card-body">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="form-label">Message on Cake</label>

                            <input type="text"
                                   name="cake_message"
                                   class="form-control"
                                   value="{{ old('cake_message') }}"
                                   maxlength="255"
                                   placeholder="Happy Birthday, Congratulations...">
                        </div>


                        <div class="col-md-6">
                            <label class="form-label">Reference Image</label>

                            <input type="file"
                                   name="reference_image"
                                   class="form-control"
                                   accept=".jpg,.jpeg,.png,.webp">

                            <div class="upload-note">
                                Upload your preferred cake design.
                                JPG, PNG or WEBP. Maximum 5 MB.
                            </div>
                        </div>


                        <div class="col-12">
                            <label class="form-label">Special Instructions</label>

                            <textarea name="special_instructions"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Mention allergies or any special requirements...">{{ old('special_instructions') }}</textarea>
                        </div>

                    </div>

                </div>

            </div>


            {{-- SECTION 4 --}}
            <div class="booking-card" id="delivery_section">

                <div class="booking-card-header">

                    <div class="booking-number">04</div>

                    <div>
                        <h3>Delivery Address</h3>
                        <p>Where should we deliver your special cake?</p>
                    </div>

                </div>

                <div class="booking-card-body">

                    <div class="row g-4">

                        <div class="col-12">
                            <label class="form-label">
                                Complete Address <span class="required-star">*</span>
                            </label>

                            <textarea name="delivery_address"
                                      id="delivery_address"
                                      class="form-control"
                                      rows="2"
                                      placeholder="House number, street, area...">{{ old('delivery_address') }}</textarea>
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">
                                City <span class="required-star">*</span>
                            </label>

                            <input type="text"
                                   name="delivery_city"
                                   id="delivery_city"
                                   class="form-control"
                                   value="{{ old('delivery_city') }}">
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">
                                State <span class="required-star">*</span>
                            </label>

                            <input type="text"
                                   name="delivery_state"
                                   id="delivery_state"
                                   class="form-control"
                                   value="{{ old('delivery_state') }}">
                        </div>


                        <div class="col-md-4">
                            <label class="form-label">
                                Pincode <span class="required-star">*</span>
                            </label>

                            <input type="text"
                                   name="delivery_pincode"
                                   id="delivery_pincode"
                                   class="form-control"
                                   value="{{ old('delivery_pincode') }}"
                                   maxlength="20">
                        </div>

                    </div>

                </div>

            </div>


            {{-- SUBMIT --}}
            <div class="booking-submit-area">

                <div>
                    <strong>Ready to create your special cake?</strong>

                    <small>
                        After reviewing your requirements, our team will send you the final price and advance payment details. Your order is confirmed only after you accept the quotation.
                    </small>
                </div>

                <button type="submit"
                        class="btn btn-book-cake"
                        id="submitBooking">

                    Submit Cake Booking &nbsp; →

                </button>

            </div>


            <div class="booking-footer-note">
                ✦ Thank you for choosing us to make your celebration sweeter. ✦
            </div>

        </form>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const fulfilment = document.getElementById('fulfilment_type');
    const deliverySection = document.getElementById('delivery_section');

    const deliveryFields = [
        'delivery_address',
        'delivery_city',
        'delivery_state',
        'delivery_pincode'
    ];

    function toggleDelivery() {

        const isDelivery = fulfilment.value === 'delivery';

        deliverySection.style.display = isDelivery ? 'block' : 'none';

        deliveryFields.forEach(function (id) {

            const field = document.getElementById(id);

            field.required = isDelivery;

            if (!isDelivery) {
                field.value = '';
            }

        });
    }

    fulfilment.addEventListener('change', toggleDelivery);

    toggleDelivery();


    const form = document.getElementById('customCakeBookingForm');
    const submitButton = document.getElementById('submitBooking');

    form.addEventListener('submit', function () {

        if (form.checkValidity()) {
            submitButton.disabled = true;
            submitButton.textContent = 'Submitting Your Booking...';
        }

    });

});
</script>

@endsection

