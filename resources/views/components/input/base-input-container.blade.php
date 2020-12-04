@props([
'label' => 'simple-select',
'id' => 'simple-select',
'inline' => false,
'labelPosition' => 'center'
])

<div class="{{$inline ? 'flex flex-wrap items-'.$labelPosition : ''}}">

    @if($label)
        <label for="{{$id}}"
               class="{{$inline ? 'w-2/6' : 'block'}} text-sm leading-5 font-medium text-gray-700 break-words">{{$label}}</label>
    @endif

    <div class="{{$inline ? 'w-4/6' : 'block w-full'}} relative">

        {{$slot}}

    </div>

</div>
