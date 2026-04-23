@extends('cms.parent')

@section('title','Edit Debit')
@section('page-name','Edit Debit')
@section('main-page','Debits')
@section('sub-page','Edit Debit')

@section('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('cms/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('cms/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- Toastr -->
<link rel="stylesheet" href="{{ asset('cms/plugins/toastr/toastr.min.css') }}">
@endsection

@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Debit</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form role="form" id="create_form">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>User</label>
                                <select class="form-control users" id="user_id" style="width: 100%;">
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if($debit->user_id == $user->id)
                                            selected @endif>{{ $user->first_name . ' ' . $user->last_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Currency</label>
                                <select class="form-control currencies" id="currency_id" style="width: 100%;">
                                    @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}" @if($debit->currency_id == $currency->id)
                                        selected @endif>{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control types" id="type" style="width: 100%;">
                                    <option value="Debtor" @if($debit->type == 'Debtor')
                                        selected @endif>Debtor</option>
                                    <option value="Creditor" @if($debit->type == 'Creditor')
                                        selected @endif>Creditor</option>
                                </select>
                                </div>
                                <div class="form-group">
                                <label>Payment Type</label>
                                <select class="form-control payment_types" id="payment_type" style="width: 100%;">
                                    <option value="Single" @if($debit->	payment_type == 'Single')
                                        selected @endif>Single</option>
                                    <option value="Multi" @if($debit->	payment_type == 'Multi')
                                        selected @endif>Multi</option>
                                </select>
                                </div>
                                <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" placeholder="Enter title" value="{{ $debit->title }}">
                                </div>
                                <div class="form-group">
                                <label for="total">Total</label>
                                <input type="number" class="form-control" id="total" placeholder="Enter total" value="{{ $debit->total }}">
                                </div>
                                <div class="form-group">
                                <label for="remain">Remain</label>
                                <input type="number" class="form-control" id="remain" placeholder="Enter remain (optional)" value="{{ $debit->remain }}">
                                </div>
                                <div class="form-group">
                                <label>Date and time range:</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="reservationtime" value="{{ $debit->date }}">
                                </div>
                                <!-- /.input group -->
                                </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="button" onclick="performUpdate({{ $debit->id }})"
                                class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{ asset('cms/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('cms/plugins/toastr/toastr.min.js') }}"></script>

<script type="text/javascript">
    //Initialize Select2 Elements
        $('.cities').select2({
            theme: 'bootstrap4'
        })
        $('.professions').select2({
            theme: 'bootstrap4'
        })
</script>

<script>
    function performUpdate(id){
        let data = {
            user_id: document.getElementById('user_id').value,
            currency_id: document.getElementById('currency_id').value,
            type: document.getElementById('type').value,
            payment_type: document.getElementById('payment_type').value,
            title: document.getElementById('title').value,
            total: document.getElementById('total').value,
            remain: document.getElementById('remain').value,
            date: document.getElementById('reservationtime').value,
        }
        let redirectUrl = '{{ route('debits.index') }}'
        console.log('URL: '+redirectUrl);
        update('/cms/admin/debits/'+id, data, redirectUrl);
    }
</script>

@endsection
