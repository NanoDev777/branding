{{-- Polyfill some features via polyfill.io --}}
@php
$polyfills = [
    'Promise',
    'Object.assign',
    'Object.values',
    'Array.prototype.find',
    'Array.prototype.findIndex',
    'Array.prototype.includes',
    'String.prototype.includes',
    'String.prototype.startsWith',
    'String.prototype.endsWith',
];
@endphp
<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features={{ implode(',', $polyfills) }}"></script>

{{-- Load the application scripts --}}
@if (app()->isLocal())
  {{--{!! Html::script('js/app.js') !!}--}}
  <script src="{{ mix('js/app.js') }}"></script>
@else
  {{--{!! Html::script('js/manifest.js') !!}--}}
  <script src="{{ mix('js/manifest.js') }}"></script>
  {{--{!! Html::script('js/vendor.js') !!}--}}
  <script src="{{ mix('js/vendor.js') }}"></script>
  {{--{!! Html::script('js/app.js') !!}--}}
  <script src="{{ mix('js/app.js') }}"></script>
@endif
