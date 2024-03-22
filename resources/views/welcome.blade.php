<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

    	<title>Fenceline</title>
		<link href="{{url('/front/dist')}}/assets/libs/slick-carousel/slick/slick.css" rel="stylesheet" />
      <link href="{{url('/front/dist')}}/assets/libs/slick-carousel/slick/slick-theme.css" rel="stylesheet" />
      <link href="{{url('/front/dist')}}/assets/libs/tiny-slider/dist/tiny-slider.css" rel="stylesheet">
      <link href="{{url('/front/dist')}}/assets/libs/nouislider/dist/nouislider.min.css" rel="stylesheet">
        <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta content="Codescandy" name="author">
      
      <!-- Google tag (gtag.js) -->
      {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-M8S4MT3EYG"></script> --}}
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
      
        gtag('config', 'G-M8S4MT3EYG');
      </script>
      
      <!-- Favicon icon-->
      <link rel="shortcut icon" type="image/x-icon" href="{{url('/')}}/favicon.png">
      
      
      <!-- Libs CSS -->
      <link href="{{url('/front/dist')}}/assets/libs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
      <link href="{{url('/front/dist')}}/assets/libs/feather-webfont/dist/feather-icons.css" rel="stylesheet">
      <link href="{{url('/front/dist')}}/assets/libs/simplebar/dist/simplebar.min.css" rel="stylesheet">
      


      <style type="text/css">
      .dropdown-menu {
  padding: 8px;
}
        .loading-spinner-overlay {
          position: fixed;
          top: 0;
          left: 0;
          height: 100%;
          width: 100%;
          display: flex;
          justify-content: center;
          align-items: center;
          background-color: rgba(0, 0, 0, 0.5); /* 50% transparent black */
          z-index: 9999; /* Make sure the overlay is on top of other elements */
          

  
        }
        ul.suggestions {
          list-style-type: none; /* Remove bullet points */
          margin: 0;
          padding: 0;
          position: absolute;
          background-color: #fff; /* Set background color */
          border: 1px solid #ddd; /* Add a border */
          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add a box shadow */
          width: 100%;
          z-index: 1; /* Ensure it appears above other elements */
}

ul.suggestionstwo {
          list-style-type: none; /* Remove bullet points */
          margin: 0;
          padding: 0;
          position: absolute;
          background-color: #fff; /* Set background color */
          border: 1px solid #ddd; /* Add a border */
          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add a box shadow */
          width: 100%;
          z-index: 1; /* Ensure it appears above other elements */
}
ul.suggestions li {
  padding: 10px; /* Add some padding */
  color: black;
}


.carousel-item {
    transition: transform 0.5s ease-in-out;
    will-change: transform;
}
.carousel {
   height: 490px; 
  overflow: hidden;
}

.dropdown-menu a.dropdown-item:hover {
  color: #0aad0a !important;
}
ul.suggestions li:hover {
  background-color: #f1f1f1; /* Add hover background color */
}

.custom-active{
  color: #088a08 !important;
  font-weight: bold !important;
}


        
        
      </style>
      <!-- Theme CSS -->
      <link rel="stylesheet" href="{{url('/front/dist')}}/assets/css/theme.min.css">
      
      
      </head>
    <body class="antialiased">
        <div id='app'></div>
        <script src="{{ asset('/js/app.js') }}"></script>


        <!-- Javascript-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="{{url('/front/dist')}}/assets/libs/nouislider/dist/nouislider.min.js"></script>
  <script src="{{url('/front/dist')}}/assets/libs/wnumb/wNumb.min.js"></script>
  <script src="{{url('/front/dist')}}/assets/libs/rater-js/index.js"></script>
<script src="{{url('/front/dist')}}/assets/libs/dropzone/dist/min/dropzone.min.js"></script>
        
  <!-- Libs JS -->
<script src="{{url('/front/dist')}}/assets/libs/jquery/dist/jquery.min.js"></script>
<script src="{{url('/front/dist')}}/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{url('/front/dist')}}/assets/libs/simplebar/dist/simplebar.min.js"></script>

<!-- Theme JS -->
<script src="{{url('/front/dist')}}/assets/js/theme.min.js"></script>
  <script src="{{url('/front/dist')}}/assets/libs/jquery-countdown/dist/jquery.countdown.min.js"></script>
  <script src="{{url('/front/dist')}}/assets/js/vendors/countdown.js"></script>
  <script src="{{url('/front/dist')}}/assets/libs/slick-carousel/slick/slick.min.js"></script>
  <script src="{{url('/front/dist')}}/assets/js/vendors/slick-slider.js"></script>
  <script src="{{url('/front/dist')}}/assets/libs/tiny-slider/dist/min/tiny-slider.js"></script>
  <script src="{{url('/front/dist')}}/assets/js/vendors/tns-slider.js"></script>
  <script src="{{url('/front/dist')}}/assets/js/vendors/zoom.js"></script>
<script src="{{url('/front/dist')}}/assets/js/vendors/increment-value.js"></script>





    </body>

    
</html>
