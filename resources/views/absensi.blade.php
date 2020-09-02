
        
@extends('base_template.app')

@section('title', 'Absensi Pegawai')  
@section('page-content')  
    <div class="jumbotron mt-3 text-center">
        <h1 class="display-4">Hello, world!</h1>
        <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
        <hr class="my-4">
        <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
    </div>


    <div class="form-group">
        <label for="formGroupExampleInput">Type Your NIK <span class="text-danger">*</span></label>
        <input type="text" class="form-control form-control-lg data-nik" id="formGroupExampleInput" placeholder="Example: 5561568789715468789">
    </div> 
    <div class="row">
        <div class="col">
            <button type="button" class="btn btn-primary btn-lg btn-block mt-2 datang">Absen Datang</button>

        </div>
        <div class="col">
            <button type="button" class="btn btn-secondary btn-lg btn-block mt-2 pulang">Absen Pulang</button>

        </div>
    </div>
@endsection

@section('page-js') 
    <script>
        $(document).ready(function(){
            $('.datang').attr('disabled',true);
            $('.pulang').attr('disabled',true);
        });

        $('.data-nik').keyup(function(e){
                if($(this).val().length > 5){
                    var nik = $(this).val();
                    $.ajax({
                        type: "get",
                        url: "{{ url('employe/find') }}/"+nik, 
                        dataType: 'json',
                        cache: false,
                        beforeSend: function() {},
                        success: function (data) {
                            if (data == '') {
                                setTimeout(function(){ 
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'NIK yang anda masukan salah, silakan coba lagi :)', 
                                    }).then((result) => {
                                            $('.datang').attr('disabled',true);
                                    });

                                }, 1000);
                             
                            } else {
                                check_present(nik,data)
                            } 

                        }}) 
                } else{
                    $('.datang').attr('disabled',true);
                    $('.pulang').attr('disabled',true);
                }


            })

        function check_present(nik,data) {
            $.ajax({
                type: "get",
                url: "{{ url('presen/find') }}/"+nik, 
                dataType: 'json',
                cache: false,
                success: function (data) {
                    if (data == '') { 
                        $('.datang').attr('disabled',false);
                        $( ".datang" ).unbind('click').click(function(e,data) {
                            absen(nik);
                        });

                    } else {
                        $('.pulang').attr('disabled',false);
                        $( ".pulang" ).unbind('click').click(function(e,data) {
                            pulang(nik);
                        });
                        
                    }
                }
            })
        }

        function absen(nik) {
            $.ajax({
                type: "get",
                url: "{{ url('absen/datang') }}/"+nik, 
                dataType: 'json',
                cache: false,
                success: function (data) {
                    console.log(data);
                    var name = data.employee.name;
                    var names = name.split(' ');

                    if (names.length > 1) {
                        name = names[0];
                    }else{
                        name = names;
                    }

                    Swal.fire({
                        icpn: 'success',
                        type: 'success',
                        title: 'Terimakasih ' + name,
                        text: 'Semoga hari ini kau bahagia. Selamat bekarja! :)' ,
                        timer: 3000,
                        footer: '<p class="text-center"> Jika terjadi error atau kendala dalam penggunaan sistem, harap hubungi admin di dalam oke </p>'
                    }).then((result) => {
                        location.reload();
                    });
 
                }
            })
        }

        function pulang(nik) {
            $.ajax({
                type: "get",
                url: "{{ url('absen/pulang') }}/"+nik, 
                dataType: 'json',
                cache: false,
                success: function (data) {
                    console.log(data);
                   if (data.message == 'sukses') {
                        var name = data.employee.name;
                        var names = name.split(' ');

                        if (names.length > 1) {
                            name = names[0];
                        }else{
                            name = names;
                        }

                        Swal.fire({
                            type: 'success',
                            title: 'Terimakasih ' + name,
                            text: 'Semoga hari ini kau bahagia. Hati - hati di jalan! :)' ,
                            timer: 3000,
                            footer: '<p class="text-center"> Jika terjadi error atau kendala dalam penggunaan sistem, harap hubungi admin di dalam oke </p>'
                        }).then((result) => {
                            location.reload();
                        });
                   };

                    if (data.message == 'notNull') {
                        Swal.fire({
                            type: 'question',
                            title: 'Oops...',
                            text: 'Bukannya tadi sudah absen pulang?!',
                            footer: '<p class="text-center"> Jika terjadi error atau kendala dalam penggunaan sistem, harap hubungi admin di dalam oke </p>'
                            
                        })
                   }

                  
                }
            })
        }

        
    </script>
@endsection

</body>
</html>