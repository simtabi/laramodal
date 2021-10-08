@props([
    'subHeading',
    'selfCall' => false,
    'heading',
    'modal',
    'size'     => 'lg',
    'args',
])

<button
    onclick='Livewire.emit("openModal", "{{$modal}}", {{ json_encode(array_merge(($args ?? []), [
                        'modal'      => $modal      ?? '',
                        'size'       => $size       ?? '',
                        'heading'    => $heading    ?? '',
                        'subHeading' => $subHeading ?? '',
                    ])) }})'

    type="button"
    class="{{ $attributes->has('class') ? $attributes->get('class') : 'btn btn-primary' }}"
    data-toggle="modal"
>
    {{ $slot }}
</button>


{{--
 <button

        x-data
        x-on:click="$dispatch('open-x-modal', {
        modal : '{{ $modal }}',
        args  : {!! str_replace('"', '\'', e( json_encode(array_merge(($args ?? []), [
                        'size'       => $size       ?? '',
                        'heading'    => $heading    ?? '',
                        'subHeading' => $subHeading ?? '',
                    ])))) !!}
        })"
        type="button"
        class="{{ $attributes->has('class') ? $attributes->get('class') : 'btn btn-primary' }}"
        data-toggle="modal"
>
    {{ $slot }}
</button>

--}}
