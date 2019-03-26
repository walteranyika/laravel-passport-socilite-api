@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Meals</div>

                    <div class="card-body">
                        <table class="table table-dark">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Unit Price</th>
                                <th>Image</th>
                                <th>Remove</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{--"name","short_description","image","price"--}}
                            @foreach($meals as  $meal)
                                <tr>
                                    <td>{{$meal->name}}</td>
                                    <td>{{$meal->short_description}}</td>
                                    <td>{{$meal->price}}</td>
                                    <td><img src="{{asset('images/'.$meal->image)}}" alt="{{$meal->name}}" style="max-width: 200px;" class="img-thumbnail"></td>
                                    <td><a href="{{route('remove',$meal->id)}}" class="btn btn-sm btn-danger">X</a></td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
