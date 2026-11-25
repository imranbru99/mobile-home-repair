@extends('layouts.app')
@section('title', 'Contact Padline')
@section('content')
<section class="section">
    <div class="wrap grid-2">
        <div>
            <h2>Contact</h2>
            <p class="lede">Call (623) 555-0167, WhatsApp, or send a note — Peoria, AZ crews are ready.</p>
            <div class="panel">
                <p><strong>Phone:</strong> (623) 555-0167</p>
                <p><strong>Email:</strong> hello@padlinerepair.example</p>
                <p><strong>Address:</strong> 8840 W Peoria Avenue, Suite 140, Peoria, AZ 85345</p>
                <p><strong>Hours:</strong> Mon–Sat crews · emergency leak calls prioritized</p>
            </div>
        </div>
        <div>
            @if(session('success'))
                <div class="flash">{{ session('success') }}</div>
            @endif
            <form class="stack" method="post" action="{{ route('contact.store') }}">
                @csrf
                <div>
                    <label>Name</label>
                    <input name="name" value="{{ old('name') }}" required>
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div>
                    <label>Phone</label>
                    <input name="phone" value="{{ old('phone') }}">
                </div>
                <div>
                    <label>Subject</label>
                    <input name="subject" value="{{ old('subject') }}">
                </div>
                <div>
                    <label>Message</label>
                    <textarea name="message" rows="5" required>{{ old('message') }}</textarea>
                </div>
                <button class="btn btn-primary" type="submit">Send message</button>
            </form>
        </div>
    </div>
</section>
@endsection
