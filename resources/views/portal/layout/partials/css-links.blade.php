<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

<!-- Font Awesome -->
<link rel="stylesheet" href="{{url('/portal')}}/plugins/fontawesome-free/css/all.min.css">

<!-- Theme style -->
<link rel="stylesheet" href="{{url('/portal')}}/dist/css/adminlte.min.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />


<link rel="stylesheet" href="{{url('/portal')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{url('/portal')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="{{url('/portal')}}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<!-- Toastr -->
<link rel="stylesheet" href="{{url('/portal')}}/plugins/toastr/toastr.min.css">

<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{url('/portal')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

<!-- Select2 -->
<link rel="stylesheet" href="{{url('/portal')}}/plugins/select2/css/select2.min.css">

<!-- Daterange -->
<link rel="stylesheet" href="{{url('/portal')}}/plugins/daterangepicker/daterangepicker.css">

<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<!-- Bootstrap Slider -->
<link rel="stylesheet" href="{{url('/portal')}}/plugins/bootstrap-slider/css/bootstrap-slider.min.css">


<!--mycss urdu--->
<link rel="stylesheet" href="{{url('/portal')}}/plugins/urdu/style.css">



  <!---andmycss js-->

<style type="text/css">
     body {
            font-family: 'Noto Nastaliq Urdu', 'Montserrat', sans-serif;
            /* direction: rtl; */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .center-table th {
    text-align: center;
    vertical-align: middle;
}

        table, th, td {
            border: 1px solid black;
          
        }
        .center-middle {
    text-align: center;
    vertical-align: middle;
}

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
        
    form label.required:after
    {
        color: red;
        content: " *";
    }

    /* .nowrap
    {
        white-space: nowrap;
    }
    .container {
        position: relative;
        display: inline-block;
        height:100px;
        width:100px;
    }

    .close-button {
        position: absolute;
        top: -10px;
        right: -20px;
        z-index:999;
        cursor: pointer;
    } */

    /*  total duration css after filter  */
    .clock-day:before {
        /* content: var(--timer-day); */
        content: "25";
    }

    .clock-hours:before {
        content: var(--timer-hours);
        /* content: "18"; */
    }

    .clock-minutes:before {
        content: var(--timer-minutes);
        /* content: "34"; */
    }

    .clock-seconds:before {
        content: var(--timer-seconds);
        /* content: "17"; */
    }

    .clock-container {
        /* margin-top: 30px; */
        margin-bottom: 30px;
        /* background-color: #080808; */
        background-color: #343a40;
        border-radius: 5px;
        /* padding: 60px 20px; */
        /*box-shadow: 1px 1px 5px rgba(255, 255, 255, 0.15), 0 15px 90px 30px rgba(0, 0, 0, 0.25); */
        display: flex;
    }

    .clock-col {
        text-align: center;
        margin-right: 40px;
        margin-left: 40px;
        min-width: 90px;
        position: relative;
    }

    .clock-col:not(:last-child):before,
    .clock-col:not(:last-child):after {
        content: "";
        background-color: rgba(255, 255, 255, 0.3);
        height: 5px;
        width: 5px;
        border-radius: 50%;
        display: block;
        position: absolute;
        right: -42px;
    }

    .clock-col:not(:last-child):before {
        top: 35%;
    }

    .clock-col:not(:last-child):after {
        top: 50%;
    }

    .clock-timer:before {
        color: #fff;
        font-size: 3.2rem;
        text-transform: uppercase;
    }

    .clock-label {
        color: rgba(255, 255, 255, 0.35);
        text-transform: uppercase;
        font-size: 1rem;
        margin-top: 10px;
    }

    @media (max-width: 500px) {
        .clock-container {
            flex-direction: column;
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .clock-col+.clock-col {
            margin-top: 20px;
        }

        .clock-col:before,
        .clock-col:after {
            display: none !important;
        }
    }
    .timeline>div>.counter {
    background-color: #adb5bd;
    border-radius: 50%;
    font-size: 16px;
    font-weight: 700;
    height: 30px;
    left: 18px;
    line-height: 30px;
    position: absolute;
    text-align: center;
    top: 0;
    width: 30px;
}
</style>


