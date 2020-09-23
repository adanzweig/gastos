@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Balance
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($balance, ['route' => ['balances.update', $balance->id], 'method' => 'patch']) !!}

                        @include('balances.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection