@props(['url'])
@php
    $logoUrl = asset('assets/backend/dist/img/logo.png'); // Fallback
    if (isset($central_branding['app_logo']) && !empty($central_branding['app_logo'])) {
        $logoUrl = url($central_branding['app_logo']);
    }
@endphp
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ $logoUrl }}" class="logo" alt="EduArchive Logo" style="max-height: 50px; width: auto; max-width: 100%;">
</a>
</td>
</tr>
