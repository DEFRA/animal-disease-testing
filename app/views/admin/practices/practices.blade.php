@extends('layouts.master')
@section('title', 'Practice Management -')
@section('content')

    <h1 class="heading-large">Practices</h1>
    <div>
        {{ link_to_route('practice.register-form', 'New practice', [], ['class'=>'button']) }}
    </div>
    <table class="practice-table">
        <thead>
            <tr>
                <th>Practice</th>
                <th>Practice code</th>
                <td></td>
            </tr>
        </thead>
        <tbody>
            @foreach($practices as $practice)
            <tr>
                <td>{{{$practice->getName()}}}</td>
                <td>{{{$practice->getLimsCode()}}}</td>
                <td>
                    <?php $firstUser = $practice->getFirstUser()?>
                    @if ($firstUser)
                        {{ link_to_route('practice.edit-form', 'Edit', ['id' => $practice->getId()]) }}
                        {{ link_to_route('users.view', 'Manage users', ['practiceId' => $practice->getId()]) }}
                    @endif
                    @if ($firstUser && !$firstUser->isActivated())
                        {{ Form::open(array('route' => 'practice.resend-email','autocomplete' => 'off'))  }}
                            {{Form::hidden('practice_id', $practice->getId())}}
                            {{Form::submit('Resend Email', ['class'=>'submit-like-link'], ['name'=>'resend_email'])}}
                        {{Form::close()}}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@stop