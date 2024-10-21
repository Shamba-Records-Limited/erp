<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\ChatRoom;
use App\ChatRoomUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user_id = Auth::id();

        $chatRoomId = $request->query('chatRoomId');
        $isAddingConversation = $request->query('isAddingConversation', "0");
        $isAddingGroup = $request->query('isAddingGroup', "0");
        $isJoiningGroup = $request->query('isJoiningGroup', "0");
        $isViewingGroupDetails = $request->query('isViewingGroupDetails', "0");
        $isAddingGroupMember = $request->query('isAddingGroupMember', "0");
        $isAddingChat = $request->query('isAddingChat', "0");
        
        $rawMyChatRooms = DB::select(DB::raw("
            SELECT cr.* FROM chat_room_user cru
            INNER JOIN chat_rooms cr ON cru.chat_room_id = cr.id
            WHERE cru.user_id = :user_id
        "), ['user_id' => $user_id]);

        $myChatRooms = ChatRoom::hydrate($rawMyChatRooms);

        $chatRoom = null;
        $chatMessages = [];
        if ($chatRoomId) {
            // update last read
            ChatRoomUser::where('user_id', $user_id)
                ->where('chat_room_id', $chatRoomId)
                ->update(['last_read_at' => now()]);

            // retrieve room
            $chatRoom = ChatRoom::find($chatRoomId);

            // todo: retrieve messages
            $chatMessages = ChatMessage::where('chat_room_id', $chatRoomId)->orderBy('created_at')->get();
        }

        $groupDetails = null;
        $groupMembers = [];
        if($isViewingGroupDetails == "1") {
            $groupMembers = ChatRoomUser::where('chat_room_id', $chatRoomId)->get();
        }

        return view('pages.chat.index', compact('chatRoomId', 'chatRoom', 'myChatRooms', 'chatMessages', 'isAddingConversation', 'isAddingGroup', 'isJoiningGroup', 'isViewingGroupDetails', 'groupMembers', 'isAddingGroupMember', 'isAddingChat'));
    }

    private function generateRandomAlphaNumericString($l = 10)
    {
        $possibleChars = 'abcdefghijklmnopqrstuvwxyz0123456789';

        $randomAlphaNumericString = '';
        for ($i = 0; $i < $l; $i++) {
            $randomIndex = rand(0, strlen($possibleChars) - 1);
            $randomAlphaNumericString .= $possibleChars[$randomIndex];
        }

        return $randomAlphaNumericString . '';
    }

    public function add_group(Request $request)
    {
        $user_id = Auth::id();

        $request->validate([
            'group_name' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // generate random group code
            $group_code = $this->generateRandomAlphaNumericString();
            while (true) {
                $room_exists = ChatRoom::where('group_code', $group_code)->exists();
                if ($room_exists) {
                    $group_code = $this->generateRandomAlphaNumericString();
                    continue;
                }

                break;
            }

            // generate random group password
            $raw_group_password = $this->generateRandomAlphaNumericString();

            // create chat room
            $chatRoom = new ChatRoom();
            $chatRoom->is_group = 1;
            $chatRoom->group_name = $request->group_name;
            $chatRoom->group_code = $group_code;
            $chatRoom->group_password = $raw_group_password;
            $chatRoom->created_by = $user_id;
            $chatRoom->save();

            // add user to chat group
            $chatRoomUser = new ChatRoomUser();
            $chatRoomUser->chat_room_id = $chatRoom->id;
            $chatRoomUser->user_id = $user_id;
            $chatRoomUser->is_admin = 1;
            $chatRoomUser->save();
            
            DB::commit();
            toastr()->success('Chat group added successfully');
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Oops! Error occurred');
            return redirect()->back();
           
        }

        return redirect()->back();
    }

    public function send_message(Request $request)
    {
        $user_id = Auth::id();

        $request->validate([
            "chat_room_id" => "required",
            "body" => "required",
        ]);

        DB::beginTransaction();
        try {
            $msg = new ChatMessage();
            $msg->chat_room_id = $request->chat_room_id;
            $msg->sender_id = $user_id;
            $msg->body = $request->body;
            $msg->save();

            DB::commit();
            toastr()->success('Message sent successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    public function add_group_member(Request $request)
    {
        $request->validate([
            "chat_room_id" => "required",
            "identification_type" => "required",
            "identification_value" => "required",
        ]);

        DB::beginTransaction();
        try {
            $user = null;
            if ($request->identification_type == "email") {
                $user = User::where('email', $request->identification_value)->first();
            } else if ($request->identification_type == "username") {
                $user = User::where('username', $request->identification_value)->first();
            } else {
                toastr()->error('Invalid identification type');
                return redirect()->back()->withInput();
            }

            if (!$user) {
                toastr()->error('User not found');
                return redirect()->back()->withInput();
            }

            // check if user is already in group
            $userInGroup = ChatRoomUser::where('chat_room_id', $request->chat_room_id)
                ->where('user_id', $user->id)
                ->first();

            if ($userInGroup) {
                toastr()->error('User is already in group');
                return redirect()->back()->withInput();
            }


            // add user to group
            $chatRoomUser = new ChatRoomUser();
            $chatRoomUser->chat_room_id = $request->chat_room_id;
            $chatRoomUser->user_id = $user->id;
            $chatRoomUser->is_admin = $request->is_admin == 'on';
            $chatRoomUser->is_admited = true;
            $chatRoomUser->save();

            DB::commit();
            toastr()->success('Group member added successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error('Oops! Error occurred');
            return redirect()->back()->withInput();
        }
        
    }

    public function add_chat(Request $request)
    {
        $user_id = Auth::id();

        $request->validate([
            "identification_type" => "required",
            "identification_value" => "required"
        ]);

        DB::beginTransaction();
        try {
            // check if user exists
            $partner_user = null;
            if ($request->identification_type == "email") {
                $partner_user = User::where('email', $request->identification_value)->first();
            } else if ($request->identification_type == "username") {
                $partner_user = User::where('username', $request->identification_value)->first();
            } else {
                toastr()->error('Invalid identification type');
                return redirect()->back()->withInput();
            }

            if (!$partner_user){
                toastr()->error('User not found');
                return redirect()->back()->withInput();
            }

            // check uniqueness
            $areSiblings = DB::select(DB::raw("SELECT count(1) AS are_siblings FROM chat_room_user cru1 
                JOIN chat_room_user cru2 ON cru1.chat_room_id = cru2.chat_room_id 
                JOIN chat_rooms cr ON cru1.chat_room_id = cr.id 
                WHERE cru1.user_id = :user_id AND cru2.user_id = :partner_id AND cr.is_group = 0
            "), [
                'user_id' => $user_id,
                'partner_id' => $partner_user->id
            ])[0]->are_siblings;

            if( $areSiblings > 0 ){
                toastr()->error('You are already chatting with this user');
                return redirect()->back()->withInput();
            };

            // create chat room
            $chatRoom = new ChatRoom();
            $chatRoom->created_by = $user_id;
            $chatRoom->save();
            
            // add user to chat group
            $chatRoomUser = new ChatRoomUser();
            $chatRoomUser->chat_room_id = $chatRoom->id;
            $chatRoomUser->user_id = $user_id;
            $chatRoomUser->save();
            
            // add partner to chat group
            $partnerRoomUser = new ChatRoomUser();
            $partnerRoomUser->chat_room_id = $chatRoom->id;
            $partnerRoomUser->user_id = $partner_user->id;
            $partnerRoomUser->save();

            DB::commit();
            toastr()->success('Chat room created successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }
}