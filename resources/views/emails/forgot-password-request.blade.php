@component('mail::message')
# Password Restoration Request

A user has requested their password to be sent or reset. Here are the details of the request:

**Account Email:** {{ $emailAddress }}
**Reason for Request:** {{ $reason }}

### Detailed Description:
{{ $description }}

Please reply to this user or send them a new/old password as requested.

Thanks,<br>
{{ config('app.name') }} Support
@endcomponent
