@extends('layouts.back.master') @section('current_title','Profile')
@section('css')
@endsection
@section('current_path')
<div id="hbreadcrumb" class="pull-right">
    <ol class="hbreadcrumb breadcrumb">
        <li><a href="{{url('admin')}}">Dashboard</a></li>

        <li class="active">
            <span>Profile</span>
        </li>
    </ol>
</div>

@section('content')
  <div class="panel panel-default">
    <div class="panel-body">

      <ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#profile">Profile Details</a></li>
  <li><a data-toggle="tab" href="#menu1">Change Password</a></li>
</ul>

<div class="tab-content">
  <div id="profile" class="tab-pane fade  in active">
    {!! Form::open(['method' => 'POST', 'route' => 'user.admin.profile', 'class' => 'form-horizontal']) !!}
    <br/>
        <div class="col-md-12">
          <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
              {!! Form::label('first_name', 'FIRST NAME', ['class' => 'col-md-2 control-label']) !!}
              <div class="col-md-10">
                  {!! Form::text('first_name', $user->first_name, ['class' => 'form-control']) !!}
                  {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
              </div>
          </div>
          <div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
              {!! Form::label('last_name', 'LAST NAME', ['class' => 'col-md-2 control-label']) !!}
              <div class="col-md-10">
                  {!! Form::text('last_name', $user->last_name, ['class' => 'form-control']) !!}
                  {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
              </div>
          </div>
          <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
              {!! Form::label('email', 'EMAIL', ['class' => 'col-md-2 control-label']) !!}
              <div class="col-md-10">
                  {!! Form::text('email', $user->email, ['class' => 'form-control']) !!}
                  {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
              </div>
          </div>
        </div>
        <div class="form-group">
            <div class="col-md-offset-2 col-md-8">
                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    {!! Form::close() !!}
  </div>
  <div id="menu1" class="tab-pane fade">
    {!! Form::open(['method' => 'POST', 'route' => 'user.admin.password', 'class' => 'form-horizontal']) !!}
    <br/>
        <div class="col-md-12">
          <div class="form-group {{ $errors->has('old_password') ? 'has-error' : ''}}">
              {!! Form::label('old_password', 'OLD PASSWORD', ['class' => 'col-md-2 control-label']) !!}
              <div class="col-md-10">
                  {!! Form::password('old_password', ['class' => 'form-control']) !!}
                  {!! $errors->first('old_password', '<p class="help-block">:message</p>') !!}
              </div>
          </div>
          <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
              {!! Form::label('password', 'NEW PASSWORD', ['class' => 'col-md-2 control-label']) !!}
              <div class="col-md-10">
                  {!! Form::password('password', ['class' => 'form-control']) !!}
                  {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
              </div>
          </div>
          <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : ''}}">
              {!! Form::label('password_confirmation', 'RE-TYPE PASSWORD', ['class' => 'col-md-2 control-label']) !!}
              <div class="col-md-10">
                  {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                  {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
              </div>
          </div>
        </div>
        <div class="form-group">
            <div class="col-md-offset-2 col-md-8">
                {!! Form::submit('Done', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    {!! Form::close() !!}
  </div>

</div>



    </div>
  </div>
@stop
