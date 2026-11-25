@extends('layouts.app')

@section('title', 'Padline Mobile Repair')
@section('meta_description', 'Roof coats, soft-floor fixes, skirting, and leveling for West Valley mobile homes — crews who know park rules and monsoon seasons.')

@section('content')
<section class="hero">
    <div class="wrap hero-inner">
        <h1>Padline<br>Mobile Repair</h1>
        <p>Roof coats, soft-floor fixes, skirting, and leveling for West Valley mobile homes — crews who know park rules and monsoon seasons.</p>
        <div class="hero-ctas">
            <a class="btn btn-primary" href="{{ route('contact') }}">Request a repair visit</a>
            <a class="btn btn-ghost" href="{{ route('services') }}">View repair services</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <h2>Services</h2>
        <p class="lede">Padline repairs roofs, floors, skirting, leveling, and weatherization for mobile homes across Peoria and the West Valley.</p>
        <div class="grid-3">
            @foreach([
                ['roof-coat-repair','Roof Coat & Leaks','Monsoon-ready coats and flashings.','https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1600&q=80'],
                ['soft-floor-repair','Soft Floor Repair','Safe decks under baths and kitchens.','https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=1600&q=80'],
                ['skirting-repair','Skirting Repair','Ventilated panels parks accept.','https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=1600&q=80'],
            ] as [$slug,$title,$blurb,$img])
                <a class="service-tile" href="{{ route('services.show', $slug) }}">
                    <img src="{{ $img }}" alt="{{ $title }}">
                    <div class="pad">
                        <h3>{{ $title }}</h3>
                        <p>{{ $blurb }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Why Padline</h2>
        <p class="lede">West Valley techs who repair mobile homes as a specialty — not a side hustle after stick-built remodels.</p>
        <div class="grid-3">
            <div class="panel">
                <h3>Park-aware crews</h3>
                <p>We work within park rules, neighbor spacing, and utility easements without drama.</p>
            </div>
            <div class="panel">
                <h3>Monsoon-ready roofs</h3>
                <p>Coatings and flashings timed for Arizona heat and summer storms.</p>
            </div>
            <div class="panel">
                <h3>ERP-run tickets</h3>
                <p>Work orders, parts, invoices, and appointments stay in one stack for owners and park managers.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>Ops dashboard preview</h2>
        <p class="lede">A glimpse of the Padline ERP used to dispatch crews, track inventory, and close invoices.</p>
        <div class="dash-preview">
            <div>
                <div class="grid-3" style="grid-template-columns:repeat(3,1fr)">
                    <div class="metric"><strong>18</strong> Open tickets</div>
                    <div class="metric"><strong>$96k</strong> Pipeline</div>
                    <div class="metric"><strong>42</strong> Roof kits</div>
                </div>
                <div class="bars">
                    <span style="height:45%"></span><span style="height:70%"></span><span style="height:55%"></span>
                    <span style="height:85%"></span><span style="height:60%"></span><span style="height:92%"></span>
                    <span style="height:68%"></span>
                </div>
            </div>
            <div>
                <h3 style="font-family:var(--font-display);margin-top:0">Live modules</h3>
                <ul style="line-height:1.9;padding-left:1.1rem;color:rgba(255,255,255,.86)">
                    <li>Customers & jobs</li>
                    <li>Inventory / products</li>
                    <li>Invoices & payments</li>
                    <li>Appointments</li>
                    <li>AI Studio & chatbot logs</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:0">
    <div class="wrap">
        <h2>From the journal</h2>
        <p class="lede">Practical notes for customers and crews.</p>
        <div class="grid-3">
            @foreach($posts as $post)
                <a class="panel" href="{{ route('blog.show', $post->slug) }}">
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->excerpt }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
