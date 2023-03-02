<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageAPIController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'ride_id' => 'required|integer',
            'last_message_datetime' => 'nullable|date',
        ]);

        try {
            $ride = Ride::find($request->ride_id);
            if (!isset($ride) || ($ride->user_id != Auth::user()->id && $ride->driver->user->id != Auth::user()->id && !Auth::user()->hasRole('admin'))) {
                return $this->sendError('Ride not found');
            }

            $messagesQuery = Message::where('ride_id', $ride->id);
            if (isset($request->last_message_datetime)) {
                $messagesQuery->where('sender_id', '!=', auth()->id())->where('created_at', '>', $request->last_message_datetime);
            }

            $messages = $messagesQuery->orderBy('id', 'asc')->get();

            return $this->sendResponse($messages, __('Messages retrieved successfully'));
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'ride_id' => 'required|integer',
            'message' => 'required_without:file|string',
            'file' => 'required_without:message|mimes:jpeg,bmp,png,gif,svg,pdf',
        ]);

        try {

            $ride = Ride::find($request->ride_id);
            if (!isset($ride) || ($ride->user_id != Auth::user()->id && $ride->driver->user->id != Auth::user()->id)) {
                return $this->sendError('Ride not found');
            } elseif (!in_array($ride->ride_status, ['accepted', 'in_progress'])) {
                return $this->sendError(__('Ride cannot receive new messages'), 403);
            }

            $message = Message::create(['ride_id' => $ride->id, 'sender_id' => Auth::user()->id, 'message' => $request->get('message')]);

            if (isset($request->file)) {
                $message->addMedia($request->file)->toMediaCollection('file');
            }

            return $this->sendResponse($message, __('Messages sended successfully'));
        } catch (\Exception $e) {
            report($e);
            return $this->sendError($e->getMessage());
        }
    }
}
