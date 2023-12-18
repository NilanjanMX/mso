@if(isset($watermark) && $watermark == 1)
    <div class="watermark">
        {{env('WATERMARK_TEXT')}}
    </div>
@endif
@php
$amf='AMFI-Registered Mutual Fund Distributor';
@endphp
<main style="width: 760px; margin-left: 20px;">
<footer style="height: 70px;">
    <p style="margin-left:10%;text-align: center;">
        {!! ($name!='')?$name.'<br>':'' !!}
        {!! ($company_name!='')?$company_name.'<br>':'' !!}
        @php if(isset($amfi_registered)){ @endphp
        {!! ($amfi_registered!='' || $amfi_registered!='0')?$amf.'<br>':'' !!}
        @php } @endphp
        {!! ($email!='')?'Email: '.$email.', ':'' !!}
        @if($website!=''){!! ($phone_no!='')?'M: '.$phone_no.', ':'' !!}@else {!! ($phone_no!='')?'M: '.$phone_no:'' !!} @endif
        {!! ($website!='')?'Website: '.$website:'' !!}
    </p>
</footer>
</main>