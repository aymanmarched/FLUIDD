@extends('client.menu')

@section('content')

@php
$fullName = trim(($client->prenom ?? '').' '.($client->nom ?? '')) ?: (auth()->user()->name ?? 'Client');
$phone = $client->telephone ?? auth()->user()->phone ?? '-';
@endphp

<div x-data="{ add:false, editId:null }" class="max-w-5xl mx-auto space-y-8">

{{-- HEADER --}}
<div class="bg-white border border-zinc-200 rounded-3xl shadow-sm p-8">

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

<div>
<h1 class="text-3xl font-extrabold text-zinc-900">Mes avis</h1>
<p class="text-zinc-500 mt-1">
Partagez votre expérience avec nos services.
</p>
</div>

<button
@click="add=true; editId=null"
class="inline-flex items-center gap-2 px-6 py-3 bg-sky-600 text-white font-semibold rounded-2xl shadow hover:bg-sky-700 transition">

➕ Ajouter un avis

</button>

</div>

</div>



{{-- REVIEWS LIST --}}
<div class="space-y-6">

@foreach($avisList as $avis)

<div class="bg-white border border-zinc-200 rounded-3xl shadow-sm p-7 hover:shadow-md transition">

<div class="flex items-start justify-between gap-6">

<div class="flex gap-4">

{{-- Avatar --}}
<div class="w-12 h-12 flex items-center justify-center rounded-full bg-sky-100 text-sky-700 font-bold">
{{ strtoupper(substr($fullName,0,1)) }}
</div>

<div>

<div class="flex items-center gap-3">

<div class="flex text-xl">

@for($i=1;$i<=5;$i++)
<span class="{{ $i <= $avis->stars ? 'text-yellow-400' : 'text-zinc-300' }}">★</span>
@endfor

</div>

<span class="text-xs text-zinc-500">
{{ $avis->updated_at->diffForHumans() }}
</span>

</div>

<p class="mt-2 text-zinc-700 leading-relaxed max-w-xl">
{{ $avis->message }}
</p>

</div>

</div>


<div class="flex gap-2">

<button
@click="editId={{ $avis->id }}; add=false"
class="px-4 py-2 text-sm bg-sky-50 text-sky-700 rounded-xl font-semibold hover:bg-sky-100">

Modifier

</button>

<form method="POST" action="{{ route('client.avis.destroy',$avis->id) }}">
@csrf
@method('DELETE')

<button class="px-4 py-2 text-sm bg-rose-50 text-rose-700 rounded-xl font-semibold hover:bg-rose-100">

Supprimer

</button>

</form>

</div>

</div>



{{-- EDIT FORM --}}
<div
x-show="editId === {{ $avis->id }}"
x-transition
class="mt-6 pt-6 border-t border-zinc-200">

<form method="POST" action="{{ route('client.avis.update',$avis->id) }}" class="space-y-5">

@csrf
@method('PUT')

<input type="hidden" name="stars" id="starsInput{{ $avis->id }}" value="{{ $avis->stars }}">

<label class="text-sm font-semibold text-zinc-700">Votre note</label>

<div class="flex gap-2 text-3xl cursor-pointer">

@for($i=1;$i<=5;$i++)

<span
onclick="setStars({{ $avis->id }},{{ $i }})"
class="star{{ $avis->id }} {{ $i <= $avis->stars ? 'text-yellow-400':'text-zinc-300' }} hover:scale-110 transition">

★

</span>

@endfor

</div>

<textarea
name="message"
rows="3"
class="w-full border border-zinc-200 rounded-2xl p-4 focus:ring-2 focus:ring-sky-200 focus:border-sky-400">

{{ $avis->message }}

</textarea>


<div class="flex gap-3">

<button class="px-6 py-2 bg-sky-600 text-white rounded-xl font-semibold hover:bg-sky-700">

Enregistrer

</button>

<button
type="button"
@click="editId=null"
class="px-6 py-2 border border-zinc-200 rounded-xl font-semibold hover:bg-zinc-50">

Annuler

</button>

</div>

</form>

</div>

</div>

@endforeach

</div>



{{-- ADD FORM --}}
<div
x-show="add"
x-transition
class="bg-white border border-zinc-200 rounded-3xl shadow-sm p-7">

<h3 class="text-xl font-bold mb-4">Ajouter un avis</h3>

<form method="POST" action="{{ route('client.avis.store') }}" class="space-y-5">

@csrf

<input type="hidden" name="stars" id="starsInputNew">

<label class="text-sm font-semibold text-zinc-700">Votre note</label>

<div class="flex gap-2 text-3xl cursor-pointer">

@for($i=1;$i<=5;$i++)

<span
onclick="setStarsNew({{ $i }})"
class="starNew text-zinc-300 hover:scale-110 transition">

★

</span>

@endfor

</div>


<textarea
name="message"
rows="4"
class="w-full border border-zinc-200 rounded-2xl p-4 focus:ring-2 focus:ring-sky-200 focus:border-sky-400"
placeholder="Partagez votre expérience..."></textarea>


<div class="flex gap-3 pt-2">

<button class="px-6 py-3 bg-sky-600 text-white rounded-xl font-semibold hover:bg-sky-700">

Publier

</button>

<button
type="button"
@click="add=false"
class="px-6 py-3 border border-zinc-200 rounded-xl font-semibold hover:bg-zinc-50">

Annuler

</button>

</div>

</form>

</div>

</div>



<script>

function setStars(id,val){

let stars=document.querySelectorAll('.star'+id)
document.getElementById('starsInput'+id).value=val

stars.forEach((s,i)=>{
s.classList.toggle('text-yellow-400',i<val)
s.classList.toggle('text-zinc-300',i>=val)
})

}

function setStarsNew(val){

let stars=document.querySelectorAll('.starNew')
document.getElementById('starsInputNew').value=val

stars.forEach((s,i)=>{
s.classList.toggle('text-yellow-400',i<val)
s.classList.toggle('text-zinc-300',i>=val)
})

}

</script>

@endsection