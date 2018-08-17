@extends('layouts.master')
@section('title', 'Start -')
@section('content')

    <div id="global-breadcrumb" class="header-context">
        <ol role="breadcrumbs" class="group">
            <li><a href="https://www.gov.uk">Home</a></li>
        </ol>
    </div>
    
    <div class="grid-row">
        <div class="two-thirds float--left">
            <h1 class="heading-large">APHA Animal disease testing service</h1>
            
            <section class="landing-section">
                <p>This service is for Vets and Veterinary workers to create and track animal disease testing submissions, and receive test results online.</p>

                <p class="list__heading">You need:</p>
                <ul class="list">
                    <li class="list__item">A registered account - see below to register.</li>
                    <li class="list__item">County Parish Holding number (CPH) or postcode for animal's location.</li>
                </ul>

                <a href="/login" class="button button-get-started">Start now</a>
            </section>

            <section class="landing-section">
                <h2>Register your practice</h2>

                <p>Before you can submit a test using our online application your practice must be registered with APHA.</p>

                <a href="/register">Register your practice</a>
            </section>

            <section class="landing-section">
                <h4 class="push-double--top push--bottom">When you can't use this service</h4>

                <p>For import, export, and statutory testing submissions – continue to use <a href="http://ahvla.defra.gov.uk/vet-gateway/surveillance/forms.htm" target="_blank">existing paper channels and instruction</a>.</p>

                <p>For submission of carcase material for post mortem examination – contact your designated <a href="http://ahvla.defra.gov.uk/postcode/pme.asp" target="_blank">PME provider</a>.</p>
            </section>

            <section class="landing-section">
                <h4 class="push-double--top push--bottom">Help using the service</h4>

                <p>If you would like to discuss a clinical case, selection of tests, or to discuss results, contact your designated <a href="https://www.gov.uk/government/organisations/animal-and-plant-health-agency/about/access-and-opening#veterinary-investigation-centres" target="_blank">Veterinary Investigation Centre</a>.</p>

                <p>If you need assistance with the web application you can <a href="/help">contact our IT support team</a>.</p>
            </section>
            
        </div>
        <div class="one-third float--left">
            <aside class="related">
                <h2 class="related__heading">Elsewhere on GOV.UK</h2>
                <nav role="navigation" class="related__nav">
                    <ul class="related__list">
                        <li class="related__item"><a href="https://www.gov.uk/guidance/laboratory-test-price-lists" class="related__link" target="_blank">APHA laboratory test price list</a></li>
                    </ul>
                </nav>
                <h3 class="related__subheading">See also</h3>
                <nav role="navigation" class="related__nav">
                    <ul class="related__list">
                        <li class="related__item"><a href="http://ahvla.defra.gov.uk/vet-gateway/index.htm" class="related__link" target="_blank">APHA Vet Gateway</a></li>
                        <li class="related__item"><a href="http://ahvla.defra.gov.uk/vet-gateway/surveillance/index.htm" class="related__link" target="_blank">APHA scanning surveillance</a></li>
                        <li class="related__item"><a href="http://ahvla.defra.gov.uk/vet-gateway/surveillance/diagnostic-support.htm" class="related__link" target="_blank">Diagnostic support</a></li>
                        <li class="related__item"><a href="#content" class="related__link--top">Return to top ↑</a></li>
                    </ul>
                </nav>
            </aside>
        </div>
    </div>

@stop