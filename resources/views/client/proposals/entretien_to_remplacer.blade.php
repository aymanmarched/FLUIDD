@extends('client.menu')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-6">

    @include('client.proposals.partials.proposal_header', ['proposal' => $proposal])

    @include('client.proposals.partials.machines_details', ['mission' => $mission])

    @include('client.proposals.partials.proposal_actions', ['proposal' => $proposal])

</div>
@endsection
