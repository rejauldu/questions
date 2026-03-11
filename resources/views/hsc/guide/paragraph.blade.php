@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <h3 class="mb-0">{{ $topic_name }}</h3>
            <span class="badge bg-info">Verified by BCS Cadre</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="text-muted mb-3">Sample Answer:</h5>
                    <div class="p-4 bg-light rounded border mb-4" style="line-height: 1.8; text-align: justify;">
                        {{ $paragraph_content }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-lightbulb"></i> Officer's Guidance</h6>
                        <p class="small">{{ $cadre_tips }}</p>
                        <hr>
                        <button class="btn btn-primary btn-sm w-100">Listen to Audio Guide</button>
                    </div>
                    <div class="mt-3">
                        <h6>Related Clusters:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($related_topics as $topic)
                                <a href="#" class="btn btn-sm btn-outline-secondary">{{ $topic }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection