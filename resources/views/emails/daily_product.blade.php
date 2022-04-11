@component('mail::message')
{{$title}}

{{$product}}<br>
{{$description}}

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
