<?php

namespace App\Console\Commands;

use App\Mail\TaskNotificationMail;
use App\Models\Notification;
use App\Models\Task;
use App\Models\TaskRequest;
use App\Models\TrainingRole;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon;

class TaskStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:task-status-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Tasks Request Status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Cron Job is running for every Minute
        // write logic Below

        $tasksRequests = TaskRequest::where('status', 1)->get();

        foreach ($tasksRequests as $tasksRequest) {
            if ($tasksRequest->display_status == 'Active') {
                $validity = Carbon\Carbon::parse($tasksRequest->valid_upto);
                $currentDate = Carbon\Carbon::now();
                $today = $currentDate->format('Y-m-d');
                $diffInDays = $validity->diffInDays($today);
                if ($diffInDays < 60) {
                    $tasksRequest->display_status = 'Upcoming';
                    $tasksRequest->filter_status = 'Upcoming';
                    $tasksRequest->save();
                }
            }

            if ($tasksRequest->display_status == 'Upcoming') {
                $validity = Carbon\Carbon::parse($tasksRequest->valid_upto);
                if ($validity->isPast()) {
                    $tasksRequest->status = 0;
                    $tasksRequest->approval_status = null;
                    $tasksRequest->display_status = 'Expired';
                    $tasksRequest->filter_status = 'Action Required';
                    $tasksRequest->save();
                }
            }
        }

        // Cron Job is running for every Minute
        // write logic Below  for Notifications

        $notifications = Notification::all();

        foreach ($notifications as $notification) {
            $sectors = $notification->notificationSectors;
            foreach ($sectors as $sector) {
                $mtasks = Task::where('sector_id', $sector->id)->get();
                foreach ($mtasks as $task) {
                    $task_reqs = TaskRequest::where('task_id', $task->id)->where('status', 1)->get();
                    foreach ($task_reqs as $task_req) {
                        $expiryDate = $task_req->valid_upto;
                        $this->sendOnTimeMail(
                            $notification,
                            $task_req,
                            $expiryDate,
                        );
                    }
                }
            }
        }
    }

    public function sendOnTimeMail($notification, $task_req, $expiryDate)
    {
        $today = Carbon\Carbon::today();
        $user = User::find($task_req->user_id);
        $task = Task::find($task_req->task_id);
        $startDays = $this->calculateDays($notification->start_number, $notification->start_period);
        $startScope = $notification->start_scope;
        $stopDays = $this->calculateDays($notification->stop_number, $notification->stop_period);
        $stopScope = $notification->stop_scope;
        $repeatAfter = $this->calculateDays($notification->repeat_number, $notification->repeat_period);

        if ($startScope == 'Before') {
            $startNotifyDate = Carbon\Carbon::parse($expiryDate)->subDays($startDays);
        } else {
            $startNotifyDate = Carbon\Carbon::parse($expiryDate)->addDays($startDays);
        }

        if ($stopScope == 'Before') {
            $stopNotifyDate = Carbon\Carbon::parse($expiryDate)->subDays($stopDays);
        } else {
            $stopNotifyDate = Carbon\Carbon::parse($expiryDate)->addDays($stopDays);
        }

        if ($startNotifyDate->eq($today)) {
            $message = 'First Mail';
            $taskNotification = new TaskNotificationMail($user, $notification, $task, $message);
            Mail::to($user->email)->send($taskNotification);
            // dd($user->id, 'Send First Mail');
        }

        $interval = $today->diffInDays($startNotifyDate);

        if ($interval % $repeatAfter == 0) {
            $message = 'Repeat Mail';
            $taskNotification = new TaskNotificationMail($user, $notification, $task, $message);
            Mail::to($user->email)->send($taskNotification);
            // dd($user->id, 'Send Repeat Mail');
        }

        if ($stopNotifyDate->eq($today)) {
            $message = 'Last Mail';
            $taskNotification = new TaskNotificationMail($user, $notification, $task, $message);
            Mail::to($user->email)->send($taskNotification);
            // dd($user->id, 'Send Last Mail');
        }
    }

    public function calculateDays($number, $period)
    {
        if ($period == 'Days') {
            return $number * 1;
        }

        if ($period == 'Weeks') {
            return $number * 7;
        }

        if ($period == 'Months') {
            return $number * 30;
        }

        if ($period == 'Years') {
            return $number * 365;
        }
    }
}
