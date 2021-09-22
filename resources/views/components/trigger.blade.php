@props([
    'selfCall' => false,
    'modal',
    'size',
    'heading',
    'subHeading',
    'args',
])

<button
        x-data x-on:click="$dispatch('open-x-modal', {
        modal : '{{ $modal }}',
        size  : '{{ $size ?? 'lg' }}',
        args  : {!! str_replace('"', '\'', e(json_encode(array_merge(($args ?? []), [
                        'modal'      => $modal      ?? '',
                        'size'       => $size       ?? '',
                        'heading'    => $heading    ?? '',
                        'subHeading' => $subHeading ?? '',
                    ])))) !!}
        })"
        type="button" class="{{ $attributes->has('class') ? $attributes->get('class') : 'btn btn-primary' }}"
        data-toggle="modal"
>
    {{ $slot }}
</button>