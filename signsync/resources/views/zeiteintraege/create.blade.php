@extends('layouts.app')

@section('title', 'Neuer Zeiteintrag - signsync')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Neuer Zeiteintrag
                    </h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert
