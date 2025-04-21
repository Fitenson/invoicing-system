@component('mail::message')
# Invoice Ready

Hello {{ $data['client_name'] }},
Please find your invoice attached.

Thanks,
{{ config('app.name') }}
@endcomponent
