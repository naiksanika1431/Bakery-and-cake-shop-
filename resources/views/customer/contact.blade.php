
@extends('layouts.customer.app')

@section('title', 'Contact Us | ZAPCODEX Bakery')

@section('content')

<style>
    .contact-page {
        --cp-wine: #63382e;
        --cp-dark: #40241f;
        --cp-accent: #ad6555;
        --cp-soft: #fff5ef;
        --cp-cream: #fffaf6;
        --cp-muted: #85766f;
        color: var(--cp-muted);
        overflow: hidden;
    }

    .contact-page * {
        box-sizing: border-box;
    }

    .contact-page h1,
    .contact-page h2,
    .contact-page h3,
    .contact-page h4,
    .contact-page h5,
    .contact-page h6 {
        color: var(--cp-wine);
    }

    .contact-page .cp-label {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        color: var(--cp-accent);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2.3px;
        text-transform: uppercase;
    }

    .contact-page .cp-label::before {
        content: "";
        width: 24px;
        height: 2px;
        border-radius: 5px;
        background: var(--cp-accent);
    }

    .contact-page .cp-heading {
        font-size: clamp(30px, 4vw, 43px);
        font-weight: 900;
        line-height: 1.17;
        letter-spacing: -1.3px;
    }

    .contact-page .cp-gradient-text {
        background: linear-gradient(100deg, #a34e42, #d28a70);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .contact-page .cp-description {
        color: var(--cp-muted);
        font-size: 14px;
        line-height: 1.9;
    }

    /* HERO */

    .cp-hero {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        padding: 85px 0 95px;
        background:
            radial-gradient(circle at 12% 25%, rgba(255,255,255,.85), transparent 26%),
            radial-gradient(circle at 90% 80%, rgba(255,255,255,.6), transparent 24%),
            linear-gradient(125deg, #fff1e8, #f6d9cc 52%, #edc7b8);
        text-align: center;
    }

    .cp-hero::before,
    .cp-hero::after {
        content: "";
        position: absolute;
        z-index: -1;
        border: 1px solid rgba(99,56,46,.12);
        border-radius: 50%;
    }

    .cp-hero::before {
        width: 350px;
        height: 350px;
        top: -190px;
        right: -90px;
    }

    .cp-hero::after {
        width: 260px;
        height: 260px;
        bottom: -150px;
        left: -70px;
    }

    .cp-hero-content {
        max-width: 760px;
        margin: auto;
    }

    .cp-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 11px 19px;
        border: 1px solid rgba(99,56,46,.15);
        border-radius: 50px;
        background: rgba(255,255,255,.55);
        color: var(--cp-wine);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.7px;
        text-transform: uppercase;
        backdrop-filter: blur(8px);
    }

    .cp-hero-badge i {
        color: var(--cp-accent);
        font-size: 15px;
    }

    .cp-hero h1 {
        margin: 24px 0 17px;
        font-size: clamp(43px, 6vw, 68px);
        font-weight: 900;
        line-height: 1.1;
        letter-spacing: -2.5px;
    }

    .cp-hero p {
        max-width: 630px;
        margin: auto;
        color: #79645c;
        font-size: 16px;
        line-height: 1.9;
    }

    /* QUICK CONTACT STRIP */

    .cp-quick-section {
        position: relative;
        z-index: 2;
        margin-top: -30px;
    }

    .cp-quick-card {
        display: flex;
        align-items: center;
        gap: 15px;
        height: 100%;
        padding: 21px 18px;
        border: 1px solid #f0e4de;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 12px 32px rgba(99,56,46,.07);
        transition: .3s ease;
    }

    .cp-quick-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 17px 35px rgba(99,56,46,.11);
    }

    .cp-quick-icon {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        background: #f9e9e1;
        color: var(--cp-accent);
        font-size: 21px;
    }

    .cp-quick-card strong {
        display: block;
        color: var(--cp-wine);
        font-size: 14px;
        font-weight: 800;
    }

    .cp-quick-card span {
        display: block;
        margin-top: 4px;
        color: #95857d;
        font-size: 11px;
    }

    /* MAIN CONTACT */

    .cp-main {
        padding: 95px 0 85px;
        background: var(--cp-cream);
    }

    .cp-contact-intro {
        padding: 12px 20px 0 0;
    }

    .cp-contact-intro h2 {
        margin: 16px 0;
    }

    .cp-contact-intro > p {
        margin-bottom: 30px;
    }

    /* CONTACT DETAILS */

    .cp-info-card {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 18px;
        margin-bottom: 14px;
        border: 1px solid #f0e6e0;
        border-radius: 15px;
        background: #fff;
        transition: .3s ease;
    }

    .cp-info-card:hover {
        transform: translateX(5px);
        border-color: #e6c6b8;
        box-shadow: 0 8px 22px rgba(99,56,46,.06);
    }

    .cp-info-icon {
        flex-shrink: 0;
        width: 49px;
        height: 49px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #f9e9e1;
        color: var(--cp-accent);
        font-size: 20px;
    }

    .cp-info-card h6 {
        margin: 2px 0 6px;
        font-size: 14px;
        font-weight: 900;
    }

    .cp-info-card p,
    .cp-info-card a {
        margin: 0;
        color: #887a73;
        font-size: 13px;
        line-height: 1.8;
        text-decoration: none;
    }

    .cp-info-card a:hover {
        color: var(--cp-accent);
    }

    /* SOCIAL */

    .cp-social-area {
        margin-top: 28px;
        padding: 22px;
        border: 1px solid #f0e5df;
        border-radius: 16px;
        background: #fff;
    }

    .cp-social-area h6 {
        margin-bottom: 14px;
        font-size: 14px;
        font-weight: 900;
    }

    .cp-social-links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .cp-social-link {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #f0ded5;
        border-radius: 13px;
        background: #fff8f4;
        color: var(--cp-accent);
        font-size: 18px;
        text-decoration: none;
        transition: .3s ease;
    }

    .cp-social-link:hover {
        transform: translateY(-4px);
        border-color: var(--cp-wine);
        background: var(--cp-wine);
        color: #fff;
    }

    /* FORM */

    .cp-form-card {
        position: relative;
        overflow: hidden;
        padding: 38px;
        border: 1px solid #f0e5df;
        border-radius: 23px;
        background: #fff;
        box-shadow: 0 18px 50px rgba(99,56,46,.065);
    }

    .cp-form-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, #63382e, #c47d68, #e8b6a1);
    }

    .cp-form-heading {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 28px;
    }

    .cp-form-heading-icon {
        flex-shrink: 0;
        width: 53px;
        height: 53px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: #f9e9e1;
        color: var(--cp-accent);
        font-size: 22px;
    }

    .cp-form-heading h3 {
        margin: 2px 0 7px;
        font-size: 23px;
        font-weight: 900;
    }

    .cp-form-heading p {
        margin: 0;
        color: #91827b;
        font-size: 12px;
        line-height: 1.7;
    }

    .cp-form-card .form-label {
        margin-bottom: 8px;
        color: var(--cp-wine);
        font-size: 12px;
        font-weight: 800;
    }

    .cp-form-card .form-control,
    .cp-form-card .form-select {
        min-height: 48px;
        padding: 11px 14px;
        border: 1px solid #eadfd9;
        border-radius: 11px;
        background-color: #fffdfb;
        color: #59463f;
        font-size: 13px;
        box-shadow: none;
        transition: .25s ease;
    }

    .cp-form-card textarea.form-control {
        min-height: 135px;
        resize: vertical;
    }

    .cp-form-card .form-control::placeholder {
        color: #b1a39c;
    }

    .cp-form-card .form-control:focus,
    .cp-form-card .form-select:focus {
        border-color: #bd8877;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(173,101,85,.11);
    }

    .cp-required {
        color: #b45d4f;
    }

    .cp-submit-btn {
        width: 100%;
        min-height: 51px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 8px;
        border: 0;
        border-radius: 12px;
        background: linear-gradient(110deg, #704338, #9b5a4d);
        color: #fff;
        font-size: 14px;
        font-weight: 900;
        box-shadow: 0 8px 20px rgba(99,56,46,.17);
        transition: .3s ease;
    }

    .cp-submit-btn:hover {
        transform: translateY(-3px);
        background: linear-gradient(110deg, #4e2b24, #754338);
        color: #fff;
        box-shadow: 0 12px 25px rgba(99,56,46,.22);
    }

    .cp-form-note {
        margin-top: 15px;
        color: #a0928a;
        font-size: 11px;
        line-height: 1.7;
        text-align: center;
    }

    /* VISIT SECTION */

    .cp-visit {
        padding: 85px 0;
        background: #fff;
    }

    .cp-visit-card {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        overflow: hidden;
        padding: 36px;
        border: 1px solid #efdcd2;
        border-radius: 22px;
        background:
            radial-gradient(circle at 100% 0%, rgba(255,255,255,.85), transparent 28%),
            linear-gradient(120deg, #fff1e8, #f5ded2);
    }

    .cp-visit-card h3 {
        margin: 13px 0 10px;
        font-size: 25px;
        font-weight: 900;
    }

    .cp-visit-card p {
        max-width: 570px;
        margin: 0;
        color: #88766e;
        font-size: 13px;
        line-height: 1.9;
    }

    .cp-visit-icon {
        flex-shrink: 0;
        width: 105px;
        height: 105px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255,255,255,.8);
        border-radius: 50%;
        background: rgba(255,255,255,.5);
        color: var(--cp-wine);
        font-size: 43px;
    }

    /* MAP */

    .cp-map-section {
        padding: 0 0 85px;
        background: #fff;
    }

    .cp-map-wrapper {
        overflow: hidden;
        padding: 8px;
        border: 1px solid #eee0d8;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 12px 35px rgba(99,56,46,.07);
    }

    .cp-map-placeholder {
        min-height: 230px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px;
        border-radius: 14px;
        background:
            radial-gradient(circle at 20% 20%, #fff, transparent 35%),
            linear-gradient(135deg, #f9e9df, #f2d9cc);
        text-align: center;
    }

    .cp-map-placeholder i {
        margin-bottom: 14px;
        color: var(--cp-accent);
        font-size: 43px;
    }

    .cp-map-placeholder h5 {
        font-size: 17px;
        font-weight: 900;
    }

    .cp-map-placeholder p {
        max-width: 450px;
        margin: 7px auto 0;
        color: #89766e;
        font-size: 12px;
        line-height: 1.8;
    }

    /* BOTTOM CTA */

    .cp-cta {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        padding: 75px 20px;
        background: linear-gradient(120deg, #70463b, #42251f);
        text-align: center;
    }

    .cp-cta::before,
    .cp-cta::after {
        content: "";
        position: absolute;
        z-index: -1;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
    }

    .cp-cta::before {
        width: 300px;
        height: 300px;
        top: -180px;
        left: -80px;
    }

    .cp-cta::after {
        width: 350px;
        height: 350px;
        right: -100px;
        bottom: -230px;
    }

    .cp-cta h2 {
        max-width: 700px;
        margin: 15px auto;
        color: #fff;
        font-size: clamp(28px, 4vw, 43px);
        font-weight: 900;
    }

    .cp-cta p {
        max-width: 550px;
        margin: 0 auto 24px;
        color: rgba(255,255,255,.73);
        font-size: 13px;
        line-height: 1.9;
    }

    .cp-cta .cp-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 27px;
        border-radius: 50px;
        background: #fff;
        color: var(--cp-wine);
        font-size: 13px;
        font-weight: 900;
        text-decoration: none;
        transition: .3s ease;
    }

    .cp-cta .cp-cta-btn:hover {
        transform: translateY(-4px);
        background: #f7e5dc;
    }

    /* RESPONSIVE */

    @media (max-width: 991px) {
        .cp-contact-intro {
            padding-right: 0;
        }

        .cp-form-card {
            padding: 30px;
        }

        .cp-quick-card {
            padding: 17px 13px;
        }
    }

    @media (max-width: 767px) {
        .cp-hero {
            padding: 70px 15px 80px;
        }

        .cp-hero h1 {
            letter-spacing: -1.5px;
        }

        .cp-hero p {
            font-size: 14px;
        }

        .cp-main,
        .cp-visit {
            padding: 65px 0;
        }

        .cp-form-card {
            padding: 25px 18px;
        }

        .cp-form-heading h3 {
            font-size: 20px;
        }

        .cp-visit-card {
            flex-direction: column;
            align-items: flex-start;
            padding: 27px;
        }

        .cp-visit-icon {
            width: 75px;
            height: 75px;
            font-size: 30px;
        }

        .cp-map-section {
            padding-bottom: 65px;
        }

        .cp-quick-card {
            gap: 10px;
        }

        .cp-quick-icon {
            width: 40px;
            height: 40px;
            font-size: 17px;
        }
    }
</style>

<div class="contact-page">

    <!-- HERO -->
    <section class="cp-hero">
        <div class="container">
            <div class="cp-hero-content">

                <span class="cp-hero-badge">
                    <i class="bi bi-chat-heart-fill"></i>
                    We'd Love To Hear From You
                </span>

                <h1>
                    Let's Make Something
                    <span class="cp-gradient-text">Sweet Together.</span>
                </h1>

                <p>
                    Have a question, need help choosing the perfect cake
                    or want to share your feedback? Our team would love
                    to hear from you.
                </p>

            </div>
        </div>
    </section>

    <!-- QUICK CONTACT -->
    <section class="cp-quick-section">
        <div class="container">
            <div class="row g-3">

                <div class="col-6 col-lg-3">
                    <div class="cp-quick-card">
                        <div class="cp-quick-icon">
                            <i class="bi bi-chat-dots-fill"></i>
                        </div>
                        <div>
                            <strong>Have a Question?</strong>
                            <span>We're happy to help</span>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="cp-quick-card">
                        <div class="cp-quick-icon">
                            <i class="bi bi-cake2-fill"></i>
                        </div>
                        <div>
                            <strong>Custom Cakes</strong>
                            <span>Let's plan your cake</span>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="cp-quick-card">
                        <div class="cp-quick-icon">
                            <i class="bi bi-chat-square-heart-fill"></i>
                        </div>
                        <div>
                            <strong>Share Feedback</strong>
                            <span>Your opinion matters</span>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="cp-quick-card">
                        <div class="cp-quick-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <div>
                            <strong>Customer Support</strong>
                            <span>Here to assist you</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CONTACT INFORMATION AND FORM -->
    <section class="cp-main">
        <div class="container">
            <div class="row g-5">

                <!-- LEFT: CONTACT INFORMATION -->
                <div class="col-lg-5">
                    <div class="cp-contact-intro">

                        <span class="cp-label">Get In Touch</span>

                        <h2 class="cp-heading">
                            Let's Talk About
                            <span class="cp-gradient-text">Something Sweet!</span>
                        </h2>

                        <p class="cp-description">
                            Whether you're planning a celebration,
                            looking for your favourite bakery treat
                            or simply want to say hello, we're always
                            happy to connect with you.
                        </p>

                        <!-- ADDRESS -->
                        <div class="cp-info-card">
                            <div class="cp-info-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>

                            <div>
                                <h6>Our Address</h6>
                                <p>
                                    Add Your Bakery Address Here
                                </p>
                            </div>
                        </div>

                        <!-- PHONE -->
                        <div class="cp-info-card">
                            <div class="cp-info-icon">
                                <i class="bi bi-telephone-fill"></i>
                            </div>

                            <div>
                                <h6>Phone Number</h6>
                                <p>
                                    <a href="tel:+91XXXXXXXXXX">
                                        +91 XXXXX XXXXX
                                    </a>
                                </p>
                            </div>
                        </div>

                        <!-- EMAIL -->
                        <div class="cp-info-card">
                            <div class="cp-info-icon">
                                <i class="bi bi-envelope-fill"></i>
                            </div>

                            <div>
                                <h6>Email Address</h6>
                                <p>
                                    <a href="mailto:info@zapcodexbakery.com">
                                        info@zapcodexbakery.com
                                    </a>
                                </p>
                            </div>
                        </div>

                        <!-- HOURS -->
                        <div class="cp-info-card">
                            <div class="cp-info-icon">
                                <i class="bi bi-clock-fill"></i>
                            </div>

                            <div>
                                <h6>Working Hours</h6>
                                <p>
                                    Monday - Sunday<br>
                                    9:00 AM - 9:00 PM
                                </p>
                            </div>
                        </div>

                        <!-- SOCIAL LINKS -->
                        <div class="cp-social-area">
                            <h6>Stay Connected With Us</h6>

                            <div class="cp-social-links">
                                <a href="#"
                                   class="cp-social-link"
                                   aria-label="Facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>

                                <a href="#"
                                   class="cp-social-link"
                                   aria-label="Instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>

                                <a href="#"
                                   class="cp-social-link"
                                   aria-label="WhatsApp">
                                    <i class="bi bi-whatsapp"></i>
                                </a>

                                <a href="mailto:info@zapcodexbakery.com"
                                   class="cp-social-link"
                                   aria-label="Email">
                                    <i class="bi bi-envelope-fill"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- RIGHT: CONTACT FORM -->
                <div class="col-lg-7">
                    <div class="cp-form-card">

                        <div class="cp-form-heading">
                            <div class="cp-form-heading-icon">
                                <i class="bi bi-pencil-square"></i>
                            </div>

                            <div>
                                <h3>Send Us a Message</h3>
                                <p>
                                    Fill in your details and tell us
                                    how we can help.
                                </p>
                            </div>
                        </div>

                        <form action="#" method="POST">
                            @csrf

                            <div class="row g-3">

                                <!-- NAME -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Your Name
                                        <span class="cp-required">*</span>
                                    </label>

                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           placeholder="Enter your full name"
                                           required>
                                </div>

                                <!-- EMAIL -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Email Address
                                        <span class="cp-required">*</span>
                                    </label>

                                    <input type="email"
                                           name="email"
                                           class="form-control"
                                           placeholder="Enter your email"
                                           required>
                                </div>

                                <!-- PHONE -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Phone Number
                                    </label>

                                    <input type="tel"
                                           name="phone"
                                           class="form-control"
                                           placeholder="Enter your phone number">
                                </div>

                                <!-- SUBJECT -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Subject
                                        <span class="cp-required">*</span>
                                    </label>

                                    <select name="subject"
                                            class="form-select"
                                            required>
                                        <option value="" selected disabled>
                                            Choose a subject
                                        </option>

                                        <option value="General Enquiry">
                                            General Enquiry
                                        </option>


                                        <option value="Product Enquiry">
                                            Product Enquiry
                                        </option>

                                        <option value="Order Support">
                                            Order Support
                                        </option>

                                        <option value="Feedback">
                                            Feedback
                                        </option>

                                        <option value="Other">
                                            Other
                                        </option>
                                    </select>
                                </div>

                                <!-- MESSAGE -->
                                <div class="col-12">
                                    <label class="form-label">
                                        Your Message
                                        <span class="cp-required">*</span>
                                    </label>

                                    <textarea name="message"
                                              class="form-control"
                                              rows="5"
                                              placeholder="Tell us how we can help you..."
                                              required></textarea>
                                </div>

                                <!-- SUBMIT -->
                                <div class="col-12">
                                    <button type="submit"
                                            class="cp-submit-btn">
                                        Send Your Message
                                        <i class="bi bi-send-fill"></i>
                                    </button>

                                    <p class="cp-form-note">
                                        <i class="bi bi-shield-check me-1"></i>
                                        Your information should be handled
                                        securely when the form is connected.
                                    </p>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- VISIT OUR BAKERY -->
    <section class="cp-visit">
        <div class="container">

            <div class="cp-visit-card">

                <div>
                    <span class="cp-label">Come Say Hello</span>

                    <h3>We'd Love To Welcome You!</h3>

                    <p>
                        Visit your nearest ZAPCODEX Bakery branch,
                        explore our delicious collection and enjoy
                        a little sweetness in your day.
                    </p>
                </div>

                <div class="cp-visit-icon">
                    <i class="bi bi-shop-window"></i>
                </div>

            </div>

        </div>
    </section>

    <!-- LOCATION SECTION -->
    <section class="cp-map-section">
        <div class="container">

            <div class="text-center mb-4">
                <span class="cp-label">Find Us</span>

                <h2 class="cp-heading mt-3">
                    Your Favourite Bakery Is <span class="cp-gradient-text">Closer.</span>
                </h2>

                <p class="cp-description">
                    Find your nearest branch and come visit us.
                </p>
            </div>

            <div class="cp-map-wrapper">
                <div class="cp-map-placeholder">
                    <i class="bi bi-geo-alt-fill"></i>

                    <h5>Find Your Nearest Branch</h5>

                    <p>
                        Branch-specific location details and an
                        interactive map can be connected here.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- BOTTOM CTA -->
    <section class="cp-cta">
        <div class="container">

            <span class="cp-label"
                  style="color:#efc3b2;">
                Something Sweet Is Waiting
            </span>

            <h2>
                Have A Celebration Coming Up?
            </h2>

            <p>
                Explore our bakery collection or plan a special
                something special for your next unforgettable moment.
            </p>

            <a href="{{ route('customer.products') }}"
               class="cp-cta-btn">
                Explore Our Products
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>
    </section>

</div>

@endsection
