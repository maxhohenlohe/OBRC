@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}


<?php
$invoices = app('Spinen\QuickBooks\Client')->getDataService()->Query("Select * From Invoice ORDER BY DocNumber");

$customers = app('Spinen\QuickBooks\Client')->getDataService()->Query("Select * From Customer");
?>


<table border='1'>
    <thead>
        <tr>
            <th>Invoice #</th>
            <th>Customer Ref</td>
            <th>Customer Full Name</th>
            <th>Email</th>
            <th>address</th>
        </tr>
    </thead>
    <tbody>
@foreach ($invoices as $invoice)
    <tr>
        <td>{{ $invoice->DocNumber }}</td>
        <td>{{ $invoice->CustomerRef }}</td>

@foreach ($customers as $customer)
    @if ($customer->Id == $invoice->CustomerRef)
        <td>{{ $customer->FullyQualifiedName }}</td>

        @if (!empty($customer->PrimaryEmailAddr->Address))
            <td>{{ $customer->PrimaryEmailAddr->Address }}</td>
        @else
            <td></td>
        @endif
    @endif
@endforeach

@if (!empty($invoice->BillAddr->Id))
    <td>{{ $invoice->BillAddr->Line1 }}</td>
    @else
        <td></td>
    @endif
        </tr>
@endforeach
</tbody>
</table>

<p><p>
