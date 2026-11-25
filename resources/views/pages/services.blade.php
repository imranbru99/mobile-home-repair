@extends('layouts.app')
@section('title', 'Services')
@section('content')
<section class="section">
    <div class="wrap">
        <h2>Services</h2>
        <p class="lede">Padline repairs roofs, floors, skirting, leveling, and weatherization for mobile homes across Peoria and the West Valley.</p>
        <div class="grid-3">
            @foreach([
                ['roof-coat-repair','Roof Coat & Leak Repair','https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1600&q=80'],
                ['soft-floor-repair','Soft Floor Repair','https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=1600&q=80'],
                ['skirting-repair','Skirting Repair','https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=1600&q=80'],
                ['leveling-blocks','Leveling & Support Blocks','https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=1600&q=80'],
                ['weather-seal','Weather Seal Package','https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=80'],
                ['park-inspection-prep','Park Inspection Prep','https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1600&q=80'],
            ] as [$slug,$title,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad"><h3>{{ $title }}</h3></div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
