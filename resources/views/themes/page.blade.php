@extends('layouts.landing')

@section('title', $page->title)

@section('content')
    <div class="bg-light py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">{{ $page->title }}</h1>
            <p class="lead mb-0 text-muted">Information and resources for our valued visitors.</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <article class="prose text-dark" style="line-height: 1.8;">
                            {!! $page->content !!}
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
