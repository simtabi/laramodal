@props([
    'show' => false,
    'type',
    'icon',
])

@if($show)
    <div class="alert alert-{{ $type }} alert-dismissible fade show">
        <div class="media align-items-center">
            {!! $icon !!}
            <div class="media-body ml-2">
                {{ $slot }}
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    </div>
@endif