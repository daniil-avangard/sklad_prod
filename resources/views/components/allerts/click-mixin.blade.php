@props(['type', 'title'])

@once
    @push('styles-plugins')
        <link href="/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
    @endpush

    @push('scripts-plugins')
        <script src="/plugins/sweet-alert2/sweetalert2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#alerts_click').click(function() {
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        onOpen: function(toast) {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: '{{ $title }}'
                    });
//                    Toast.fire({
//                        icon: 'error',
//                        title: '{{ $title }}'
//                    });
                });
            });
        </script>
    @endpush

@endonce
