@extends('layouts.master')
@section('title', 'VICTOR Dashboard -')
@section('content')

    <h1 class="heading-large">Welcome, {{{ $loggedUser->getFullname() }}}</h1>
    
    <ul class="quick-links">
            @if($loggedUser->canManagePracticeAccounts())
                <li class="quick-links__item">{{link_to_route('practices.view', 'Manage practices', null, array('id'=>'manage-practices-route', 'class' => 'quick-links__link'))}}</li>
            @endif
            @if($loggedUser->canManageVictorAccounts())
                <li class="quick-links__item">{{link_to_route('users.view', 'Manage victor administrators', ['practiceId' => $loggedUser->getPractice()->id], array('id'=>'manage-admins-route', 'class' => 'quick-links__link'))}}</li>
            @endif
            @if($loggedUser->canManageVictorAccounts())
                <li class="quick-links__item">{{link_to_route('settings.edit', 'Manage victor settings', null, array('id'=>'settings-route', 'class' => 'quick-links__link'))}}</li>
            @endif
            @if($loggedUser->canManageLookupTables())
                <li class="quick-links__item">{{link_to_route('crud.crud', 'Manage lookup tables', null, array('id'=>'lookups-route', 'class' => 'quick-links__link'))}}</li>
            @endif
            @if($loggedUser->canManageInformationMessages())
                <li class="quick-links__item">{{link_to_route('information-messages.edit', 'Manage information messages', null, array('id'=>'manage-information-message-route', 'class' => 'quick-links__link'))}}</li>
            @endif
            @if($loggedUser->canManageLookupTables())
                <li class="quick-links__item">{{link_to_route('lookups.test-advice', 'Upload test advice', null, array('id'=>'test-advice-route', 'class' => 'quick-links__link'))}}</li>
            @endif            
            @if($loggedUser->canViewLogs())
                <li class="quick-links__item">{{link_to_route('logs.view', 'Logs', null, array('id'=>'logs-route', 'class' => 'quick-links__link'))}}</li>
            @endif
        </ul>
@stop