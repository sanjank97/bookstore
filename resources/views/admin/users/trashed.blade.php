@extends('admin.layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
         <div class="float-start">
            Trashed Users
         </div>
         <div class="float-end">
             <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
        </div>
    </div>
 
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                <th scope="col">S#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Roles</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @forelse ($user->getRoleNames() as $role)
                            <span class="badge bg-primary">{{ $role }}</span>
                        @empty
                        @endforelse
                    </td>
                    <td>
                    
                        @can('delete-user')
                            @if (Auth::user()->id!=$user->id)
                             <form action="{{route('users.restore',$user->id)}}" method="post">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-primary btn-sm" href="" >Restore</button>
                             </form>
                              <form action="{{route('users.delete',$user->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" >Delete</button>
                             </form>

                            @endif
                        @endcan
                        
                    </td>
                </tr>
                @empty
                    <td colspan="5">
                        <span class="text-danger">
                            <strong>No User Found!</strong>
                        </span>
                    </td>
                @endforelse
            </tbody>
        </table>

        {{ $users->links() }}

    </div>
</div>
    
@endsection