@extends('errors::minimal')
@section('code', '503')
@section('message', __($exception->getMessage() ?: 'Service Unavailable'))
