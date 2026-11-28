@extends('layouts.customer.app')

@section('title', 'Customer Registration')

@section('content')

<div class="customer-register-page">
    <div class="register-shell">

        <!-- LEFT WELCOME PANEL -->
        <div class="register-welcome">
            <div class="welcome-content">
                <div class="welcome-icon">
                    <i class="bi bi-flower1"></i>
                </div>

                <span class="welcome-eyebrow">ZAPCODEX BAKERY</span>

                <h1>Join Our<br>Sweet Community.</h1>

                <p>
                    Create your account and enjoy a sweeter shopping
                    experience with freshly baked happiness.
                </p>

                <div class="welcome-features">
                    <div>
                        <i class="bi bi-check-circle-fill"></i>
                        Easy online ordering
                    </div>
                    <div>
                        <i class="bi bi-check-circle-fill"></i>
                        Personalized cake bookings
                    </div>
                    <div>
                        <i class="bi bi-check-circle-fill"></i>
                        Track your orders
                    </div>
                </div>

                <div class="welcome-decoration decoration-one"></div>
                <div class="welcome-decoration decoration-two"></div>
            </div>

            <div class="welcome-bottom">
                <span>Made with love, baked with happiness.</span>
            </div>
        </div>

        <!-- RIGHT REGISTRATION FORM -->
        <div class="register-form-panel">
            <div class="register-form-container">

                <div class="form-heading">
                    <span class="form-eyebrow">CREATE ACCOUNT</span>
                    <h2>Create Your Account</h2>
                    <p>Fill in your details to get started.</p>
                </div>

                <form id="customerRegisterForm" action="{{ route('customer.register.submit') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <div class="form-group">
                        <label for="registerName">Full Name</label>
                        <div class="input-wrapper">
                            <i class="bi bi-person"></i>
                            <input
                                type="text"
                                id="registerName"
                                name="name" value="{{ old('name') }}"
                                placeholder="Enter your full name"
                                autocomplete="name"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="registerEmail">Email Address</label>
                        <div class="input-wrapper">
                            <i class="bi bi-envelope"></i>
                            <input
                                type="email"
                                id="registerEmail"
                                name="email" value="{{ old('email') }}"
                                placeholder="Enter your email address"
                                autocomplete="email"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="registerPhone">Mobile Number</label>
                        <div class="input-wrapper">
                            <i class="bi bi-telephone"></i>
                            <input
                                type="tel"
                                id="registerPhone"
                                name="phone" value="{{ old('phone') }}"
                                placeholder="Enter your mobile number"
                                autocomplete="tel"
                                pattern="[0-9]{10}"
                                maxlength="10"
                                inputmode="numeric"
                                required
                            >
                        </div>
                        <small class="field-hint">Enter your 10-digit mobile number.</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="registerPassword">Password</label>
                            <div class="input-wrapper">
                                <i class="bi bi-lock"></i>
                                <input
                                    type="password"
                                    id="registerPassword"
                                    name="password"
                                    placeholder="Create password"
                                    autocomplete="new-password"
                                    minlength="8"
                                    required
                                >
                                <button type="button" class="password-toggle" data-target="registerPassword" aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="registerConfirmPassword">Confirm Password</label>
                            <div class="input-wrapper">
                                <i class="bi bi-shield-lock"></i>
                                <input
                                    type="password"
                                    id="registerConfirmPassword"
                                    name="password_confirmation"
                                    placeholder="Confirm password"
                                    autocomplete="new-password"
                                    minlength="8"
                                    required
                                >
                                <button type="button" class="password-toggle" data-target="registerConfirmPassword" aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <label class="terms-row">
                        <input type="checkbox" id="registerTerms" name="terms" value="1" required>
                        <span>
                            I agree to the
                            <a href="#" target="_blank">Terms &amp; Conditions</a>
                            and
                            <a href="#" target="_blank">Privacy Policy</a>.
                        </span>
                    </label>

                    <button type="submit" class="register-submit">
                        Create Account
                        <i class="bi bi-arrow-right"></i>
                    </button>

                    <div class="register-divider">
                        <span>ALREADY A MEMBER?</span>
                    </div>

                    <p class="login-prompt">
                        Already have an account?
                        <a href="{{ route('customer.login') }}">Login here</a>
                    </p>

                    <a href="{{ route('customer.home') }}" class="back-home">
                        <i class="bi bi-arrow-left"></i>
                        Back to Home
                    </a>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
.customer-register-page {
    --register-wine: #592122;
    --register-wine-dark: #421719;
    --register-pink: #FCEEEB;
    --register-border: #ead8d4;
    --register-muted: #927d78;
    background: #fffaf8;
    min-height: calc(100vh - 150px);
    padding: 55px 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'DM Sans', sans-serif;
}

.customer-register-page * {
    box-sizing: border-box;
}

.register-shell {
    width: 100%;
    max-width: 1120px;
    min-height: 700px;
    display: grid;
    grid-template-columns: 0.9fr 1.1fr;
    background: #fff;
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 22px 65px rgba(89, 33, 34, .10);
    border: 1px solid rgba(89, 33, 34, .07);
}

.register-welcome {
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 65px 48px 35px;
    color: #fff;
    background: linear-gradient(145deg, #793638 0%, #592122 55%, #421719 100%);
}

.welcome-content {
    position: relative;
    z-index: 2;
}

.welcome-icon {
    width: 57px;
    height: 57px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255,255,255,.35);
    border-radius: 17px;
    font-size: 28px;
    margin-bottom: 30px;
    color: #f9d9d2;
}

.welcome-eyebrow,
.form-eyebrow {
    display: block;
    font-size: .72rem;
    font-weight: 800;
    letter-spacing: 2.5px;
}

.welcome-eyebrow {
    color: #f3c9c1;
    margin-bottom: 17px;
}

.register-welcome h1 {
    color: #fff;
    font-family: 'DM Serif Display', serif;
    font-size: clamp(2.4rem, 4vw, 3.5rem);
    line-height: 1.12;
    font-weight: 400;
    margin: 0 0 22px;
}

.register-welcome p {
    color: rgba(255,255,255,.78);
    font-size: .95rem;
    line-height: 1.9;
    max-width: 350px;
    margin-bottom: 35px;
}

.welcome-features {
    display: grid;
    gap: 17px;
}

.welcome-features div {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #fff4f1;
    font-size: .88rem;
}

.welcome-features i {
    color: #f3c9c1;
    font-size: 1.05rem;
}

.welcome-bottom {
    position: relative;
    z-index: 2;
    color: rgba(255,255,255,.65);
    font-size: .77rem;
    letter-spacing: .5px;
}

.welcome-decoration {
    position: absolute;
    border: 1px solid rgba(255,255,255,.09);
    border-radius: 50%;
    pointer-events: none;
}

.decoration-one {
    width: 260px;
    height: 260px;
    right: -130px;
    top: 80px;
}

.decoration-two {
    width: 180px;
    height: 180px;
    right: -90px;
    top: 120px;
}

.register-form-panel {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 55px;
}

.register-form-container {
    width: 100%;
    max-width: 470px;
}

.form-heading {
    margin-bottom: 28px;
}

.form-eyebrow {
    color: #a16c65;
    margin-bottom: 10px;
}

.form-heading h2 {
    font-family: 'DM Serif Display', serif;
    color: var(--register-wine, #592122);
    font-size: 2.15rem;
    font-weight: 400;
    margin: 0 0 7px;
}

.form-heading p {
    color: #927d78;
    font-size: .9rem;
    margin: 0;
}

.customer-register-page .form-group {
    margin-bottom: 18px;
}

.customer-register-page .form-group label {
    display: block;
    color: #4d3430;
    font-size: .81rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.input-wrapper {
    height: 49px;
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 0 14px;
    border: 1px solid var(--register-border);
    border-radius: 10px;
    background: #fffdfc;
    transition: border-color .2s, box-shadow .2s;
}

.input-wrapper:focus-within {
    border-color: #a76b68;
    box-shadow: 0 0 0 3px rgba(89,33,34,.07);
}

.input-wrapper > i {
    color: #a27d77;
    font-size: 1rem;
    flex-shrink: 0;
}

.input-wrapper input {
    width: 100%;
    min-width: 0;
    height: 100%;
    padding: 0;
    border: 0;
    outline: 0;
    background: transparent;
    color: #4d3430;
    font-family: inherit;
    font-size: .84rem;
}

.input-wrapper input::placeholder {
    color: #b5a6a2;
}

.password-toggle {
    border: 0;
    background: transparent;
    color: #94736e;
    cursor: pointer;
    padding: 5px;
    font-size: 1rem;
}

.field-hint {
    display: block;
    color: #a18e89;
    font-size: .71rem;
    margin-top: 6px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 13px;
}

.terms-row {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    color: #796761;
    font-size: .77rem;
    line-height: 1.6;
    margin: 3px 0 22px;
    cursor: pointer;
}

.terms-row input {
    accent-color: #592122;
    margin-top: 4px;
    flex-shrink: 0;
}

.terms-row a,
.login-prompt a {
    color: #793638;
    font-weight: 700;
    text-decoration: none;
}

.terms-row a:hover,
.login-prompt a:hover {
    text-decoration: underline;
}

.register-submit {
    width: 100%;
    min-height: 51px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    border: 0;
    border-radius: 10px;
    background: linear-gradient(110deg, #793638, #592122);
    color: #fff;
    font-family: inherit;
    font-size: .9rem;
    font-weight: 700;
    cursor: pointer;
    transition: transform .2s, box-shadow .2s;
}

.register-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 9px 20px rgba(89,33,34,.18);
}

.register-divider {
    display: flex;
    align-items: center;
    gap: 13px;
    margin: 25px 0 15px;
    color: #b19e99;
    font-size: .66rem;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.register-divider::before,
.register-divider::after {
    content: "";
    flex: 1;
    height: 1px;
    background: #eee3df;
}

.login-prompt {
    text-align: center;
    color: #927d78;
    font-size: .83rem;
    margin: 0 0 22px;
}

.back-home {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    color: #927d78;
    text-decoration: none;
    font-size: .8rem;
    font-weight: 600;
}

.back-home:hover {
    color: #592122;
}

@media (max-width: 900px) {
    .customer-register-page {
        padding: 30px 16px;
    }

    .register-shell {
        max-width: 650px;
        grid-template-columns: 1fr;
    }

    .register-welcome {
        min-height: 280px;
        padding: 35px;
    }

    .welcome-icon {
        margin-bottom: 18px;
    }

    .register-welcome h1 {
        font-size: 2.5rem;
    }

    .register-welcome p {
        margin-bottom: 20px;
    }

    .welcome-features {
        grid-template-columns: 1fr 1fr;
    }

    .welcome-bottom {
        margin-top: 25px;
    }

    .register-form-panel {
        padding: 40px 35px;
    }
}

@media (max-width: 520px) {
    .customer-register-page {
        padding: 18px 10px;
    }

    .register-shell {
        border-radius: 17px;
    }

    .register-welcome {
        min-height: auto;
        padding: 30px 24px;
    }

    .register-welcome h1 {
        font-size: 2.2rem;
    }

    .welcome-features {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .register-form-panel {
        padding: 32px 22px;
    }

    .form-heading h2 {
        font-size: 1.8rem;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.customer-register-page .password-toggle').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.target);
            const icon = button.querySelector('i');

            if (!input || !icon) return;

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('bi-eye', !isPassword);
            icon.classList.toggle('bi-eye-slash', isPassword);
            button.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
        });
    });
});
</script>
@endpush
