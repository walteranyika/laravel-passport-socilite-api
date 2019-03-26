@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Restaurants</div>

                    <div class="card-body">
                       <table class="table table-dark">
                           <thead>
                           <tr>
                               <th>Name</th>
                               <th>Phone</th>
                               <th>Address</th>
                               <th># Meals</th>
                               <th>More...</th>
                           </tr>
                           </thead>
                           <tbody>
                               @foreach($hotels as  $hotel)
                                   <tr>
                                       <td>{{$hotel->name}}</td>
                                       <td>{{$hotel->phone}}</td>
                                       <td>{{$hotel->address}}</td>
                                       <td>{{$hotel->meals->count()}}</td>
                                       <td><a href="{{route('details',$hotel->id)}}" class="btn btn-sm btn-info">Meals</a></td>
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
