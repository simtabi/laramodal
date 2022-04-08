@props([
'subHeading',
'isButton' => true,
'selfCall' => false,
'heading',
'modal',
'size'     => 'lg',
'tooltip'  => null,
'args',
])

@if($isButton)<button type="button" @else <a href="#" @endif

onclick='emitLivewire()'
        {!! $attributes->merge(['class' => 'align-items-center']) !!}

@if(!empty($tooltip))
    {!! $tooltip !!}
        @endif
>
    {!! $slot !!}
    @if($isButton) </button> @else </a> @endif
<script>
    function emitLivewire(e) {
    e.preventDefault();
        Livewire.emit("openModal", "{{$modal}}", {{ json_encode(array_merge(($args ?? []), [
    'modal'      => $modal      ?? '',
    'size'       => $size       ?? '',
    'heading'    => $heading    ?? '',
    'subHeading' => $subHeading ?? '',
])) }})
    }
</script>
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
