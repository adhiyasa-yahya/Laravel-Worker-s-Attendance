@extends('base_template.app')

@section('title', 'Monitoring Absensi Pegawai')  
@section('page-content')  
    <div class="jumbotron mt-3 text-center">
        <h1 class="display-4">Hello, world!</h1>
        <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
        <hr class="my-4">
        <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
    </div>
    <p><strong id="update-data"></strong></p>
    <button type="button" class="btn btn-primary" id="start"> Start Real Time Data</button>
    <button type="button" class="btn btn-danger" id="stop"> Pause</button>

    <table class="table table-hover" id="datatable">
        <thead>
            <tr>
                <th scope="col">Nama Pegawai</th>
                <th scope="col">Divisi </th>
                <th scope="col">Status</th>
                <th scope="col">Update</th>
            </tr>
        </thead>
        <tbody> 
        </tbody>
    </table>
@endsection

@section('page-js') 
    <script>
    
        $(document).ready(function() { 
            $('#update-data').html(getDate());
            getDataTabel(); 
            real_time_update();
        });

         
        function getDataTabel() {
            $('#datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "searching": true,
                "bInfo": false,
                "lengthChange": false, 
                "paging" : false,
                "ajax": {url: "{{route('datatable.fetch_absen')}}"},
                "columns": [
                    {'data': 'nama'},
                    {'data': 'divisi'},
                    {'data': 'status'},
                    {'data': 'update'},
                ]
            });

        }

        function getDate() {
            var currentdate = new Date(); 
            var months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
            var hours = currentdate.getHours();
            var minutes = currentdate.getMinutes();
            var ampm = hours >= 12 ? ' pm' : ' am';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0'+minutes : minutes;
            var datetime = "Update: " + currentdate.getDate() + "-"
                            + months[(currentdate.getMonth())]  + "-" 
                            + currentdate.getFullYear() + " @ "  
                            + hours + ":"  
                            + minutes + ":" 
                            + currentdate.getSeconds()
                            + ampm;
            return datetime;
        } 

        var timer = null,  interval = 1000;
        $("#start").click(function() {
            $("#update-data").removeClass("blink_text");
            $('#update-data').html(getDate());
            real_time_update();
            getDataTabel(); 
        });

        $("#stop").click(function() {
            $("#update-data").addClass("blink_text");
            clearInterval(timer);
            timer = null
        });

        function real_time_update() {
            timer = setInterval(function(){
                $('#update-data').html(getDate());
                getDataTabel();
            }, 5000);
        }
         
        
    </script>
@endsection
