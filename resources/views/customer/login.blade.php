@extends('layouts.customer.app')

@section('title', 'Customer Login')

@section('content')

<div class="customer-login-page">

    <div class="login-decoration login-decoration-one"></div>
    <div class="login-decoration login-decoration-two"></div>

    <div class="container">
        <div class="login-wrapper">

            <!-- LEFT WELCOME PANEL -->
            <div class="login-welcome-panel">

                <div class="welcome-decoration welcome-decoration-one"></div>
                <div class="welcome-decoration welcome-decoration-two"></div>

                <div class="welcome-content">

                    <div class="welcome-brand-icon">
                        <i class="bi bi-flower1"></i>
                    </div>

                    <span class="welcome-eyebrow">
                        WELCOME TO ZAPCODEX
                    </span>

                    <h1>
                        A Little Sweetness
                        <em>Begins Here.</em>
                    </h1>

                    <p class="welcome-description">
                        Sign in to explore our delicious collection,
                        manage your orders and make every celebration
                        a little more special.
                    </p>

                    <div class="welcome-divider"></div>

                    <div class="welcome-features">

                        <div class="welcome-feature">
                            <span class="feature-icon">
                                <i class="bi bi-cake2"></i>
                            </span>
                            <div>
                                <strong>Freshly Baked</strong>
                                <small>Made with love and care</small>
                            </div>
                        </div>

                        <div class="welcome-feature">
                            <span class="feature-icon">
                                <i class="bi bi-gift"></i>
                            </span>
                            <div>
                                <strong>Made for Celebrations</strong>
                                <small>Special cakes for special moments</small>
                            </div>
                        </div>

                        <div class="welcome-feature">
                            <span class="feature-icon">
                                <i class="bi bi-heart"></i>
                            </span>
                            <div>
                                <strong>Made with Love</strong>
                                <small>Every bite tells a sweet story</small>
                            </div>
                        </div>

                    </div>

                    <div class="welcome-bottom-text">
                        <i class="bi bi-quote"></i>
                        Happiness is homemade, just like our cakes.
                    </div>

                </div>
            </div>


            <!-- RIGHT LOGIN PANEL -->
            <div class="login-form-panel">

                <div class="login-form-inner">

                    <div class="login-mobile-icon">
                        <i class="bi bi-flower1"></i>
                    </div>

                    <div class="login-heading">
                        <span class="login-eyebrow">YOUR ACCOUNT</span>

                        <h2>Welcome <em>Back!</em></h2>

                        <p>
                            Please enter your details to sign in
                            to your account.
                        </p>
                    </div>


                    <!-- LOGIN FORM -->
                    <form id="customerLoginForm" action="{{ route('customer.login.submit') }}" method="POST">

                        @csrf

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="login-field">

                            <label for="loginEmail">
                                Email Address
                            </label>

                            <div class="login-input-wrapper">
                                <i class="bi bi-envelope"></i>

                                <input
                                    type="email"
                                    id="loginEmail"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Enter your email address"
                                    autocomplete="email"
                                    required
                                >
                            </div>

                        </div>


                        <div class="login-field">

                            <div class="password-label-row">
                                <label for="loginPassword">
                                    Password
                                </label>

                                <a href="#" class="forgot-password-link">
                                    Forgot Password?
                                </a>
                            </div>

                            <div class="login-input-wrapper">
                                <i class="bi bi-lock"></i>

                                <input
                                    type="password"
                                    id="loginPassword"
                                    name="password"
                                    placeholder="Enter your password"
                                    autocomplete="current-password"
                                    required
                                >

                                <button
                                    type="button"
                                    class="password-toggle"
                                    id="togglePassword"
                                    aria-label="Show password"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                        </div>


                        <div class="login-options">

                            <label class="remember-option">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                >
                                <span>Remember me</span>
                            </label>

                        </div>


                        <button
                            type="submit"
                            class="login-submit-btn"
                        >
                            <span>Sign In to Your Account</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>


                        <div class="login-separator">
                            <span>YOUR SWEET JOURNEY</span>
                        </div>


                        <div class="register-prompt">
                            <p>
                                Don't have an account?
                                <a href="{{ route('customer.register') }}" class="register-link">
                                    Create Account
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            </p>
                        </div>

                    </form>


                    <div class="login-security-note">
                        <i class="bi bi-shield-check"></i>
                        <span>Your account details are kept secure.</span>
                    </div>

                </div>

            </div>

        </div>


        <div class="login-back-link">
            <a href="{{ url('/') }}">
                <i class="bi bi-arrow-left"></i>
                Back to Home
            </a>
        </div>

    </div>
</div>


@endsection


@push('styles')

<style>
/* =========================================
   CUSTOMER LOGIN PAGE
========================================= */

.customer-login-page {
    position: relative;
    overflow: hidden;
    padding: 65px 15px 75px;
    background:
        radial-gradient(circle at 8% 10%, rgba(255,255,255,.75), transparent 25%),
        radial-gradient(circle at 95% 90%, rgba(232,190,183,.3), transparent 28%),
        #FCEEEB;
    font-family: 'DM Sans', sans-serif;
}

.customer-login-page .container {
    position: relative;
    z-index: 2;
    max-width: 1120px;
}

.login-decoration {
    position: absolute;
    border: 1px solid rgba(89,33,34,.08);
    border-radius: 50%;
    pointer-events: none;
}

.login-decoration-one {
    top: 35px;
    left: -100px;
    width: 260px;
    height: 260px;
}

.login-decoration-two {
    right: -100px;
    bottom: 20px;
    width: 320px;
    height: 320px;
}


/* MAIN WRAPPER */

.login-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    overflow: hidden;
    min-height: 630px;
    border: 1px solid rgba(89,33,34,.08);
    border-radius: 28px;
    background: #FFFDFC;
    box-shadow: 0 25px 70px rgba(89,33,34,.12);
}


/* WELCOME PANEL */

.login-welcome-panel {
    position: relative;
    display: flex;
    align-items: center;
    overflow: hidden;
    padding: 65px 55px;
    background: linear-gradient(145deg, #793638 0%, #592122 55%, #421719 100%);
    color: #fff;
}

.login-welcome-panel::before {
    position: absolute;
    right: -120px;
    bottom: -130px;
    width: 350px;
    height: 350px;
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 50%;
    box-shadow:
        0 0 0 30px rgba(255,255,255,.025),
        0 0 0 60px rgba(255,255,255,.02);
    content: "";
}

.welcome-decoration {
    position: absolute;
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 50%;
    pointer-events: none;
}

.welcome-decoration-one {
    top: -100px;
    right: -80px;
    width: 260px;
    height: 260px;
}

.welcome-decoration-two {
    top: 40px;
    right: 45px;
    width: 90px;
    height: 90px;
}

.welcome-content {
    position: relative;
    z-index: 2;
    width: 100%;
}

.welcome-brand-icon {
    display: grid;
    width: 65px;
    height: 65px;
    margin-bottom: 35px;
    place-items: center;
    border: 1px solid rgba(255,255,255,.35);
    border-radius: 20px;
    background: rgba(255,255,255,.1);
    color: #FFE7DC;
    font-size: 2rem;
}

.welcome-eyebrow,
.login-eyebrow {
    display: block;
    margin-bottom: 15px;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .3em;
}

.welcome-eyebrow {
    color: #EBC6B8;
}

.login-welcome-panel h1 {
    margin: 0;
    color: #fff;
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: clamp(2.7rem, 4vw, 3.7rem);
    line-height: 1.12;
}

.login-welcome-panel h1 em {
    display: block;
    margin-top: 5px;
    color: #F2C9B8;
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.18em;
    font-weight: 600;
}

.welcome-description {
    max-width: 390px;
    margin: 24px 0 25px;
    color: rgba(255,255,255,.76);
    font-size: .91rem;
    line-height: 1.9;
}

.welcome-divider {
    width: 55px;
    height: 2px;
    margin: 30px 0;
    background: #E8BDAE;
}

.welcome-features {
    display: grid;
    gap: 23px;
}

.welcome-feature {
    display: flex;
    align-items: center;
    gap: 15px;
}

.feature-icon {
    display: grid;
    width: 43px;
    height: 43px;
    flex-shrink: 0;
    place-items: center;
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 13px;
    background: rgba(255,255,255,.09);
    color: #F6D5C7;
    font-size: 1.15rem;
}

.welcome-feature strong,
.welcome-feature small {
    display: block;
}

.welcome-feature strong {
    color: #fff;
    font-size: .84rem;
    font-weight: 700;
}

.welcome-feature small {
    margin-top: 4px;
    color: rgba(255,255,255,.63);
    font-size: .73rem;
}

.welcome-bottom-text {
    margin-top: 43px;
    color: #EBC6B8;
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-size: 1.15rem;
    font-style: italic;
}

.welcome-bottom-text i {
    margin-right: 7px;
}


/* LOGIN FORM PANEL */

.login-form-panel {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 55px 55px;
    background: #FFFDFC;
}

.login-form-inner {
    width: 100%;
    max-width: 390px;
}

.login-mobile-icon {
    display: none;
}

.login-eyebrow {
    color: #9B5144;
}

.login-heading {
    margin-bottom: 36px;
}

.login-heading h2 {
    margin: 0;
    color: #211414;
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: 2.8rem;
    line-height: 1.15;
}

.login-heading h2 em {
    color: #793638;
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-weight: 600;
}

.login-heading p {
    margin: 13px 0 0;
    color: #806C68;
    font-size: .85rem;
    line-height: 1.7;
}


/* INPUT FIELDS */

.login-field {
    margin-bottom: 23px;
}

.login-field label {
    display: block;
    margin-bottom: 10px;
    color: #382322;
    font-size: .79rem;
    font-weight: 700;
}

.login-input-wrapper {
    position: relative;
    display: flex;
    min-height: 53px;
    align-items: center;
    border: 1px solid #EBD5D1;
    border-radius: 12px;
    background: #FFF9F6;
    transition: all .25s ease;
}

.login-input-wrapper:focus-within {
    border-color: #793638;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(89,33,34,.07);
}

.login-input-wrapper > i:first-child {
    margin-left: 16px;
    color: #9B5144;
    font-size: 1.05rem;
}

.login-input-wrapper input {
    width: 100%;
    min-width: 0;
    height: 51px;
    padding: 0 14px;
    border: 0;
    outline: none;
    background: transparent;
    color: #211414;
    font-family: 'DM Sans', sans-serif;
    font-size: .82rem;
}

.login-input-wrapper input::placeholder {
    color: #AD9690;
}

.login-input-wrapper input:focus {
    outline: none;
    box-shadow: none;
}

.password-label-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.forgot-password-link {
    color: #793638;
    font-size: .72rem;
    font-weight: 700;
    transition: color .2s ease;
}

.forgot-password-link:hover {
    color: #421719;
    text-decoration: underline;
}

.password-toggle {
    display: grid;
    width: 44px;
    height: 44px;
    flex-shrink: 0;
    place-items: center;
    border: 0;
    background: transparent;
    color: #806C68;
    cursor: pointer;
    font-size: 1rem;
}

.password-toggle:hover {
    color: #592122;
}


/* REMEMBER ME */

.login-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 5px 0 26px;
}

.remember-option {
    display: inline-flex !important;
    align-items: center;
    gap: 9px;
    margin: 0 !important;
    color: #806C68 !important;
    font-size: .77rem !important;
    font-weight: 500 !important;
    cursor: pointer;
}

.remember-option input {
    width: 16px;
    height: 16px;
    accent-color: #592122;
    cursor: pointer;
}


/* SUBMIT BUTTON */

.login-submit-btn {
    display: flex;
    width: 100%;
    min-height: 54px;
    align-items: center;
    justify-content: center;
    gap: 13px;
    border: 1px solid #592122;
    border-radius: 50px;
    background: linear-gradient(110deg, #793638, #592122);
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: .84rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 8px 18px rgba(89,33,34,.16);
    transition: all .25s ease;
}

.login-submit-btn:hover {
    transform: translateY(-2px);
    background: #421719;
    box-shadow: 0 12px 23px rgba(89,33,34,.23);
}

.login-submit-btn i {
    font-size: 1.05rem;
    transition: transform .25s ease;
}

.login-submit-btn:hover i {
    transform: translateX(4px);
}


/* NOTICE AND FOOTER */

.login-demo-notice {
    display: none;
    margin-top: 15px;
    padding: 12px 14px;
    border: 1px solid #EBD5D1;
    border-radius: 10px;
    background: #FFF3EF;
    color: #793638;
    font-size: .77rem;
    line-height: 1.6;
}

.login-demo-notice.show {
    display: block;
}

.login-separator {
    display: flex;
    align-items: center;
    gap: 13px;
    margin: 32px 0 25px;
    color: #A58D87;
    font-size: .61rem;
    font-weight: 700;
    letter-spacing: .18em;
}

.login-separator::before,
.login-separator::after {
    height: 1px;
    flex: 1;
    background: #F0E2DE;
    content: "";
}

.register-prompt {
    text-align: center;
}

.register-prompt p {
    margin: 0;
    color: #806C68;
    font-size: .79rem;
}

.register-link {
    margin-left: 4px;
    color: #793638;
    font-weight: 700;
}

.register-link:hover {
    color: #421719;
    text-decoration: underline;
}

.register-link i {
    margin-left: 2px;
    font-size: .7rem;
}

.login-security-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 35px;
    color: #9B8983;
    font-size: .69rem;
}

.login-security-note i {
    color: #793638;
    font-size: .9rem;
}


/* BACK TO HOME */

.login-back-link {
    margin-top: 25px;
    text-align: center;
}

.login-back-link a {
    color: #793638;
    font-size: .8rem;
    font-weight: 700;
    transition: color .2s ease;
}

.login-back-link a:hover {
    color: #421719;
}

.login-back-link i {
    margin-right: 6px;
}


/* RESPONSIVE */

@media (max-width: 991px) {

    .login-wrapper {
        grid-template-columns: .85fr 1.15fr;
    }

    .login-welcome-panel {
        padding: 45px 32px;
    }

    .login-form-panel {
        padding: 45px 35px;
    }

    .login-welcome-panel h1 {
        font-size: 2.7rem;
    }
}

@media (max-width: 767px) {

    .customer-login-page {
        padding: 35px 12px 45px;
    }

    .login-wrapper {
        grid-template-columns: 1fr;
        border-radius: 22px;
    }

    .login-welcome-panel {
        display: none;
    }

    .login-form-panel {
        padding: 42px 25px;
    }

    .login-form-inner {
        max-width: 440px;
    }

    .login-mobile-icon {
        display: grid;
        width: 53px;
        height: 53px;
        margin-bottom: 25px;
        place-items: center;
        border: 1px solid #EBD5D1;
        border-radius: 16px;
        background: #FCEEEB;
        color: #592122;
        font-size: 1.7rem;
    }

    .login-heading {
        margin-bottom: 30px;
    }

    .login-heading h2 {
        font-size: 2.5rem;
    }

    .login-security-note {
        margin-top: 27px;
    }
}

@media (max-width: 420px) {

    .login-form-panel {
        padding: 35px 19px;
    }

    .login-heading h2 {
        font-size: 2.2rem;
    }

    .login-options {
        margin-bottom: 22px;
    }

    .register-prompt p {
        font-size: .73rem;
    }
}

</style>

@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('loginPassword');
    const toggleButton = document.getElementById('togglePassword');

    if (toggleButton && passwordInput) {
        toggleButton.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';

            toggleButton.innerHTML = isPassword
                ? '<i class="bi bi-eye-slash"></i>'
                : '<i class="bi bi-eye"></i>';

            toggleButton.setAttribute(
                'aria-label',
                isPassword ? 'Hide password' : 'Show password'
            );
        });
    }
});
</script>
@endpush
