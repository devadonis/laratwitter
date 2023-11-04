@extends('layouts.app')

@section('content')
  <task-draggable
    :tasks="{{ $tasks }}"
  ></task-draggable>
@stop