@props(['logo'])
<x-mail::layout>
    {{-- Header --}}
    <x-slot:header>
        <x-mail::header url="https://woodlandflooring.co.uk">
            <img
                src="https://{{config('app.url')}}/img/logo.png"
                alt="Logo"
                style="max-width: 320px;"
            >
        </x-mail::header>
    </x-slot:header>

    {{-- Body --}}
    <x-slot:message>
        </x-slot::message>

        {{-- Subcopy --}}
        @isset($subcopy)
            <x-slot:subcopy>
                <x-mail::subcopy>
                    {{ $subcopy }}
                </x-mail::subcopy>
            </x-slot:subcopy>
        @endisset

        {{-- Footer --}}
        <x-slot:footer>
            <x-mail::footer>
                © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
            </x-mail::footer>
        </x-slot:footer>
</x-mail::layout>
