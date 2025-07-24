@props(['messages', 'class' => ''])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-danger list-unstyled mt-2 ' . $class]) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
