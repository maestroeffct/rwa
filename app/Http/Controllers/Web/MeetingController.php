<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Meeting;
use App\Models\MeetingPackage;
use App\Models\MeetingTime;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentChannel;
use App\Models\ReserveMeeting;
use App\Models\Sale;
use App\Models\Setting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MeetingController extends Controller
{

    public function index(Request $request, $username)
    {
        $user = auth()->user();
        $instructor = User::query()->where('username', $username)->first();

        if (empty($user)) {
            return redirect('/login');
        }

        if (!empty($instructor)) {
            $times = [];
            $meeting = Meeting::query()->select('*', DB::raw('LEAST(online_group_amount, in_person_group_amount) as min_group_amount'))
                ->where('creator_id', $instructor->id)
                ->with([
                    'meetingTimes'
                ])
                ->first();

            if (!empty($meeting) and !empty($meeting->meetingTimes)) {
                $times = convertDayToNumber($meeting->meetingTimes->groupby('day_label')->toArray());
            }

            $data = [
                'pageTitle' => trans('public.book_a_meeting'),
                'instructor' => $instructor,
                'meeting' => $meeting,
                'times' => $times,
                'step' => 1,
            ];

            $data = array_merge($data, $this->getSelectedDateTimes($request, $meeting));

            if (!empty(getMeetingPackagesSettings("status")) and $meeting->enable_meeting_packages) {
                $data['userMeetingPackages'] = MeetingPackage::query()->where('creator_id', $instructor->id)
                    ->where('enable', true)
                    ->get();
            }


            return view('design_1.web.users.meeting.index', $data);
        }

        abort(404);
    }

    public function overview(Request $request, $username)
    {
        $user = auth()->user();
        $instructor = User::query()->where('username', $username)->first();

        if (empty($user)) {
            return redirect('/login');
        }

        if (!empty($instructor)) {
            $meeting = Meeting::query()->select('*', DB::raw('LEAST(online_group_amount, in_person_group_amount) as min_group_amount'))
                ->where('creator_id', $instructor->id)
                ->with([
                    'meetingTimes'
                ])
                ->first();

            $getSelectedDateTimes = $this->getSelectedDateTimes($request, $meeting);

            if (empty($getSelectedDateTimes['selectedDate']) or empty($getSelectedDateTimes['selectedTime'])) {
                return redirect($instructor->getMeetingReservationUrl());
            }

            $data = [
                'pageTitle' => trans('public.book_a_meeting'),
                'instructor' => $instructor,
                'meeting' => $meeting,
                'step' => 2,
            ];

            $data = array_merge($data, $getSelectedDateTimes);

            return view('design_1.web.users.meeting.index', $data);
        }

        abort(404);
    }

    private function getSelectedDateTimes(Request $request, $meeting)
    {
        $selectedDate = null;
        $selectedDateTimes = null;
        $selectedTime = null;

        $date = $request->get('date');
        $time = $request->get('time');

        if (!empty($date)) {
            $dayLabel = Carbon::createFromTimestamp($date, getTimezone())->format('l');

            if (!empty($dayLabel)) {
                $dayLabel = mb_strtolower($dayLabel);
                $selectedDate = $date;

                $selectedDateTimes = $meeting->meetingTimes()
                    ->where('day_label', $dayLabel)
                    ->get();

                foreach ($selectedDateTimes as $selectedDateTime) {
                    $can_reserve = true;

                    $reserveMeeting = ReserveMeeting::where('meeting_time_id', $selectedDateTime->id)
                        ->where('day', $date)
                        ->whereIn('status', ['pending', 'open'])
                        ->first();

                    if ($reserveMeeting && ($reserveMeeting->locked_at || $reserveMeeting->reserved_at)) {
                        $can_reserve = false;
                    }

                    /*if ($timestamp + $secondTime < time()) {
                        $can_reserve = false;
                    }*/

                    $selectedDateTime->can_reserve = $can_reserve;
                }
            }
        }

        if (!empty($time)) {
            $selectedTime = $meeting->meetingTimes()
                ->where('id', $time)
                ->first();
        }

        return [
            'selectedDate' => $selectedDate,
            'selectedTime' => $selectedTime,
            'selectedDateTimes' => $selectedDateTimes,
        ];
    }

    public function reserve(Request $request, $username)
    {
        $user = auth()->user();
        $instructor = User::query()->where('username', $username)->first();

        if (empty($user)) {
            return redirect('/login');
        }

        $meeting = Meeting::query()->select('*', DB::raw('LEAST(online_group_amount, in_person_group_amount) as min_group_amount'))
            ->where('creator_id', $instructor->id)
            ->with([
                'meetingTimes'
            ])
            ->first();


        $data = $request->all();
        $validator = Validator::make($data, [
            'meeting_type' => 'required|in:in_person,online',
            'time' => 'required|exists:meeting_times,id',
            'day' => 'required',
            'student_count' => $meeting->group_meeting ? 'required|numeric' : 'nullable',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!empty($instructor)) {

            if ($instructor->id == $user->id) {
                return response()->json([
                    'toast_alert' => [
                        'title' => trans('public.request_failed'),
                        'msg' => trans('update.cant_reserve_your_appointment'),
                    ]
                ], 422);
            }

            if (!empty($meeting) and !$meeting->disabled) {

                $timeId = $data['time'];
                $day = $data['day'];
                $day = str_replace('/', '-', $day);
                $studentCount = $data['student_count'] ?? 1;
                $selectedMeetingType = $data['meeting_type'];
                $description = $data['description'];

                $meetingTime = $meeting->meetingTimes()->where('id', $timeId)->first();

                if (!empty($meetingTime)) {
                    if (!empty($meeting->amount) and $meeting->amount > 0) {
                        $reserveMeeting = ReserveMeeting::where('meeting_time_id', $meetingTime->id)
                            ->where('day', $day)
                            ->first();

                        if (!empty($reserveMeeting) and $reserveMeeting->locked_at) {
                            return response()->json([
                                'toast_alert' => [
                                    'title' => trans('public.request_failed'),
                                    'msg' => trans('meeting.locked_time'),
                                ]
                            ], 422);
                        }

                        if (!empty($reserveMeeting) and $reserveMeeting->reserved_at) {
                            return response()->json([
                                'toast_alert' => [
                                    'title' => trans('public.request_failed'),
                                    'msg' => trans('meeting.reserved_time'),
                                ]
                            ], 422);
                        }

                        $hourlyAmountResult = $this->handleHourlyMeetingAmount($meeting, $meetingTime, $studentCount, $selectedMeetingType);

                        if (!$hourlyAmountResult['status']) {
                            return $hourlyAmountResult['result']; // json response
                        }

                        $hourlyAmount = $hourlyAmountResult['result'];

                        $explodeTime = explode('-', $meetingTime->time);

                        $hours = (strtotime($explodeTime[1]) - strtotime($explodeTime[0])) / 3600;

                        $instructorTimezone = $meeting->getTimezone();

                        $startAt = $this->handleUtcDate($day, $explodeTime[0], $instructorTimezone);
                        $endAt = $this->handleUtcDate($day, $explodeTime[1], $instructorTimezone);

                        $reserveMeeting = ReserveMeeting::updateOrCreate([
                            'user_id' => $user->id,
                            'meeting_time_id' => $meetingTime->id,
                            'meeting_id' => $meetingTime->meeting_id,
                            'status' => ReserveMeeting::$pending,
                            'day' => $day,
                            'meeting_type' => $selectedMeetingType,
                            'student_count' => $studentCount
                        ], [
                            'date' => strtotime($day),
                            'start_at' => $startAt,
                            'end_at' => $endAt,
                            'paid_amount' => (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0,
                            'discount' => $meetingTime->meeting->discount,
                            'description' => $description,
                            'created_at' => time(),
                        ]);

                        $cart = Cart::where('creator_id', $user->id)
                            ->where('reserve_meeting_id', $reserveMeeting->id)
                            ->first();

                        if (empty($cart)) {
                            Cart::create([
                                'creator_id' => $user->id,
                                'reserve_meeting_id' => $reserveMeeting->id,
                                'created_at' => time()
                            ]);
                        }

                        return response()->json([
                            'code' => 200,
                            'title' => trans('public.request_success'),
                            'msg' => trans('update.meeting_added_to_cart'),
                            'redirect_to' => '/cart'
                        ]);
                    } else {
                        return $this->handleFreeMeetingReservation($user, $meeting, $meetingTime, $day, $selectedMeetingType, $studentCount);
                    }
                } else {
                    return response()->json([
                        'toast_alert' => [
                            'title' => trans('public.request_failed'),
                            'msg' => trans('meeting.select_time_to_reserve'),
                        ]
                    ], 422);
                }
            }
        }

        return response()->json([
            'toast_alert' => [
                'title' => trans('public.request_failed'),
                'msg' => trans('public.not_login_toast_msg_lang'),
            ]
        ], 422);
    }

    public function getMeetingAmount(Request $request, $username)
    {
        $user = auth()->user();
        $instructor = User::query()->where('username', $username)->first();

        if (empty($user)) {
            return redirect('/login');
        }

        if (!empty($instructor)) {
            $meeting = Meeting::query()->select('*', DB::raw('LEAST(online_group_amount, in_person_group_amount) as min_group_amount'))
                ->where('creator_id', $instructor->id)
                ->with([
                    'meetingTimes'
                ])
                ->first();

            if (!empty($meeting)) {
                $timeId = $request->input('time');
                $studentCount = $request->get('student_count', 1);
                $selectedMeetingType = $request->get('meeting_type', 'online');

                $meetingTime = $meeting->meetingTimes()->where('id', $timeId)->first();

                if (!empty($meetingTime)) {
                    $hourlyAmountResult = $this->handleHourlyMeetingAmount($meeting, $meetingTime, $studentCount, $selectedMeetingType);

                    if (!$hourlyAmountResult['status']) {
                        return $hourlyAmountResult['result']; // json response
                    }

                    $hourlyAmount = $hourlyAmountResult['result'];
                    $explodeTime = explode('-', $meetingTime->time);
                    $hours = (strtotime($explodeTime[1]) - strtotime($explodeTime[0])) / 3600;

                    $amount = (!empty($hourlyAmount) and $hourlyAmount > 0) ? $hourlyAmount * $hours : 0;

                    return response()->json([
                        'code' => 200,
                        'amount' => handlePrice($amount),
                    ]);
                }
            }
        }

        return response()->json([], 422);
    }

    private function handleUtcDate($day, $clock, $instructorTimezone)
    {
        $date = $day . ' ' . $clock;

        $utcDate = convertTimeToUTCzone($date, $instructorTimezone);

        return $utcDate->getTimestamp();
    }

    private function handleHourlyMeetingAmount(Meeting $meeting, MeetingTime $meetingTime, $studentCount, $selectedMeetingType)
    {
        if (empty($studentCount)) {
            $studentCount = 1;
        }

        $status = true;
        $hourlyAmount = $meeting->amount;

        if ($selectedMeetingType == 'in_person' and in_array($meetingTime->meeting_type, ['in_person', 'all'])) {
            if ($meeting->in_person) {
                $hourlyAmount = $meeting->in_person_amount;
            } else {
                $status = false;

                $hourlyAmount = response()->json([
                    'toast_alert' => [
                        'title' => trans('public.request_failed'),
                        'msg' => trans('update.in_person_meetings_unavailable'),
                    ]
                ], 422);
            }
        }

        if ($meeting->group_meeting and $status) {
            $types = ['in_person', 'online'];

            foreach ($types as $type) {
                if ($selectedMeetingType == $type and in_array($meetingTime->meeting_type, ['all', $type])) {

                    $meetingMaxVar = $type . '_group_max_student';
                    $meetingMinVar = $type . '_group_min_student';
                    $meetingAmountVar = $type . '_group_amount';

                    if ($studentCount < $meeting->$meetingMinVar) {
                        $hourlyAmount = $hourlyAmount * $studentCount;
                    } else if ($studentCount > $meeting->$meetingMaxVar) {
                        $status = false;

                        $hourlyAmount = response()->json([
                            'toast_alert' => [
                                'title' => trans('public.request_failed'),
                                'msg' => trans('update.group_meeting_max_student_count_hint', ['max' => $meeting->$meetingMaxVar]),
                            ]
                        ], 422);

                    } else if ($studentCount >= $meeting->$meetingMinVar and $studentCount <= $meeting->$meetingMaxVar) {
                        $hourlyAmount = $meeting->$meetingAmountVar * $studentCount;
                    }
                }
            }
        }

        return [
            'status' => $status,
            'result' => $hourlyAmount
        ];
    }

    private function handleFreeMeetingReservation($user, $meeting, $meetingTime, $day, $selectedMeetingType, $studentCount)
    {
        $instructorTimezone = $meeting->getTimezone();
        $explodetime = explode('-', $meetingTime->time);

        $startAt = $this->handleUtcDate($day, $explodetime[0], $instructorTimezone);
        $endAt = $this->handleUtcDate($day, $explodetime[1], $instructorTimezone);

        $reserve = ReserveMeeting::updateOrCreate([
            'user_id' => $user->id,
            'meeting_time_id' => $meetingTime->id,
            'meeting_id' => $meetingTime->meeting_id,
            'status' => ReserveMeeting::$pending,
            'day' => $day,
            'meeting_type' => $selectedMeetingType,
            'student_count' => $studentCount
        ], [
            'date' => strtotime($day),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'paid_amount' => 0,
            'discount' => $meetingTime->meeting->discount,
            'created_at' => time(),
        ]);

        if (!empty($reserve)) {
            $sale = Sale::create([
                'buyer_id' => $user->id,
                'seller_id' => $meeting->creator_id,
                'meeting_id' => $meeting->id,
                'type' => Sale::$meeting,
                'payment_method' => Sale::$credit,
                'amount' => 0,
                'total_amount' => 0,
                'created_at' => time(),
            ]);

            if (!empty($sale)) {
                $reserve->update([
                    'sale_id' => $sale->id,
                    'reserved_at' => time()
                ]);
            }
        }


        return response()->json([
            'code' => 200,
            'title' => trans('public.request_success'),
            'msg' => trans('cart.success_pay_msg_for_free_meeting'),
        ]);
    }
}
