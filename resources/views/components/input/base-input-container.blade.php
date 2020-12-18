@props([
'label' => 'simple-select',
'id' => 'simple-select',
'inline' => false,
'labelPosition' => 'center',
'labelClasses',
'options'
])

<div class="{{$inline ? 'flex flex-wrap items-'.$labelPosition : ''}}">

    @if($label)
        <label for="{{$id}}"
               class="{{$inline ? $options['label']['inline-style'] : $options['label']['block-display']}} {{$labelClasses}}">{{$label}}</label>
    @endif

    <div class="{{$inline ? 'w-4/6' : 'block w-full'}} relative">

        {{$slot}}

    </div>

</div>
