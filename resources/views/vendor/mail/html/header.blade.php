<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<div style="padding-bottom: 10px;"><img src="{{ asset('assets/img/logo.png') }}" height="70" alt="InvoiceNeko"/></div>
{{ $slot }}
@endif
</a>
</td>
</tr>
