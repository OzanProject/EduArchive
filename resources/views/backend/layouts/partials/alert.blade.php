@if (session('success'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: '{!! addslashes(session("success")) !!}',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });
  });
</script>
@endif

@if (session('error'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
      icon: 'error',
      title: 'Oops! Terjadi Kesalahan',
      text: '{!! addslashes(session("error")) !!}',
      confirmButtonColor: '#3b82f6',
      confirmButtonText: 'Mengerti',
      customClass: {
        confirmButton: 'btn btn-primary px-4 py-2'
      },
      buttonsStyling: false
    });
  });
</script>
@endif

@if (session('warning'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'warning',
      title: '{!! addslashes(session("warning")) !!}',
      showConfirmButton: false,
      timer: 4000,
      timerProgressBar: true
    });
  });
</script>
@endif

@if (session('info'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'info',
      title: '{!! addslashes(session("info")) !!}',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });
  });
</script>
@endif

@if ($errors->any())
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let errorList = '<ul class="text-left" style="list-style-type: disc; padding-left: 20px;">';
    @foreach ($errors->all() as $error)
      errorList += '<li class="mb-1">{!! addslashes($error) !!}</li>';
    @endforeach
    errorList += '</ul>';

    Swal.fire({
      icon: 'error',
      title: 'Validasi Gagal!',
      html: errorList,
      confirmButtonColor: '#3b82f6',
      confirmButtonText: 'Perbaiki',
      customClass: {
        confirmButton: 'btn btn-primary px-4 py-2'
      },
      buttonsStyling: false
    });
  });
</script>
@endif