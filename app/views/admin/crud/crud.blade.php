@extends('layouts.master')
@section('title', 'Crud Management -')
@section('content')
    <h1 class="heading-large">Lookups Management</h1>

    <table class="practice-table">
        <thead>
            <tr>
                <th>Lookup Table</th>
                <th>Lookup Table Code</th>
                <td></td>
            </tr>
        </thead>
        <tbody>
        @foreach($tables as $tableKey => $tableData)
            <tr>
                <td>{{{ $tableData['description'] }}}</td>
                <td>{{{ $tableData['table_name'] }}}</td>
                <td>
                    {{ link_to_route('crud.edit', 'Edit', $tableData['id'] ) }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop