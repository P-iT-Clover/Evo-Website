<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (\Session::has('success'))
    <script>
        Swal.fire(
            'Success', '{!! \Session::get('success') !!}', 'success'
        )
    </script>
@endif

@if (\Session::has('info'))
    <script>
        Swal.fire(
            'Information', '{!! \Session::get('info') !!}', 'info'
        )
    </script>
@endif

@if (\Session::has('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{!! \Session::get('error') !!}'
        })
    </script>
@endif
