@extends('user.header')

@section('content')
<style>
    .input-error {
    border: 2px solid #ef4444;
}

.error-box {
    background: #fee2e2;
    color: #991b1b;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 14px;
    text-align: center;
}

    .password-container {
        max-width: 480px;
        margin: 80px auto;
        background: #ffffff;
        padding: 40px;
        border-radius: 18px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid #eef1f5;
    }

    .password-title {
        font-size: 28px;
        font-weight: 700;
        text-align: center;
        color: #1f2937;
        margin-bottom: 10px;
    }

    .password-subtitle {
        text-align: center;
        font-size: 16px;
        color: #6b7280;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 22px;
        text-align: left;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-group input {
        width: 100%;
        padding: 14px 16px;
        border-radius: 12px;
        border: 1px solid #d1d5db;
        font-size: 15px;
        transition: 0.2s;
    }

    .form-group input:focus {
        outline: none;
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
    }

    .submit-btn {
        width: 100%;
        padding: 15px;
        background: #22c55e;
        color: #fff;
        font-size: 17px;
        font-weight: 600;
        border-radius: 14px;
        border: none;
        cursor: pointer;
        transition: 0.25s;
    }

    .submit-btn:hover {
        background: #16a34a;
    }

   .security-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 20px;
    font-size: 14px;
    color: #6b7280;
}

.security-icon {
    width: 18px;
    height: 18px;
    color: #22c55e;
}

</style>

<div class="password-container">
    <h3 class="password-title">Créer votre mot de passe</h3>
    <p class="password-subtitle">
        Sécurisez votre compte pour accéder à votre espace personnel.
    </p>

     {{-- Global error --}}
    @if ($errors->any())
        <div class="error-box">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('client.setPassword.store', $client) }}">
        @csrf

        <div class="form-group">
            <label for="password">Nouveau mot de passe</label>
            <input
                id="password"
                type="password"
                name="password"
                placeholder="Minimum 8 caractères"
                required
            >
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmez le mot de passe</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                placeholder="Répétez le mot de passe"
                required
            >
        </div>

        <button type="submit" class="submit-btn">
            Enregistrer et accéder à mon compte
        </button>
    </form>

  <p class="security-note">
    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
         viewBox="0 0 24 24" stroke-width="1.5"
         stroke="currentColor" class="security-icon">
     <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" />
 </svg>
    

    Vos informations sont sécurisées et chiffrées.
</p>

</div>
@endsection
