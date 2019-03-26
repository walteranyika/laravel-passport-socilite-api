@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add new Restaurant</div>

                    <div class="card-body">

                        {{--form--}}
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('save-meal') }}" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="form-group">
                                <select name="restaurant_id" id="restaurant_id" class="form-control">
                                   @foreach($restaurants as $restaurant)
                                        <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                                   @endforeach
                                </select>
                            </div>


                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name">Meal Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Restaurant Name" required>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('short_description') ? ' has-error' : '' }}">
                                <label for="short_description">Short description</label>
                                <input type="text" class="form-control" id="short_description" name="short_description" value="{{ old('short_description') }}" placeholder="Meal  description" required>
                                @if ($errors->has('short_description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('short_description') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                                <label for="price">Project price</label>
                                <input type="text" class="form-control" id="price" name="price" value="{{ old('price') }}" placeholder="Unit price" required>
                                @if ($errors->has('price'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                @endif
                            </div>


                            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                <label for="image">First Image</label>
                                <input type="file" class="form-control" id="image" name="image"  placeholder="Meal Image" required>
                                @if ($errors->has('image'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('image') }}</strong>
                                </span>
                                @endif
                            </div>


                            <button class="btn btn-block btn-primary" type="submit">Add Meal</button>

                        </form>
                        {{--end form--}}


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
