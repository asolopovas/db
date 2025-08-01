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
@component('mail::message')
    Dear {{ $fullname }},

    {!! nl2br(e($message)) !!}

    @include('partials.signature')
@endcomponent
