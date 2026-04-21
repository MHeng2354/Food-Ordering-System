@extends('layouts.app')

@section('content')
<div class="container p-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-center mb-4">
                <h1 style="font-size: 4.0rem;">Contact Us</h1>
                <p class="lead" style="font-size: 1.25rem;">If you have questions or need assistance, please fill out the form below and we will get back to you.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Your full name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Optional phone number">
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject of your message" required>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Feedback / Message</label>
                            <textarea class="form-control" id="message" name="message" rows="6" placeholder="Write your feedback here..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
