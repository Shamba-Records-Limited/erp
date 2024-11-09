@extends('layouts.app')

@push('plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
@endpush

@section('topItem')
@if($isAddingGroup == 1)
<div class="overlay">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-4 mx-auto card shadow-lg border-0 rounded">
                <div class="card-header text-center bg-gradient">
                    <h4><i class="fas fa-users"></i> Add Chat Group</h4>
                    <a class="btn btn-light btn-sm float-left" href="?">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('chat.add-group') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="group_name">Group Name</label>
                            <input type="text" class="form-control {{ $errors->has('group_name') ? 'is-invalid' : '' }}"
                                id="group_name" placeholder="Group Name" name="group_name"
                                value="{{ old('group_name', '') }}">
                            @if ($errors->group_name)
                            <div class="invalid-feedback">{{ $errors->first('group_name') }}</div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Create Group</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@elseif($isJoiningGroup == 1)
<!-- Join Chat Group Modal -->
<div class="overlay">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-4 mx-auto card shadow-lg border-0 rounded">
                <div class="card-header text-center bg-gradient">
                    <h4><i class="fas fa-user-plus"></i> Join Chat Group</h4>
                    <a class="btn btn-light btn-sm float-left" href="?">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('chat.search-group-to-join') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="group_name">Group Name</label>
                            <input type="text" class="form-control {{ $errors->has('group_name') ? 'is-invalid' : '' }}"
                                id="group_name" placeholder="Group Name" name="group_name"
                                value="{{ old('group_name', '') }}">
                            @if ($errors->group_name)
                            <div class="invalid-feedback">{{ $errors->first('group_name') }}</div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Join Group</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@elseif($isViewingGroupDetails == 1)
<!-- Group Details Modal -->
<div class="overlay">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-4 mx-auto card shadow-lg border-0 rounded">
                <div class="card-header text-center bg-gradient">
                    <h4><i class="fas fa-info-circle"></i> Group Details</h4>
                    <a class="btn btn-light btn-sm float-left" href="?">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Group Name:</strong>
                        <div class="lead">{{ $chatRoom->group_name }}</div>
                    </div>
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <div class="lead">{{ $chatRoom->description }}</div>
                    </div>
                    <hr />
                    <strong>Members</strong>
                    <div class="d-flex justify-content-end mb-3">
                        <a href="?chatRoomId={{ $chatRoom->id }}&isAddingGroupMember=1" class="btn btn-warning"><i
                                class="fas fa-user-plus"></i> Add Member</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupMembers as $member)
                                <tr>
                                    <td>{{ $member->user->username }}</td>
                                    <td>{{ $member->user->email }}</td>
                                    <td>{{ $member->user->getRoleNames()[0] }}</td>
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
<!-- Add Group Member Modal -->
<div class="overlay">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-4 mx-auto card shadow-lg border-0 rounded">
                <div class="card-header text-center bg-gradient">
                    <h4><i class="fas fa-user-plus"></i> Add Group Member</h4>
                    <a class="btn btn-light btn-sm float-left"
                        href="?chatRoomId={{ $chatRoom->id }}&isViewingGroupDetails=1">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('chat.add-group-member') }}" method="POST">
                        @csrf
                        <input type="hidden" name="chat_room_id" value="{{ $chatRoom->id }}">
                        <div class="form-group">
                            <label for="identification_type">Identification Type</label>
                            <select class="form-control" name="identification_type" id="identification_type">
                                <option value="username" @if(old("identification_type", '' )=="username" ) selected
                                    @endif>Username</option>
                                <option value="email" @if(old("identification_type", '' )=="email" ) selected @endif>
                                    Email</option>
                            </select>
                            @if($errors->has("identification_type"))
                            <div class="text-danger">{{ $errors->first("identification_type") }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="identification_value">Identification Value</label>
                            <input type="text" class="form-control" name="identification_value"
                                id="identification_value" value="{{ old('identification_value', '') }}">
                            @if($errors->has('identification_value'))
                            <div class="text-danger">{{ $errors->first('identification_value') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="is_admin" class="mr-2">Is Admin?</label>
                            <input type="checkbox" name="is_admin" id="is_admin">
                        </div>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-warning"><i class="fas fa-user-plus"></i> Add
                                Member</button>
                            <a href="?chatRoomId={{ $chatRoom->id }}&isViewingGroupDetails=1"
                                class="btn btn-secondary ml-2"><i class="fas fa-arrow-left"></i> Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@elseif($isAddingChat == 1)
<!-- Add Chat Modal -->
<div class="overlay">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-4 mx-auto card shadow-lg border-0 rounded">
                <div class="card-header text-center bg-gradient">
                    <h4><i class="fas fa-comment-dots"></i> Add Chat</h4>
                    <a class="btn btn-light btn-sm float-left" href="?">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('chat.add-chat') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="identification_type">User Identification Type</label>
                            <select class="form-control" name="identification_type" id="identification_type">
                                <option value="username" @if(old("identification_type", '' )=="username" ) selected
                                    @endif>Username</option>
                                <option value="email" @if(old("identification_type", '' )=="email" ) selected @endif>
                                    Email</option>
                            </select>
                            @if($errors->has("identification_type"))
                            <div class="text-danger">{{ $errors->first("identification_type") }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="identification_value">User Identification Value</label>
                            <input type="text" class="form-control" name="identification_value"
                                id="identification_value" value="{{ old('identification_value', '') }}">
                            @if($errors->has('identification_value'))
                            <div class="text-danger">{{ $errors->first('identification_value') }}</div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Start Chat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('content')
<div class="container my-5">
    <div class="card shadow-lg" style="border-radius: 15px;">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 sidebar-info">
                    <h5 class="text-center">Chat Rooms</h5>
                    <div class="d-flex justify-content-end mb-3">
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle " type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"><span class="mr-2">Add Chat</span>
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="?isAddingChat=1"><i class="fas fa-comment"></i> Add
                                    Chat</a>
                                <a class="dropdown-item" href="?isAddingGroup=1"><i class="fas fa-users"></i> Add Group
                                    Chat</a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Search..." aria-label="Search">
                    </div>
                    <div class="chat-room-list">
                        @foreach($myChatRooms as $key => $room)
                        <a href="?chatRoomId={{ $room->id }}" class="text-dark">
                            <div class="chat-room-item @if($chatRoomId == $room->id) active @endif">
                                <div><i class="fas fa-comments"></i> {{ $room->room_name }}</div>
                                @if($room->unread > 0)
                                <div class="badge badge-primary">{{ $room->unread }}</div>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-8 display-message-section" style="padding: 20px;">
                    <div class="border border-primary p-3 mb-1 bg-light rounded text-center">
                        @if($chatRoomId == '')
                        <h5>Select a chat room</h5>
                        @else
                        <h5>{{ $chatRoom->room_name }}</h5>
                        @endif
                    </div>
                    <div id="chat-messages" class="p-2 chat-messages">
                        @foreach($chatMessages as $key => $message)
                        @if(Auth::id() == $message->sender_id)
                        <div class="message me">
                            <div class="text-sm"><strong>Me</strong></div>
                            <div>{{ $message->body }}</div>
                         
                            <div class="message-time">{{ $message->created_at->diffForHumans() }}</div>
                        </div>
                        @else
                        <div class="message other">
                            <div class="text-sm"><strong>{{ $message->sender->username }}</strong></div>
                            <div>{{ $message->body }}</div>
                          
                            <div class="message-time">{{ $message->created_at->diffForHumans() }}</div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @if($chatRoomId != '')
                    <form action="{{ route('chat.send-message') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="chat_room_id" value="{{ $chatRoomId }}">
                        <div class="d-flex align-items-end">
                            <input type="text" class="form-control" placeholder="Type your message..." name="body"
                                required>
                            <button class="btn btn-outline-primary ml-2" type="submit"><i
                                    class="fas fa-paper-plane"></i></button>
                            @if($chatRoom->is_group)
                            <div class="dropdown ml-2">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">View Group Details
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item"
                                        href="?chatRoomId={{ $chatRoomId }}&isViewingGroupDetails=1"><i
                                            class="fas fa-info-circle"></i> View Group Details</a>
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
</div>
@endsection

@push('custom-scripts')
<script>
$(document).ready(function() {
    $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
});
</script>
@endpush

<style>
body {
    background-color: #f0f2f5;
    /* Lighter background for better contrast */
    font-family: 'Arial', sans-serif;
}

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1050;
}

.bg-gradient {
    background: linear-gradient(90deg, rgba(99, 139, 233, 1) 0%, rgba(66, 153, 225, 1) 100%);
    color: white;
    border-radius: 10px;
    /* Rounded corners for the header */
}

.chat-room-item {
    border: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 10px;
    /* More rounded corners */
    transition: background-color 0.3s ease, transform 0.2s ease;
    /* Added transform for hover */
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f8f9fa;
    /* Light background for chat room items */

}

.chat-room-item:hover {
    background-color: #e7f1ff;
    transform: scale(1.02);
    /* Slightly enlarge on hover */
}

.message {
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 10px;
    margin-top: 10px;
    min-width: 200px;
    max-width: 80%;
    display: flex;
    flex-direction: column;
    transition: background-color 0.3s ease;
    /* Smooth background transition */
}

.message.me {
    background-color: #cce5ff;
    align-self: flex-end;
}

.message.other {
    background-color: #d4edda;
    align-self: flex-start;
}

.message-time {
    font-size: 12px;
    color: #666;
    text-align: right;
}

.chat-messages {
    height: 400px;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    border: 1px solid #ddd;
    /* Added border for chat area */
    border-radius: 10px;
    /* Rounded corners */
    padding: 10px;
    /* Padding inside chat area */
}

.btn {
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn:hover {
    transform: scale(1.05);
    background-color: #0056b3;
    /* Darker background on hover */
    color: white;
    /* White text on hover */
}

.badge {
    position: relative;
    top: -5px;
}

/* New styles for sidebar and message display */
.sidebar-info {
    background-color: #f8f9fa;
    /* Light background for sidebar */
    border-right: 1px solid #ddd;
    /* Right border for separation */
    padding: 20px;
    /* Padding for better spacing */
    border-radius: 15px 0 0 15px;
    /* Rounded corners */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    /* Subtle shadow */
}

.display-message-section {
    padding: 20px;
    /* Padding for better spacing */
    background-color: #ffffff;
    /* White background for message area */
    border-radius: 0 15px 15px 0;
    /* Rounded corners */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    /* Subtle shadow */
}

.chat-room-list a {
    text-decoration: none;
    /* Remove underline from links */
}

.chat-room-list a:hover {
    background-color: #e7f1ff;
    /* Highlight on hover */
    border-radius: 5px;
    /* Rounded corners on hover */
}
</style>