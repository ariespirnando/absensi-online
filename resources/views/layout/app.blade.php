<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta19
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Absensi Master</title>
    <!-- CSS files -->

    <link href="{{ asset('dist/css/tabler.min.css?1684106062') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/tabler-flags.min.css?1684106062') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/tabler-payments.min.css?1684106062') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/tabler-vendors.min.css?1684106062') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/demo.min.css?1684106062') }}" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
  </head>
  <body  class=" layout-boxed">
    <script src="{{ asset('dist/js/demo-theme.min.js?1684106062') }}"></script>
    <div class="page">
      <!-- Navbar -->
      @include('shared.header')
      @include('shared.menus')
      <div class="page-wrapper">
        @yield("contents")
        @include('shared.footer')
      </div>
    </div>

    <!-- Libs JS -->
    <script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js?1684106062') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1684106062') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/maps/world.js?1684106062') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/maps/world-merc.js?1684106062') }}" defer></script>
    <script src="{{ asset('dist/libs/nouislider/dist/nouislider.min.js?1684106062') }}" defer></script>
    <!-- Tabler Core -->
    <script src="{{ asset('dist/js/tabler.min.js?1684106062') }}" defer></script>
    <script src="{{ asset('dist/js/demo.min.js?1684106062') }}" defer></script>

  </body>
</html>
