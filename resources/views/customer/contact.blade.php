
@extends('layouts.customer.app')

@section('title', 'Contact Us')

@section('content')

<!-- Contact Hero -->
<section class="py-5 text-center"
         style="background:linear-gradient(120deg,#f9e9df,#f4d4c5);">
    <div class="container py-4">
        <span class="text-uppercase fw-semibold" style="color:#9b5144;">
            We'd Love To Hear From You
        </span>
        <h1 class="display-4 fw-bold mt-2" style="color:#63382e;">
            Contact Us
        </h1>
        <p class="fs-5 text-secondary">
            Have a question or want to order a special cake? Get in touch!
        </p>
    </div>
</section>

<!-- Contact Information and Form -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">

            <!-- Contact Details -->
            <div class="col-lg-5">
                <span class="text-uppercase fw-semibold" style="color:#9b5144;">
                    Get In Touch
                </span>

                <h2 class="fw-bold mt-2 mb-3" style="color:#63382e;">
                    Let's Talk About Something Sweet!
                </h2>

                <p class="text-secondary lh-lg mb-4">
                    We're happy to help with product enquiries,
                    custom cake orders, celebrations and feedback.
                    Contact our bakery using the details below.
                </p>

                <!-- Address -->
                <div class="d-flex align-items-start mb-4">
                    <div class="rounded-3 p-3 me-3"
                         style="background:#f9e9df;">
                        <i class="bi bi-geo-alt-fill fs-4"
                           style="color:#9b5144;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Our Address</h6>
                        <p class="text-secondary mb-0">
                            Add Your Bakery Address Here
                        </p>
                    </div>
                </div>

                <!-- Phone -->
                <div class="d-flex align-items-start mb-4">
                    <div class="rounded-3 p-3 me-3"
                         style="background:#f9e9df;">
                        <i class="bi bi-telephone-fill fs-4"
                           style="color:#9b5144;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Phone Number</h6>
                        <p class="text-secondary mb-0">
                            +91 XXXXX XXXXX
                        </p>
                    </div>
                </div>

                <!-- Email -->
                <div class="d-flex align-items-start mb-4">
                    <div class="rounded-3 p-3 me-3"
                         style="background:#f9e9df;">
                        <i class="bi bi-envelope-fill fs-4"
                           style="color:#9b5144;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Email Address</h6>
                        <p class="text-secondary mb-0">
                            info@zapcodexbakery.com
                        </p>
                    </div>
                </div>

                <!-- Working Hours -->
                <div class="d-flex align-items-start mb-4">
                    <div class="rounded-3 p-3 me-3"
                         style="background:#f9e9df;">
                        <i class="bi bi-clock-fill fs-4"
                           style="color:#9b5144;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Working Hours</h6>
                        <p class="text-secondary mb-0">
                            Monday - Sunday<br>
                            9:00 AM - 9:00 PM
                        </p>
                    </div>
                </div>

                <!-- Social Media -->
                <h6 class="fw-bold mt-4">Follow Us</h6>
                <div class="d-flex gap-3">
                    <a href="#" class="fs-4" style="color:#9b5144;">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="fs-4" style="color:#9b5144;">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="fs-4" style="color:#9b5144;">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5">

                    <h3 class="fw-bold mb-2" style="color:#63382e;">
                        Send Us a Message
                    </h3>

                    <p class="text-secondary mb-4">
                        Fill out the form below and we'll be happy to assist you.
                    </p>

                    <form action="#" method="POST">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Your Name
                                </label>
                                <input type="text"
                                       class="form-control py-2"
                                       placeholder="Enter your name"
                                       required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Email Address
                                </label>
                                <input type="email"
                                       class="form-control py-2"
                                       placeholder="Enter your email"
                                       required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Phone Number
                                </label>
                                <input type="tel"
                                       class="form-control py-2"
                                       placeholder="Enter your phone number">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Subject
                                </label>
                                <select class="form-select py-2" required>
                                    <option value="">Select Subject</option>
                                    <option>General Enquiry</option>
                                    <option>Custom Cake Order</option>
                                    <option>Product Enquiry</option>
                                    <option>Feedback</option>
                                    <option>Other</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    Your Message
                                </label>
                                <textarea class="form-control"
                                          rows="5"
                                          placeholder="Write your message here..."
                                          required></textarea>
                            </div>

                            <div class="col-12">
                                <button type="submit"
                                        class="btn btn-bakery px-4 py-2">
                                    Send Message
                                    <i class="bi bi-send ms-2"></i>
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- Map Section -->
<section class="pb-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color:#63382e;">
                Find Our Bakery
            </h2>
            <p class="text-secondary">
                Visit us and enjoy freshly baked treats.
            </p>
        </div>

        <div class="rounded-4 overflow-hidden shadow-sm">
            <iframe
                src="https://www.google.com/maps?q=India&output=embed"
                width="100%"
                height="350"
                style="border:0;"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

@endsection
