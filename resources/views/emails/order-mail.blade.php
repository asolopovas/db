@php
    $fullname = implode(
        ' ',
        array_filter([
            $order['customer']->title,
            $order['customer']->firstname,
            $order['customer']->lastname,
        ]),
    );
@endphp

<x-mail::message>
    Dear {{ $fullname }},

    {!! $message !!}
</x-mail::message>
