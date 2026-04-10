@extends('technicien.menu')

@section('content')
@php
  $title = "Edit Step {$stepNo}";
  $hasMedia = $step && $step->media_path;
  $mediaUrl = $hasMedia ? asset('storage/'.$step->media_path) : null;
@endphp

<div class="max-w-3xl mx-auto space-y-6">

  <div class="flex items-center justify-between">
    <div>
      <div class="text-sm text-zinc-500 font-semibold">Mission</div>
      <div class="text-2xl font-extrabold text-zinc-900">{{ $mission->reference }}</div>
      <div class="text-sm text-zinc-600 font-semibold">{{ $title }}</div>
    </div>

    {{-- رجّع لصفحة التفاصيل --}}
    <a href="{{ route('technicien.missions.details', $mission) }}"
       class="px-4 py-2 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-50 font-extrabold">
      ← Retour
    </a>
  </div>

  @if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 text-sm">
      <div class="font-extrabold mb-2">Veuillez corriger :</div>
      <ul class="list-disc pl-5 space-y-1">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if (session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl p-4 text-sm font-semibold">
      {{ session('success') }}
    </div>
  @endif

  {{-- ✅ DELETE MEDIA = FORM بوحدو (ماشي داخل فورم update) --}}
  @if($hasMedia)
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6 space-y-3">
      <div class="font-extrabold text-zinc-900">Média actuel</div>

      @if(($step->media_type ?? '') === 'video')
        <video class="w-full rounded-2xl border border-zinc-200" controls playsinline>
          <source src="{{ $mediaUrl }}">
        </video>
      @else
        <img class="w-full rounded-2xl border border-zinc-200" src="{{ $mediaUrl }}" alt="media">
      @endif

      <form method="POST" action="{{ route('technicien.missions.steps.media.delete', [$mission, $stepNo]) }}">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="w-full px-4 py-3 rounded-2xl border border-red-200 bg-red-50 text-red-800 font-extrabold hover:bg-red-100">
          Supprimer le média
        </button>
      </form>
    </div>
  @endif

  {{-- ✅ UPDATE STEP = FORM بوحدو --}}
  <form method="POST"
        action="{{ route('technicien.missions.steps.update', [$mission, $stepNo]) }}"
        enctype="multipart/form-data"
        class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6 space-y-5">
    @csrf

    <div>
      <label class="font-extrabold text-zinc-900">Commentaire</label>
      <textarea name="comment"
                class="mt-2 w-full border border-zinc-200 rounded-2xl p-3"
                rows="4"
                placeholder="Commentaire...">{{ old('comment', $step->comment ?? '') }}</textarea>
    </div>

    <div class="space-y-3">
      <div class="font-extrabold text-zinc-900">Remplacer / Ajouter un média</div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="border border-zinc-200 rounded-2xl p-3 bg-white">
          <div class="text-sm font-extrabold text-zinc-900 mb-2">Photo</div>
          <input type="file" name="media_photo" accept="image/*" class="w-full">
        </div>

        <div class="border border-zinc-200 rounded-2xl p-3 bg-white">
          <div class="text-sm font-extrabold text-zinc-900 mb-2">Vidéo</div>
          <input type="file" name="media_video" accept="video/*" class="w-full">
        </div>

        <div class="border border-zinc-200 rounded-2xl p-3 bg-white">
          <div class="text-sm font-extrabold text-zinc-900 mb-2">Fichier</div>
          <input type="file" name="media_file" accept="image/*,video/*" class="w-full">
        </div>
      </div>

      <div class="text-xs text-zinc-500 font-semibold">
        Si vous chargez un nouveau média, l’ancien sera supprimé.
      </div>
    </div>

    <button type="submit"
            class="w-full px-5 py-4 bg-orange-600 hover:bg-orange-700 text-white rounded-2xl font-extrabold transition">
      Enregistrer
    </button>
  </form>

</div>
@endsection