@extends( (Sentinel::hasAccess('ad.user')) ? 'layouts.back.master2' : 'layouts.back.master')


@section('css')
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-3.5.2/select2.css')}}"/>
<link rel="stylesheet" href="{{asset('assets/back/vendor/select2-bootstrap/select2-bootstrap.css')}}"/>
<!-- Jasny -->
<link href="{{asset('assets/back/css/plugins/jasny/jasny-bootstrap.css" rel="stylesheet')}}">

<!-- Color Picker -->
<link href="{{asset('assets/front/css/colorpicker/bootstrap-colorpicker.css')}}" rel="stylesheet">

<link href="{{url('assets/back/file/bootstrap-fileinput-master/css/file-input.min.css')}}" rel="stylesheet"
type="text/css"/>
<link href="{{url('assets/back/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
rel="stylesheet" type="text/css"/>


<style type="text/css">
.input-group[class*=col-] {
  padding-right: 15px;
  padding-left: 15px;
}

.errors {
  color: red !important;
  text-align: right;
}

.control-label {
  text-align: right;
}

.fileinput.input-group .form-control {
  word-break: break-all;
}
</style>

<style>
.submit-btn-loader {
  color: #52b459;
  font-size: 12px;
  margin: 50px auto;
  width: 0.8em;
  height: 0.8em;
  border-radius: 50%;
  position: relative;
  text-indent: -9999em;
  -webkit-animation: load4 1.3s infinite linear;
  animation: load4 1.3s infinite linear;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
  display: none;
}
@-webkit-keyframes load4 {
  0%,
  100% {
    box-shadow: 0 -3em 0 0.2em, 2em -2em 0 0em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 0;
  }
  12.5% {
    box-shadow: 0 -3em 0 0, 2em -2em 0 0.2em, 3em 0 0 0, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 -1em;
  }
  25% {
    box-shadow: 0 -3em 0 -0.5em, 2em -2em 0 0, 3em 0 0 0.2em, 2em 2em 0 0, 0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 -1em;
  }
  37.5% {
    box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0em 0 0, 2em 2em 0 0.2em, 0 3em 0 0em, -2em 2em 0 -1em, -3em 0em 0 -1em, -2em -2em 0 -1em;
  }
  50% {
    box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 0em, 0 3em 0 0.2em, -2em 2em 0 0, -3em 0em 0 -1em, -2em -2em 0 -1em;
  }
  62.5% {
    box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 0, -2em 2em 0 0.2em, -3em 0 0 0, -2em -2em 0 -1em;
  }
  75% {
    box-shadow: 0em -3em 0 -1em, 2em -2em 0 -1em, 3em 0em 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 0, -3em 0em 0 0.2em, -2em -2em 0 0;
  }
  87.5% {
    box-shadow: 0em -3em 0 0, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 0, -3em 0em 0 0, -2em -2em 0 0.2em;
  }
}
@keyframes load4 {
  0%,
  100% {
    box-shadow: 0 -3em 0 0.2em, 2em -2em 0 0em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 0;
  }
  12.5% {
    box-shadow: 0 -3em 0 0, 2em -2em 0 0.2em, 3em 0 0 0, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 -1em;
  }
  25% {
    box-shadow: 0 -3em 0 -0.5em, 2em -2em 0 0, 3em 0 0 0.2em, 2em 2em 0 0, 0 3em 0 -1em, -2em 2em 0 -1em, -3em 0 0 -1em, -2em -2em 0 -1em;
  }
  37.5% {
    box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0em 0 0, 2em 2em 0 0.2em, 0 3em 0 0em, -2em 2em 0 -1em, -3em 0em 0 -1em, -2em -2em 0 -1em;
  }
  50% {
    box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 0em, 0 3em 0 0.2em, -2em 2em 0 0, -3em 0em 0 -1em, -2em -2em 0 -1em;
  }
  62.5% {
    box-shadow: 0 -3em 0 -1em, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 0, -2em 2em 0 0.2em, -3em 0 0 0, -2em -2em 0 -1em;
  }
  75% {
    box-shadow: 0em -3em 0 -1em, 2em -2em 0 -1em, 3em 0em 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 0, -3em 0em 0 0.2em, -2em -2em 0 0;
  }
  87.5% {
    box-shadow: 0em -3em 0 0, 2em -2em 0 -1em, 3em 0 0 -1em, 2em 2em 0 -1em, 0 3em 0 -1em, -2em 2em 0 0, -3em 0em 0 0, -2em -2em 0 0.2em;
  }
}
</style>
@include('includes.opening-hours-css')
@stop
@section('page_header')
<div class="col-lg-9">
  <h2>Account Settings</h2>
  <ol class="breadcrumb">
    <li>
      <a href="{{url('/')}}">Home</a>
    </li>
    <li class="active">
      <strong>Account Settings </strong>
    </li>
  </ol>
</div>
@stop
@section('content')

<div class="row">
  <div class="col-lg-12 margins">
    <div class="ibox-content">

      <ul id="tabs" class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#basic" id="basicLink">Basic</a></li>
        <li><a data-toggle="tab" href="#about" id="aboutLink">About</a></li>
        <li><a data-toggle="tab" href="#social" id="socialLink">Social</a></li>
        <li><a data-toggle="tab" href="#password" id="passwordLink">Password</a></li>
        @if ($user->inRole('merchant-user') || $user->inRole('super-admin-developer') || $user->inRole('system-admin') || $user->inRole('agent-admin'))

        <li><a data-toggle="tab" href="#business" id="businessLink">Business</a></li>
        <li><a data-toggle="tab" href="#layout" id="layoutLink">Layout</a></li>

        @endif
      </ul>
      <div class="tab-content">

        <div id="basic" class="tab-pane fade in active">
          <div class="hr-line-dashed"></div>
          <form id="basic_form" method="POST">
            {{ csrf_field() }}
            <div class="row">
              <div class="form-group col-sm-6">
                <label class="col-sm-3 control-label">First Name</label>
                <div class="col-sm-9">
                  <div class="row">
                    <div class="col-md-12"><input type="text"
                      placeholder="John"
                      class="form-control"
                      name="first_name"
                      value="{{$user->first_name}}">
                      <p class="help-block errors" id="first_name_error"></p></div>
                    </div>
                  </div>
                </div>
                <div class="form-group col-sm-6">
                  <label class="col-sm-3 control-label">Last Name</label>
                  <div class="col-sm-9">
                    <div class="row">
                      <div class="col-md-12"><input
                        type="text"
                        placeholder="Smith"
                        class="form-control"
                        name="last_name"
                        value="{{$user->last_name}}">
                        <p class="help-block errors" id="last_name_error"></p></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-6">
                    <label class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-9">
                      <div class="row">
                        <div class="col-md-12"><input disabled
                          type="email"
                          placeholder="example@example.com"
                          class="form-control"
                          name="email"
                          value="{{$user->email}}">
                          <p class="help-block errors" id="email_error"></p></div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group col-sm-6">
                      <label class="col-sm-3 control-label">Mobile</label>
                      <div class="col-sm-9">
                        <div class="row">
                          <div class="col-md-12"><input type="text"
                            name="mobile"
                            placeholder="+94xxxxxxxxx or 0xxxxxxxxx"
                            class="form-control"
                            value="{{$user->mobile}}">
                            <p class="help-block errors" id="mobile_error"></p></div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group col-sm-6">
                        <label class="col-sm-3 control-label">Your City</label>
                        <div class="col-sm-9">
                          <div class="row">
                            <div class="col-md-12"><input type="text"
                              name="city"
                              placeholder="Your City"
                              class="form-control"
                              value="{{$user->city}}">
                              <p class="help-block errors" id="city_error"></p></div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <input type="hidden" value="basic_data" name="basic_data">
                      <div class="form-group">
                        <div class="col-sm-11 text-right">
                          <button class="btn btn-default" type="button" onclick="location.reload();">
                            Cancel
                          </button>
                          <button class="btn btn-primary" type="submit">Done
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>

                  <div id="about" class="tab-pane fade in ">
                    <div class="hr-line-dashed"></div>
                    <form id="about_form" method="POST" enctype="multipart/form-data">
                      {{ csrf_field() }}
                      <div class="row ">
                        <div class="row">
                          <div class="col-md-6">
                            @if($user->cover_image)
                            <img src="{{Config('constants.bucket.url'). $user->cover_image}}"
                            class="img-responsive text-center" style="max-height: 150px;">
                            @endif
                          </div>
                          <div class="col-md-6 text-right">
                            @if($user->avatar)
                            <img src="{{Config('constants.bucket.url'). $user->avatar}}"
                            class="img-responsive text-center" style="max-height: 150px;">
                            @endif
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-sm-6">
                            <label class="col-sm-3 control-label">Cover Image</label>
                            <div class="col-sm-9">
                              <div class="row">
                                <div class="col-md-12"><input name="cover_image"
                                  type="file"
                                  placeholder="Cover Image"
                                  class="form-control"
                                  accept="image/jpeg, image/image/x-png"
                                  >
                                  <p class="help-block errors"
                                  id="about_cover_image_error"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="form-group col-sm-6">
                            <label class="col-sm-3 control-label">Avatar</label>
                            <div class="col-sm-9">
                              <div class="row">
                                <div class="col-md-12"><input name="avatar"
                                  type="file"
                                  placeholder="Avatar"
                                  class="form-control"
                                  accept="image/jpeg, image/image/x-png"
                                  >
                                  <p class="help-block errors" id="about_avatar_error"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="form-group col-sm-6">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                              <div class="row">
                                <div class="col-md-12">
                                  <textarea name="description"
                                  rows="8"
                                  style="max-width:100%;"
                                  class="form-control"
                                  placeholder="Description">{{$user->description}}</textarea>

                                  <p class="help-block errors"
                                  id="about_description_error"></p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <input type="hidden" value="about_data" name="about_data">
                      <div class="form-group">
                        <div class="col-sm-11 text-right">
                          <button class="btn btn-default" type="button" onclick="location.reload();">
                            Cancel
                          </button>
                          <button class="btn btn-primary" type="submit">Done
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>

                  <div id="social" class="tab-pane fade in ">
                    <div class="hr-line-dashed"></div>
                    <form id="social_form" method="POST">
                      {{ csrf_field() }}
                      <div class="row ">
                        <div class="row">
                          <div class="form-group col-sm-6">
                            <label class="col-sm-3 control-label">Facebook Url</label>
                            <div class="col-sm-9">
                              <div class="row">
                                <div class="col-md-12"><input name="facebook"
                                  type="text"
                                  placeholder="Facebook Url"
                                  class="form-control"
                                  value="{{$user->facebook_url}}">
                                  <p class="help-block errors"
                                  id="personal_facebook_url_error"></p></div>
                                </div>
                              </div>
                            </div>
                            <div class="form-group col-sm-6">
                              <label class="col-sm-3 control-label">Google+ Url </label>
                              <div class="col-sm-9">
                                <div class="row">
                                  <div class="col-md-12"><input name="google_plus"
                                    type="text"
                                    placeholder="Google+ Url"
                                    class="form-control"
                                    value="{{$user->google_plus_url}}">
                                    <p class="help-block errors"
                                    id="personal_google_plus_url_error"></p></div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group col-sm-6">
                                <label class="col-sm-3 control-label">Twitter</label>
                                <div class="col-sm-9">
                                  <div class="row">
                                    <div class="col-md-12"><input name="twitter"
                                      type="text"
                                      placeholder="Twitter"
                                      class="form-control"
                                      value="{{$user->twitter_url}}">
                                      <p class="help-block errors" id="social_twitter_error"></p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group col-sm-6">
                                <label class="col-sm-3 control-label">Instagram</label>
                                <div class="col-sm-9">
                                  <div class="row">
                                    <div class="col-md-12"><input name="instagram"
                                      type="text"
                                      placeholder="Instagram"
                                      class="form-control"
                                      value="{{$user->instagram_url}}">
                                      <p class="help-block errors"
                                      id="social_instagram_error"></p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group col-sm-6">
                                <label class="col-sm-3 control-label">Linkedin</label>
                                <div class="col-sm-9">
                                  <div class="row">
                                    <div class="col-md-12"><input name="linkedin"
                                      type="text"
                                      placeholder="Linkedin"
                                      class="form-control"
                                      value="{{$user->linkedin_url}}">
                                      <p class="help-block errors" id="social_linkedin_error"></p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <input type="hidden" value="social_data" name="social_data">
                          <div class="form-group">
                            <div class="col-sm-11 text-right">
                              <button class="btn btn-default" type="button" onclick="location.reload();">
                                Cancel
                              </button>
                              <button class="btn btn-primary" type="submit">Done
                              </button>
                            </div>
                          </div>
                        </form>
                      </div>

                      <div id="password" class="tab-pane fade in ">
                        <div class="hr-line-dashed"></div>
                        <form method="post" id="password_form">
                          {{ csrf_field() }}
                          <div class="row">
                            <div class="form-group col-sm-6">
                              <label class="col-sm-3 control-label">Current Password</label>
                              <div class="col-sm-9">
                                <div class="row">
                                  <div class="col-md-12"><input name="current_password"
                                    type="password"
                                    placeholder="Enter your current password"
                                    class="form-control">
                                    <p class="help-block errors" id="current_password_error"></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="form-group col-sm-6">
                              <label class="col-sm-3 control-label">Password</label>
                              <div class="col-sm-9">
                                <div class="row">
                                  <div class="col-md-12"><input id="password_new"
                                    name="password"
                                    type="password"
                                    placeholder="Enter your new password"
                                    class="form-control">
                                    <p class="help-block errors" id="password_error"></p></div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="form-group col-sm-6">
                                <label class="col-sm-3 control-label">Confirm Password</label>
                                <div class="col-sm-9">
                                  <div class="row">
                                    <div class="col-md-12"><input name="password_confirmation"
                                      type="password"
                                      placeholder="Confirm password"
                                      class="form-control">
                                      <p class="help-block errors"
                                      id="password_confirmation_error"></p>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            </div>
                            <input type="hidden" value="password_data" name="password_data">
                            <div class="form-group">
                              <div class="col-sm-11 text-right">
                                <button class="btn btn-default" type="button" onclick="location.reload();">
                                  Cancel
                                </button>
                                <button class="btn btn-primary" type="submit">Done
                                </button>
                              </div>
                            </div>
                          </form>
                        </div>

                        @if ($user->inRole('merchant-user') || $user->inRole('super-admin-developer') || $user->inRole('system-admin') || $user->inRole('agent-admin'))

                        <div id="business" class="tab-pane fade in">
                          <div class="hr-line-dashed"></div>
                          <form method="post" id="business_form">
                            {{ csrf_field() }}
                            <div class="row ">
                              <div class="form-group">
                                <label class="col-sm-2 control-label">Business Name</label>
                                <div class="col-sm-6">
                                  <input name="business_name"
                                  type="text"
                                  placeholder="Ex: David Smith"
                                  class="form-control"
                                  value="{{$business->business_name or ""}}">
                                  <p class="help-block errors" id="business_name_error"></p>
                                </div>
                              </div>
                            </div>

                            <div class="row ">
                              <div class="form-group">
                                <label class="col-sm-2 control-label">About Business</label>
                                <div class="col-sm-6">
                                  <textarea name="about"
                                  type="text"
                                  class="form-control"
                                  >{{$business->about or ""}}</textarea>
                                  <p class="help-block errors" id="business_about_error"></p>
                                </div>
                              </div>
                            </div>

                            <div class="row ">
                              <div class="form-group">
                                <label class="col-sm-2 control-label">Opening Hours</label>
                                <div class="col-sm-10">
                                  @include('includes.opening-hours-module', ['openingData' => $business ? $business->getOpeningTimes : null])
                                  <br>
                              </div>
                            </div>

                            <div class="row ad-container">
                                    {{-- <div class="form-group">
                                        <label class="col-sm-2 control-label">URL</label>
                                        <div class="col-sm-6">
                                            <input name="business_url"
                                            type="text"
                                            placeholder="Ex: http://www.sambole.lk/"
                                            class="form-control"
                                            value="{{$business->website or ""}}">
                                            <p class="help-block errors" id="business_url_error"></p>
                                        </div>
                                      </div> --}}
                                      {{-- @if($user->store) --}}
                                      <div class="form-group profile-url"><label class="col-sm-2 control-label ">PROFILE
                                      URL</label>
                                      <div class="col-sm-6 input-group">
                                        <span class="input-group-addon">http://www.sambole.lk/</span>
                                        <input type="text" name="profile_url" id="profile_url" class="form-control"
                                        value="{{$business->business_page_name or ""}}">
                                      </div>
                                      <label id="profile_url-error" class="error" for="first_name"></label>
                                    </div>
                                    {{-- @endif  --}}
                                  </div>
                            {{-- <div class="row ad-container">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Website</label>
                                    <div class="col-sm-6">
                                        <input name="business_url"
                                        type="text"
                                        placeholder="Website"
                                        class="form-control"
                                        value="{{$business->website or ""}}">
                                        <p class="help-block errors" id="business_url_error"></p>
                                    </div>
                                </div>
                              </div> --}}

                              <div class="row ad-container">
            {{-- <div class="form-group">
                <label class="col-sm-2 control-label">URL</label>
                <div class="col-sm-6">
                    <input name="business_url"
                    type="text"
                    placeholder="Ex: http://www.sambole.lk/"
                    class="form-control"
                    value="{{$business->website or ""}}">
                    <p class="help-block errors" id="business_url_error"></p>
                </div>
              </div> --}}

              {{-- @if($user->store) --}}
            {{--< div class="form-group profile-url"><label class="col-sm-2 control-label ">PROFILE URL</label>
               <div class="col-sm-6 input-group">
                <span class="input-group-addon">http://www.sambole.lk/</span>
                <input type="text" name="profile_url" id="profile_url" class="form-control" value="{{$business->business_page_name or ""}}">
            </div>
            <label id="profile_url-error" class="error" for="first_name"></label>
          </div> --}}
          {{-- @endif  --}}
        </div>
        <div class="row ad-container">
          <div class="form-group">
            <label class="col-sm-2 control-label">Website</label>
            <div class="col-sm-6">
              <input name="business_url"
              type="text"
              placeholder="Website"
              class="form-control"
              value="{{$business->website or ""}}">
              <p class="help-block errors" id="business_url_error"></p>
            </div>
          </div>
        </div>

        <div class="row ad-container">
          <div class="form-group">
            <label class="col-sm-2 control-label">Industry</label>
            <div class="col-sm-6">
              <select class="form-control m-b"
              name="business_industry"
              placeholder="Industry">
              @if (isset($business->industry_id ))
              @foreach ($industries->sortBy("name") as $industry)
              <option value="{{$industry->id}}" {{$business->industry_id == $industry->id ? "selected" : ""}}>{{$industry->name}}</option>
              @endforeach
              @else
              @foreach ($industries->sortBy("name") as $industry)
              <option value="{{$industry->id}}">{{$industry->name}}</option>
              @endforeach
              @endif

            </select>
            <p class="help-block errors" id="business_industry_error"></p>
          </div>
        </div>
      </div>


      <div class="row ad-container">
        <div class="form-group">
          <label class="col-sm-2 control-label">Contact No</label>
          <div class="col-sm-6">
            <input name="business_contact_number"
            type="text"
            placeholder="94xxxxxxxxx or 0xxxxxxxxxx"
            class="form-control"
            required="required"
            value="{{$business->contact_number or ""}}">
            <p class="help-block errors" id="business_contact_number_error"></p>

          </div>
        </div>
      </div>


      <div class="row ad-container">
        <div class="form-group">
          <label class="col-sm-2 control-label">Company Address</label>
          <div class="col-sm-6">
            <textarea class="form-control"
            name="business_company_address"
            rows="8"
            cols="80"
            style="max-width: 100%;">{{$business->company_address or ""}}</textarea>
            <p class="help-block errors"
            id="business_company_address_error"></p>

          </div>
        </div>
      </div>

      <div class="row ad-container">
        <div class="form-group">
          <label class="col-sm-2 control-label">Facebook Url</label>
          <div class="col-sm-6">
            <input type="text"
            name="business_facebook_url"
            class="form-control"
            placeholder="Facebook URL"
            value="{{$business->facebook_url or "" }}">
            <p class="help-block errors" id="business_facebook_url_error"></p>
          </div>
        </div>
      </div>

      <div class="row ad-container">
        <div class="form-group">
          <label class="col-sm-2 control-label">Google+ Url</label>
          <div class="col-sm-6">
            <input name="business_google_plus_url"
            type="text"
            class="form-control"
            placeholder="Google Plus URL"
            value="{{$business->googleplus_url or ""}}">
            <p class="help-block errors"
            id="business_google_plus_url_error"></p>
          </div>
        </div>
      </div>

      <div class="row ad-container">
        <div class="form-group">
          <label class="col-sm-2 control-label">Instagram Url</label>
          <div class="col-sm-6">
            <input name="instagram_url"
            type="text"
            class="form-control"
            placeholder="Instagram URL"
            value="{{$business->instagram_url or ""}}">
            <p class="help-block errors"
            id="business_instagram_url_error"></p>
          </div>
        </div>
      </div>

      <div class="row ad-container">
        <div class="form-group">
          <label class="col-sm-2 control-label">Twitter Url</label>
          <div class="col-sm-6">
            <input name="twitter_url"
            type="text"
            class="form-control"
            placeholder="Twitter URL"
            value="{{$business->twitter_url or ""}}">
            <p class="help-block errors"
            id="business_twitter_url_error"></p>
          </div>
        </div>
      </div>

      <div class="row ad-container">
        <div class="form-group">
          <label class="col-sm-2 control-label">LinkedIn Url</label>
          <div class="col-sm-6">
            <input name="linkedin_url"
            type="text"
            class="form-control"
            placeholder="LinkedIn URL"
            value="{{$business->linkedin_url or ""}}">
            <p class="help-block errors"
            id="business_linkedin_url_error"></p>
          </div>
        </div>
      </div>

      <input type="hidden" value="business_data" name="business_data">
      <div class="form-group">
        <div class="col-sm-11 text-right">
          <button class="btn btn-default" type="button"
          onclick="location.reload();">
          Cancel
        </button>
        <button class="btn btn-primary" type="submit">Done
        </button>
      </div>
    </div>
  </form>
</div>
</div>

<div id="layout" class="tab-pane fade in">

  <div class="hr-line-dashed"></div>
  <form id="layout_form" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group ">
      <div class="row">
        <label class="col-sm-2 control-label">Logo</label>

        <div class="col-sm-10">
          @if (isset($layoutDetails->logo_img))
          <img src="{{Config('constants.bucket.url'). $layoutDetails->logo_img}}"
          class="  logo-image" style="max-width:100%; width : 150px;">
          @else
          Please select a logo
          @endif
        </div>
      </div>


    </div>

    <div class="form-group ">
      <div class="row">
        <label class="col-sm-2 control-label">Select Logo</label>
        <div class="fileinput col-sm-6 fileinput-new input-group"
        data-provides="fileinput">
        <div class="form-control" data-trigger="fileinput">
          <i class="glyphicon glyphicon-file fileinput-exists"></i>
          <span class="fileinput-filename"></span>
        </div>
        <span class="input-group-addon btn btn-default btn-file">
          <span class="fileinput-new"><i class="fa fa-paperclip"></i></span>
          {{-- <span class="fileinput-exists">Change</span> --}}
          <input accept="image/jpeg, image/x-png"
          type="file"
          name="layout_logo"/>
        </span>
        <a href="#" class="input-group-addon btn btn-default fileinput-exists"
        data-dismiss="fileinput"><i class="fa fa-times"></i></a>
      </div>
    </div>
    <p class="help-block errors" id="layout_logo_error"></p>
  </div>


  <div class="row ad-container">
    <div class="form-group col-sm-12">
      <div class="row">
        <label class="col-sm-2 control-label">Slider Images</label>
        <div class="col-sm-10">
          @if (isset($layoutDetails->sliderimage))
          @foreach ($layoutDetails->sliderimage as $img)
          <div class="col-sm-6 col-md-4 ">

            <img src="{{Config('constants.bucket.url'). $img->img_path . $img->img_name}}"
            class="logo-image"
            style="max-width:100%; padding:5px; height: 200px;">
            <div class="checkbox text-center">
              <label><input type="checkbox"
                name="layout_slider_delete[]"
                value="{{$img->id}}">Delete</label>
              </div>

            </div>
            @endforeach
            @else
            Plese select a slider image
            @endif
          </div>
        </div>


        <div class="row" style="margin-top: 10px;">
          <label class="col-sm-2 control-label">Select Slider Images</label>
          <div class="fileinput col-sm-6 fileinput-new input-group"
          data-provides="fileinput">
          <div class="form-control" data-trigger="fileinput">
            <i class="glyphicon glyphicon-file fileinput-exists"></i>
            <span class="fileinput-filename"></span>
          </div>
          <span class="input-group-addon btn btn-default btn-file">
           <span class="fileinput-new"><i class="fa fa-paperclip"></i></span>
           {{-- <span class="fileinput-exists">Change</span> --}}
           <input accept="image/jpeg, image/x-png"
           type="file"
           name="layout_slider_image[]"
           multiple/>
         </span>
         <a href="#"
         class="input-group-addon btn btn-default fileinput-exists"
         data-dismiss="fileinput"><i class="fa fa-times"></i></a>

       </div>
     </div>

     <p class="help-block errors " id="layout_slider_image_error"></p>
   </div>
 </div>


 <div class="form-group " id="dropdown-industry">
  <div class="row">

    <label class="col-sm-2 control-label">Header Style</label>
    <div class="col-sm-6">
      <select class="form-control "
      name="layout_header_style">

      <option value="1" {{(isset($layoutDetails->header_style_id) && $layoutDetails->header_style_id == 1) ? "selected" : ""}}>
        Logo left align
      </option>
      <option value="2" {{(isset($layoutDetails->header_style_id) && $layoutDetails->header_style_id == 2) ? "selected" : ""}}>
        Logo center align
      </option>
      <option value="3" {{(isset($layoutDetails->header_style_id) && $layoutDetails->header_style_id == 3) ? "selected" : ""}}>
        Logo right align
      </option>

    </select>
    <p class="help-block errors" id="layout_header_style_error"></p>
  </div>
</div>

</div>


<div class="form-group ">
  <div class="row">

    <label class="col-sm-2 control-label">Header Color</label>
    <div class="col-sm-6 input-group" id="cp2">
      <input name="layout_header_color"
      type="text"
      placeholder="Pick Header Color" class="form-control"
      value="{{$layoutDetails->header_color_code or ""}}"
      readonly
      style="background-color: #fff; cursor: text;"/>
      <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
    </div>
  </div>

  <p class="help-block errors" id="layout_header_color_error"></p>
</div>


<div class="form-group">
  <div class="row">

    <label class="col-sm-2 control-label">Background Color</label>
    <div class="col-sm-6 input-group " id="cp3">
      <input name="layout_background_color"
      type="text"
      placeholder="Pick Background Color (Optional)"
      class="form-control"
      value="{{$layoutDetails->background_color_code or ""}}" readonly
      style="background-color: #fff; cursor: text;"/>
      <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
    </div>
  </div>

  <p class="help-block errors" id="layout_background_color_error"></p>
</div>


<div class="form-group ">
  <div class="row">

    <label class="col-sm-2 control-label advanceC"> Advance
    Customizations</label>
    <button style="margin-left : 15px;" class="submit disabled" disabled="disabled">
      *Coming soon
    </button>
  </div>
</div>


<input type="hidden" value="layout_data" name="layout_data">
<div class="form-group">
  <div class="submit-btn-loader">Loading...</div>
  <div class="col-sm-11 text-right">
    <button class="btn btn-default" type="button"
    onclick="location.reload();">
    Cancel
  </button>
  <button class="btn btn-primary" id="layout_save_btn" type="submit">Done
  </button>
</div>
</div>
</form>
</div>

@endif
</div>

</div>

</div>
</div>

@stop
@section('js')
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
{{-- <script src="http://localhost/sambole-web-portal/assets/front/js/datepicker/bootstrap-datepicker.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
{{--    <script src="{{asset('assets/back/vendor/select2-3.5.2/select2.min.js')}}"></script>--}}
{{--    <script src="{{asset('assets/back/vendor/jquery-validation/jquery.validate.min.js')}}"></script>--}}
<!-- color picker -->
<script src="{{asset('assets/front/js/colorpicker/bootstrap-colorpicker.js')}}"></script>
<!-- Jasny -->
<script src="{{asset('assets/back/js/plugins/jasny/jasny-bootstrap.js')}}"></script>

<script>
  $(document).ready(function($) {
    $("#layout_save_btn").click(function(event) {
      event.preventDefault();
      $(".submit-btn-loader").css('display', 'block');
      // $("#formCancel").prop('disabled', true);
      $(this).prop('disabled', true);
      $("#layout_form").submit();
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function ($) {
    $('.datepicker').datetimepicker({
      format: 'LT'
    });
  });

  jQuery(function () {
    jQuery("#cp2").colorpicker({format: "hex"})
    .on('change', function (e) {
      jQuery("#formId1").bootstrapValidator('enableFieldValidators', 'header_color_id', false, 'notEmpty');
      jQuery("#formId1").bootstrapValidator('enableFieldValidators', 'header_color_id', true, 'hexColor');

    });
  });
  jQuery(function () {
    jQuery("#cp3").colorpicker({format: "hex"})
    .on('colorpickerUpdate', function (e) {

    });
  });
</script>
<script>
  @if($errors->any())
  @foreach($errors->all() as $error)
  toastr.error("{!! $error !!}");
  @endforeach
  @endif
</script>
<script>
  $.validator.addMethod("lettersonly", function (value, element) {
    return this.optional(element) || /^[a-z]+$/i.test(value);
  }, "Letters only please");

  $.validator.addMethod("urlwww", function (value, element) {
    return this.optional(element) || /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(value);
  }, "Please enter a valid URL.");

  $.validator.addMethod("phone", function (value, element) {
    return this.optional(element) || /(^[+](94)[0-9]{9}$)|(^(0)[0-9]{9}$)/i.test(value);
  }, "Please specify a valid phone number");

  $.validator.addMethod("alphanumeric", function (value, element) {
    return this.optional(element) || /^\w+$/i.test(value);
  }, "Letters, numbers, spaces or underscores only please");

  $.validator.addMethod("noSpace", function (value, element) {
    return value.indexOf(" ") < 0 && value != "";
  }, "No space please and don't leave it empty");

  $.validator.addMethod('filesize', function (value, element, arg) {
            var minsize = 1000; // min 1kb
            if ((value > minsize) && (value <= arg)) {
              return true;
            } else {
              return false;
            }
          });

  $("#basic_form").validate({
    rules: {
      first_name: {
        required: true,
        lettersonly: true,
        maxlength: 20
      },
      last_name: {
        required: true,
        lettersonly: true,
        maxlength: 20
      },
      mobile: {
        required: true,
        phone: true

      },
      branch: {
        required: true
      },
      city: {
        required: true
      }
    },
    submitHandler: function (form) {
      form.submit();
    }
  });

  $("#about_form").validate({
    rules: {
      cover_image: {
                    // required: true,
                    extension: "png,jpg,jpeg"
                    // filesize: 10000
                  },
                  avatar: {
                    // required: true,
                    extension: "png,jpg,jpeg"
                    // filesize: 10000
                  },
                  description: {
                    // alphanumeric: true
                  }
                },
                submitHandler: function (form) {
                  form.submit();
                }
              })

  $("#social_form").validate({
    rules: {
      facebook: {
        urlwww: true,
                // maxlength: 255,
                // url: true
              },
              google_plus: {
                urlwww: true,
                // maxlength: 255,
                // url: true
              },
              twitter: {
                urlwww: true,
                    // maxlength: 255,
                    // url: true
                  },
                  instagram: {
                    urlwww: true,
                    // maxlength: 255,
                    // url: true
                  },
                  linkedin: {
                    urlwww: true,
                    // maxlength: 255,
                    // url: true
                  }
                },
                submitHandler: function (form) {
                  form.submit();
                }
              });

  $("#password_form").validate({
    rules: {
      current_password: {
        required: true,
        minlength: 6
      },
      password: {
        required: true,
        minlength: 6
      },
      password_confirmation: {
        required: true,
        equalTo: '#password_new',
        minlength: 6
      }
    },
    submitHandler: function (form) {
      form.submit();
    }
  });

  $("#business_form").validate({
    rules: {
      business_name: {
        required: true,
        maxlength: 32
      },
      business_url: {
        urlwww: true,
      },
      profile_url: {
        required: true,
        noSpace: true
      },
      business_page_name: {
        required: true,
        maxlength: 32
      },
      business_contact_number: {
        required: true,
        phone: true
      },
      business_company_address: {
        required: true
      },
      business_facebook_url: {
        urlwww: true
      },
      business_google_plus_url: {
        urlwww: true
      },
      instagram_url: {
        urlwww: true
      },
      linkedin_url: {
        urlwww: true
      },
      twitter_url: {
        urlwww: true
      }
    },
    submitHandler: function (form) {
      form.submit();
    }
  });

  $("#profile_url").keyup(function (event) {
    $.ajax({
      url: '{{url('checkPageName')}}',
      data: {
        business_page_name: $('#profile_url').val(),
        _token: '{{csrf_token()}}'
      },
      method: 'POST',
      success: function (data) {
        console.log(data);
        if (JSON.parse(data)['valid'] !== 'true') {
          $('#profile_url-error').text('');
          $('#profile_url-error').text('This url is not available');
                        // $(".submit").prop("disabled", true);
                      }
                      else {
                        $('#profile_url-error').text('');
                        // $(".submit").prop("disabled", false);
                      }
                    }
                  })

  });
</script>

<script type="text/javascript">
  {{--function submitFrom() {--}}
  {{--$(".errors").html("")--}}
  {{--$("#submitFormBtn").html('Please Wait <i class="fa fa-spinner fa-spin"></i>')--}}
  {{--data = new FormData(document.getElementById("accountSettingForm"))--}}
  {{--ajax_request(data)--}}
  {{--}--}}

  {{--function ajax_request(data) {--}}

  {{--var xhttp;--}}
  {{--if (window.XMLHttpRequest) {--}}
  {{--xhttp = new XMLHttpRequest();--}}
  {{--} else {--}}
  {{--// code for IE6, IE5--}}
  {{--xhttp = new ActiveXObject("Microsoft.XMLHTTP");--}}
  {{--}--}}
  {{--xhttp.addEventListener("load", function () {--}}

  {{--try {--}}
  {{--SubmitResultHandler(JSON.parse(this.responseText));--}}
  {{--} catch (e) {--}}
  {{--$("#submitFormBtn").html('Error. Please Try Again')--}}
  {{--setTimeout(function () {--}}
  {{--$("#submitFormBtn").html('Done')--}}
  {{--}, 3000);--}}
  {{--}--}}


  {{--});--}}
  {{--xhttp.addEventListener("error", function () {--}}
  {{--$("#submitFormBtn").html('Error. Please Try Again')--}}
  {{--setTimeout(function () {--}}
  {{--$("#submitFormBtn").html('Done')--}}
  {{--}, 3000);--}}
  {{--});--}}

  {{--xhttp.open("POST", "{{url("user/account-settings")}}", false);--}}

  {{--xhttp.setRequestHeader("X-CSRF-TOKEN", "{{csrf_token()}}");--}}

  {{--xhttp.send(data);--}}
  {{--}--}}

  {{--function SubmitResultHandler(data) {--}}
  {{--if (data.hasOwnProperty('errors') && Object.keys(data.errors).length !== 0) {--}}
  {{--$("#submitFormBtn").html('Please check the erros')--}}
  {{--submitErrors(data.errors);--}}
  {{--setTimeout(function () {--}}
  {{--$("#submitFormBtn").html('Done')--}}
  {{--}, 3000);--}}


  {{--} else if (data.hasOwnProperty('success') && data.success == "19199212") {--}}
  {{--$("#submitFormBtn").html('Sucess')--}}
  {{--setTimeout(function () {--}}
  {{--$("#submitFormBtn").html('Done')--}}
  {{--submitSuccess(data.message);--}}
  {{--}, 3000);--}}

  {{--} else {--}}
  {{--$("#submitFormBtn").html('Error. Please Try Again')--}}
  {{--setTimeout(function () {--}}
  {{--$("#submitFormBtn").html('Done')--}}
  {{--}, 3000);--}}
  {{--}--}}
  {{--}--}}

  {{--function submitErrors(errors) {--}}
  {{--clicked = false--}}
  {{--Object.keys(errors).forEach(function (key) {--}}
  {{--if ($("#basic").has($("#" + key + "_error")).length && !clicked) {--}}
  {{--clicked = true--}}
  {{--$('#basicLink').click();--}}

  {{--} else if ($("#about").has($("#" + key + "_error")).length && !clicked) {--}}
  {{--clicked = true--}}
  {{--$("#aboutLink").click()--}}

  {{--} else if ($("#social").has($("#" + key + "_error")).length && !clicked) {--}}
  {{--clicked = true--}}
  {{--$("#socialLink").click()--}}

  {{--} else if ($("#password").has($("#" + key + "_error")).length && !clicked) {--}}
  {{--clicked = true--}}
  {{--$("#passwordLink").click()--}}

  {{--}--}}

  {{--$("#" + key + "_error").html(errors[key])--}}

  {{--});--}}
  {{--}--}}

  {{--function submitSuccess(data) {--}}

  {{--window.location.reload();--}}

  {{--}--}}


</script>
@include('includes.opening-hours-js')
@stop
