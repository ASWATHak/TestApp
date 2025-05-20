@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow p-4" style="width: 350px;">
        <h3 class="text-center mb-4">Login</h3>
        <form id="loginForm">
            @csrf
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div id="errorMessage" class="text-danger mt-3" style="display: none;"></div>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        const response = await fetch('{{ route('login') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email: formData.get('email'),
                password: formData.get('password'),
            }),
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = '{{ route('dashboard') }}';
        } else {
            document.getElementById('errorMessage').textContent = data.message;
            document.getElementById('errorMessage').style.display = 'block';
        }
    });
</script>
@endsection