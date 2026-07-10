@if (session('success'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: '{{ session("success") }}',
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
      toast: true,
      position: 'top-end',
      icon: 'error',
      title: '{{ session("error") }}',
      showConfirmButton: false,
      timer: 5000,
      timerProgressBar: true
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
      title: '{{ session("warning") }}',
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
      title: '{{ session("info") }}',
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
    let errorList = '<ul>';
    @foreach ($errors->all() as $error)
      errorList += '<li class="text-left">{{ $error }}</li>';
    @endforeach
    errorList += '</ul>';

    Swal.fire({
      icon: 'error',
      title: 'Terdapat Kesalahan!',
      html: errorList,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Tutup'
    });
  });
</script>
@endif