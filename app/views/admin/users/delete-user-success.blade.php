@extends('layouts.master')
@section('title', 'Delete User Success - User Management -')
@section('content')

<h1 class="heading-large">Success!</h1>

<p>Successfully deleted user.</p>

<p>{{ link_to_route('users.view', 'Back to practice users', ['practiceId' => $practice->getId()]) }}</p>

@stop