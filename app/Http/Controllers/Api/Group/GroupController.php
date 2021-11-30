<?php

namespace App\Http\Controllers\Api\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddMemberToGroupRequest;
use App\Http\Requests\GroupRequest;
use App\Http\Requests\RemoveMembersFromGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // trả về các group theo post_id mà người dùng đk
        try {
            // khởi tạo mảng để lưu group
            $arr = [];
            //tìm mấy cái tkb chứa lz auth
            $schedules = Schedule::where('user_id', auth()->user()->id)->get();
            foreach ($schedules as $schedule) {
                // tìm lz group theo lz schedule xong bỏ bô mảng
                $group = Group::where('post_id', $schedule->post_id)->first();
                //kiểm tra mảng có group chưa
                if ($group) {
                    if (!in_array($group, $arr)) {
                        array_push($arr, $group);
                    }
                }
            }

            return $this->sendResponse($arr, 'fetch all groups data by user successfully');
        } catch (\Throwable $th) {
            return $this->sendError([], $th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupRequest $request)
    {
        try {
            $group = Group::create([
                'name' => $request['name'],
                'post_id' => $request['post_id'],
                'wb_id' => Uuid::uuid4()->toString(),
                'user_id' => auth()->user()->id
            ]);
            return $this->sendResponse($group, 'create group successfully');
        } catch (\Throwable $th) {
            return $this->sendError([], $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            // Substitute your Twilio Account SID and API Key details
            $accountSid = env('TWILIO_ACCOUNT_SID');
            $apiKeySid = env('TWILIO_API_KEY');
            $apiKeySecret = env('TWILIO_API_SECRET');

            $group = Group::where('id', $id)->first();
            $user = $request->user();
            dd($group->group_user);
            // if (in_array($user->id, $group->group_user->))
            $identity = $user->last_name . $user->id;
            $user_name = $user->first_name . ' ' .  $user->last_name;
            $group_name = $group->name;

            // Create an Access Token
            $token = new AccessToken(
                $accountSid,
                $apiKeySid,
                $apiKeySecret,
                3600,
                $identity,
            );

            // Grant access to Video
            $grant = new VideoGrant();
            $grant->setRoom($group_name);
            $token->addGrant($grant);

            // Serialize the token as a JWT
            $result = [
                "member_id" => $user->id,
                "user_name" => $user_name,
                "group_name" => $group_name,
                'wb_id' => $group->wb_id,
                'post' => $group->post,
                "token" => $token->toJWT()
            ];
            return $this->sendResponse($result, 'show group successfully');
        } catch (\Throwable $th) {
            return $this->sendError([], $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGroupRequest $request, $id)
    {
        try {
            if ($request->validator->fails()) {
                return $this->sendError('Validation error.', $request->validator->messages(), 412);
            }

            $group = Group::where([
                ['id', $id],
                ['user_id', auth()->user()->id]
            ])->first();
            // check access permission
            if ($group) {
                $group->update([
                    'name' => $request['name'],
                ]);
                return $this->sendResponse($group, 'update group successfully.');
            } else {
                return $this->sendError([], 'update group failed.', 403);
            }
        } catch (\Throwable $th) {
            return $this->sendError([], $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $group = Group::where([
                ['id', $id],
                ['owner_id', auth()->user()->id]
            ])->first();
            if ($group) {
                $group->delete();
                return $this->sendResponse(true, 'update group successfully.');
            } else {
                return $this->sendError([], 'delete group failed', 403);
            }
        } catch (\Throwable $th) {
            return $this->sendError([], $th->getMessage());
        }
    }

    public function addMemberToGroup(AddMemberToGroupRequest $request)
    {
        try {
            if ($request->validator->fails()) {
                return $this->sendError('Validation error.', $request->validator->messages(), 412);
            }
            $memberArr = [];
            $groupId = $request['group_id'];
            $owner_id = Group::find($groupId)->owner_id;
            if ($owner_id == auth()->user()->id) {
                $memberIds = json_decode($request['members'], 1);
                foreach ($memberIds as $memberId) {
                    GroupUser::create([
                        'group_id' => $request['group_id'],
                        'user_id' => $memberId
                    ]);
                    $user = User::find($memberId);
                    array_push($memberArr, $user);
                }
                $group = Group::find($groupId);
                $respone = [
                    'group' => $group,
                    'add_members' => $memberArr
                ];
                return $this->sendResponse($respone, 'add members to group successfully');
            } else {
                return $this->sendError([], "you can't add members to group failed", 403);
            }
        } catch (\Throwable $th) {
            return $this->sendError([], $th->getMessage());
        }
    }

    public function removeMemberFromGroup(RemoveMembersFromGroupRequest $request)
    {
        try {
            if ($request->validator->fails()) {
                return $this->sendError('Validation error.', $request->validator->messages(), 412);
            }
            $groupId = $request['group_id'];
            $owner_id = Group::find($groupId)->owner_id;
            if ($owner_id == auth()->user()->id) {
                $memberIds = json_decode($request['members'], 1);
                foreach ($memberIds as $memberId) {
                    $groupUser = GroupUser::where([
                        ['group_id', $groupId],
                        ['user_id', $memberId]
                    ])->first();
                    $groupUser->delete();
                }
                return $this->sendResponse(true, 'remove members from group successfully');
            } else {
                return $this->sendError([], 'remove members from group successfully', 403);
            }
        } catch (\Throwable $th) {
            return $this->sendError([], $th->getMessage());
        }
    }
}
