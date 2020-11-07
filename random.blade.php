@extends('layouts.app')

@section('content')
<div class="container">
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

                    <table class="table table-striped data-table 0DNISVd9 dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                        <colgroup>
                            <col class="con0">
                            <col class="con1">
                            <col class="con2">
                            <col class="con3">
                            <col class="con4">
                            <col class="con5">
                            <col class="con6">
                            <col class="con7">
                            <col class="con8">
                        </colgroup>
                        <thead>
                        <tr role="row"><th align="center" valign="middle" class="head0 sorting_disabled" style="width:20px" rowspan="1" colspan="1" aria-label="">

                            </th><th align="center" valign="middle" class="head1 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Invoice Number : activate to sort column ascending">
                                Invoice Number
                            </th><th align="center" valign="middle" class="head2 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Client Name : activate to sort column ascending">
                                Client Name
                            </th><th align="center" valign="middle" class="head3 sorting_desc" tabindex="0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Date : activate to sort column ascending">
                                Date
                            </th><th align="center" valign="middle" class="head4 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Amount : activate to sort column ascending">
                                Amount
                            </th><th align="center" valign="middle" class="head5 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Balance : activate to sort column ascending">
                                Balance
                            </th><th align="center" valign="middle" class="head6 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Due Date : activate to sort column ascending">
                                Due Date
                            </th><th align="center" valign="middle" class="head7 sorting" tabindex="0" rowspan="1" colspan="1" aria-label="Status : activate to sort column ascending">
                                Status
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($invoices as $invoice)

                        <tr role="row" class="odd"><td><input type="checkbox" name="ids[]" value="269"></td>
                            <td><a href="https://invoice.vmrllc.com/invoices/269/edit" class="">{{ $invoice->DocNumber }}</a></td>
                            @foreach ($customers as $customer)
                                @if ($customer->Id == $invoice->CustomerRef)
                                    <td><a href="https://invoice.vmrllc.com/clients/1">{{ $customer->FullyQualifiedName }}</a></td>
                                @endif
                            @endforeach
                            <td>{{ $invoice->TxnDate }}</td>
                            <td>{{ $invoice->TotalAmt }}</td>
                            <td>{{ $invoice->Balance }}</td>
                            <td>{{ $invoice->DueDate }}</td>
                            <td><h4><div class="label label-success">{{ $invoice->status }}</div></h4></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>
@endsection
