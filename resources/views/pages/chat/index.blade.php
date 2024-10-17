@extends('layout.master')

@push('plugin-styles')

@endpush

@section('topItem')

@if($isAddingGroup == 1)
<div style="position: absolute; z-index: 1050; background-color: #2222; width: 100vw; min-height: 100vh;">
    <div class="container-fluid h-100 w-100" style="min-height: 100vh;">
        <div class="row h-100" style="min-height: 100vh;">
            <div class="col"></div>
            <div class="col-6 card h-100" style="min-height: 100vh;">
                <div class="card-header">
                    <div class="card-title position-relative">
                        <a class="position-absolute top-5 left-5 btn btn-outline-dark" href="?">
                            <i class="mdi mdi-close"></i>
                        </a>
                        <h4 class="text-center">Add Chat Group</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('chat.add-group')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="group_name">Group Name</label>
                                <input type="text" class="form-control {{ $errors->has('group_name') ? ' is-invalid' : '' }}" id="group_name" placeholder="Group Name" name="group_name" value="{{old('group_name', '')}}">

                                @if ($errors->group_name)
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('group_name') }}</strong>
                                </span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Add Chat Group</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@elseif($isJoiningGroup == 1)
<div style="position: absolute; z-index: 1050; background-color: #2222; width: 100vw; min-height: 100vh;">
    <div class="container-fluid h-100 w-100" style="min-height: 100vh;">
        <div class="row h-100" style="min-height: 100vh;">
            <div class="col"></div>
            <div class="col-6 card h-100" style="min-height: 100vh;">
                <div class="card-header">
                    <div class="card-title position-relative">
                        <a class="position-absolute top-5 left-5 btn btn-outline-dark" href="?">
                            <i class="mdi mdi-close"></i>
                        </a>
                        <h4 class="text-center">Join Chat Group</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('chat.search-group-to-join')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="group_name">Group Name</label>
                                <input type="text" class="form-control {{ $errors->has('group_name') ? ' is-invalid' : '' }}" id="group_name" placeholder="Group Name" name="group_name" value="{{old('group_name', '')}}">

                                @if ($errors->group_name)
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('group_name') }}</strong>
                                </span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Add Chat Group</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@elseif($isViewingGroupDetails == 1)
<div style="position: absolute; z-index: 1050; background-color: #2222; width: 100vw; min-height: 100vh;">
    <div class="container-fluid h-100 w-100" style="min-height: 100vh;">
        <div class="row h-100" style="min-height: 100vh;">
            <div class="col"></div>
            <div class="col-6 card h-100" style="min-height: 100vh;">
                <div class="card-header">
                    <div class="card-title position-relative">
                        <a class="position-absolute top-5 left-5 btn btn-outline-dark" href="?">
                            <i class="mdi mdi-close"></i>
                        </a>
                        <h4 class="text-center">Group Details</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-sm">Group Name</div>
                        <div class="lead">{{$chatRoom->group_name}}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-sm">Group Description</div>
                        <div class="lead">{{$chatRoom->group_name}}</div>
                    </div>
                    <hr />
                    <div class="font-weight-bold">Group Members</div>
                    <div class="d-flex justify-content-end">
                        <a href="?chatRoomId={{$chatRoom->id}}&isAddingGroupMember=1" class="btn btn-primary">Add Member</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover dt">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupMembers as $member)
                                <tr>
                                    <td>{{$member->user->username}}</td>
                                    <td>{{$member->user->email}}</td>
                                    <td>{{$member->user->getRoleNames()[0]}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@elseif($isAddingGroupMember == 1)
<div style="position: absolute; z-index: 1050; background-color: #2222; width: 100vw; min-height: 100vh;">
    <div class="container-fluid h-100 w-100" style="min-height: 100vh;">
        <div class="row h-100" style="min-height: 100vh;">
            <div class="col"></div>
            <div class="col-6 card h-100" style="min-height: 100vh;">
                <div class="card-header">
                    <div class="card-title position-relative">
                        <a class="position-absolute top-5 left-5 btn btn-outline-dark" href="?chatRoomId={{$chatRoom->id}}&isViewingGroupDetails=1">
                            <i class="mdi mdi-close"></i>
                        </a>
                        <h4 class="text-center">Add Group Member</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('chat.add-group-member')}}" method="POST">
                        @csrf
                        <input type="hidden" name="chat_room_id" value="{{$chatRoom->id}}">
                        <div class="form-group">
                            <label for="identification_type">Identification Type</label>
                            <select class="form-control" name="identification_type" id="identification_type">
                                <option value="username" @if(old("identification_type", '' )=="username" ) selected @endif>Username</option>
                                <option value="email" @if(old("identification_type", '' )=="email" ) selected @endif>Email</option>
                            </select>

                            @if($errors->has("identification_type"))
                            <div class="text-danger">{{$errors->first()}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="identification_value">Identification Value</label>
                            <input type="text" class="form-control" name="identification_value" id="identification_value" value="{{old('identification_value', '')}}">

                            @if($errors->has('identification_value'))
                            <div class="text-danger">{{$errors->first('identification_value')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="is_admin" class="mr-2">Is Admin?</label>
                            <input type="checkbox" name="is_admin" id="is_admin">
                        </div>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary">Add Member</button>
                            <a href="?chatRoomId={{$chatRoom->id}}&isViewingGroupDetails=1" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@elseif($isAddingChat == 1)
<div style="position: absolute; z-index: 1050; background-color: #2222; width: 100vw; min-height: 100vh;">
    <div class="container-fluid h-100 w-100" style="min-height: 100vh;">
        <div class="row h-100" style="min-height: 100vh;">
            <div class="col"></div>
            <div class="col-6 card h-100" style="min-height: 100vh;">
                <div class="card-header">
                    <div class="card-title position-relative">
                        <a class="position-absolute top-5 left-5 btn btn-outline-dark" href="?">
                            <i class="mdi mdi-close"></i>
                        </a>
                        <h4 class="text-center">Add Chat</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('chat.add-chat')}}" method="POST">
                        @csrf
                        {{$errors}}
                        <div class="form-group">
                            <label for="identification_type">User Identification Type</label>
                            <select class="form-control" name="identification_type" id="identification_type">
                                <option value="username" @if(old("identification_type", '' )=="username" ) selected @endif>Username</option>
                                <option value="email" @if(old("identification_type", '' )=="email" ) selected @endif>Email</option>
                            </select>

                            @if($errors->has("identification_type"))
                            <div class="text-danger">{{$errors->first()}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="identification_value">User Identification Value</label>
                            <input type="text" class="form-control" name="identification_value" id="identification_value" value="{{old('identification_value', '')}}">

                            @if($errors->has('identification_value'))
                            <div class="text-danger">{{$errors->first('identification_value')}}</div>
                            @endif
                        </div>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary">Add Chat</button>
                            <a href="?" class="btn btn-secondary ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="d-flex justify-content-end mb-1">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <!-- <a class="dropdown-item" href="?isJoiningGroup=1">Join Chat Group</a> -->
                            <a class="dropdown-item" href="?isAddingChat=1">Add Chat</a>
                            <a class="dropdown-item" href="?isAddingGroup=1">Add Group Chat</a>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
                <div>
                    @foreach($myChatRooms as $key => $room)
                    <a href="?chatRoomId={{$room->id}}" class="text-dark">
                        <div class="border p-3 mb-1 bg-white rounded @if($chatRoomId == $room->id) border-primary text-primary @endif d-flex justify-content-between align-items-center">
                            <div>{{$room->room_name}}</div>
                            @if($room->unread > 0)
                            <div class="badge badge-primary">{{$room->unread}}</div>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            <div class="col-8">
                <div class="border border-primary p-3 mb-1 bg-white rounded">@if($chatRoomId == '') Select a chat room @else {{$chatRoom->room_name}} @endif</div>
                <div id="chat-messages" class="p-2" style="height: 400px; overflow-y: scroll; background-color: #f5f5f5; display: flex; flex-direction: column;">
                    @foreach($chatMessages as $key => $message)
                    @if(Auth::id() == $message->sender_id)
                    <div style="border: 1px solid #ccc; padding: 10px; background-color: #ccf; border-radius: 10px 10px 0 10px; margin-top: 10px; align-self: flex-end; min-width: 200px; max-width: 80%;">
                        <div class="text-sm" style="color: #666; font-size: 12px">Me</div>
                        <div>{{$message->body}}</div>
                        <div style="color: #666; font-size: 12px; text-align: right">{{$message->created_at->diffForHumans()}}</div>
                    </div>
                    @else
                    <div style="border: 1px solid #ccc; padding: 10px; background-color: #cfc; border-radius: 10px 10px 0 10px; margin-top: 10px; align-self: flex-start; min-width: 200px; max-width: 80%;">
                        <div class="text-sm" style="color: #666; font-size: 12px">{{$message->sender->username}}</div>
                        <div>{{$message->body}}</div>
                        <div style="color: #666; font-size: 12px; text-align: right">{{$message->created_at->diffForHumans()}}</div>
                    </div>
                    @endif
                    @endforeach
                </div>
                @if($chatRoomId != '')
                <form action="{{route('chat.send-message')}}" method="POST">
                    @csrf
                    <input type="hidden" name="chat_room_id" value="{{$chatRoomId}}">
                    <div class="d-flex align-items-end" style="background-color: #f5f5f5">
                        <div class="flex-grow-1 mr-2">
                            <input type="text" class="form-control" placeholder="Type your message..." name="body">
                        </div>
                        <button class="btn btn-outline-primary" type="submit"><i class="mdi mdi-send"></i></button>
                        @if($chatRoom->is_group)
                        <div class="dropdown h-100">
                            <button class="btn btn-outline-primary dropdown-toggle h-100" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="?chatRoomId={{$chatRoomId}}&isViewingGroupDetails=1">View Group Details</a>
                            </div>
                        </div>
                        @endif
                    </div>
                </form>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    // scroll #chat-messages to bottom on page load
    $(document).ready(function() {
        $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
    });
</script>
@endpush