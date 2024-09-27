@if (session('success'))
    @push('alert')
        <script>
            Toast.fire({
                icon: 'success',
                title: ' {{ session('success') }}'
            });
        </script>
    @endpush
@endif
