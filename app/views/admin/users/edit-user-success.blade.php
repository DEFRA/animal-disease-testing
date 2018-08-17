@extends('layouts.master')
@section('title', 'Edit User Successful - User Management -')
@section('content')

<h1 class="heading-large">Edit User Success</h1>

<p>Your changes were successful.</p>

<div>
    {{ link_to_route('users.view', 'Manage users', ['practiceId' => $practice->getId()], []) }}
</div>

@stop