<?php

namespace App\Console\Commands;

use App\Models\FormAssignment;
use App\Models\Notification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendFeedbackReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feedback:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders to students for pending feedback forms approaching deadline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for forms requiring reminders...');
        
        $now = Carbon::now();
        $remindersSent = 0;

        // Get all pending assignments with end dates
        $assignments = FormAssignment::where('status', 'pending')
            ->whereNotNull('end_date')
            ->with(['student.user'])
            ->get();

        foreach ($assignments as $assignment) {
            // Skip if no end date or already ended
            if (!$assignment->end_date || $assignment->hasEnded()) {
                continue;
            }

            $effectiveEndDate = $assignment->end_date->copy()->addHours($assignment->grace_period_hours ?? 0);
            $hoursRemaining = $now->diffInHours($effectiveEndDate, false);

            // Send reminder at different intervals
            $shouldRemind = false;
            $reminderType = null;

            if ($hoursRemaining <= 2 && $hoursRemaining > 0) {
                // 2 hours before deadline
                $shouldRemind = !$this->hasRecentReminder($assignment, 'final', 2);
                $reminderType = 'final';
            } elseif ($hoursRemaining <= 24 && $hoursRemaining > 2) {
                // 1 day before deadline
                $shouldRemind = !$this->hasRecentReminder($assignment, 'day', 24);
                $reminderType = 'day';
            } elseif ($hoursRemaining <= 72 && $hoursRemaining > 24) {
                // 3 days before deadline
                $shouldRemind = !$this->hasRecentReminder($assignment, 'days', 72);
                $reminderType = 'days';
            }

            if ($shouldRemind && $assignment->student && $assignment->student->user) {
                $this->sendReminder($assignment, $reminderType, $hoursRemaining);
                $remindersSent++;
            }
        }

        // Check for forms starting soon
        $upcomingAssignments = FormAssignment::where('status', 'pending')
            ->whereNotNull('start_date')
            ->where('start_date', '>', $now)
            ->where('start_date', '<=', $now->copy()->addHours(24))
            ->with(['student.user'])
            ->get();

        foreach ($upcomingAssignments as $assignment) {
            if (!$this->hasRecentReminder($assignment, 'starting', 24)) {
                $this->sendStartingReminder($assignment);
                $remindersSent++;
            }
        }

        $this->info("Reminders sent: {$remindersSent}");
        return 0;
    }

    /**
     * Check if a reminder was recently sent to avoid duplicates.
     */
    private function hasRecentReminder($assignment, $type, $hours)
    {
        return Notification::where('user_id', $assignment->student->user_id)
            ->where('type', 'form_reminder')
            ->where('data', 'like', '%"reminder_type":"' . $type . '"%')
            ->where('data', 'like', '%"assignment_id":' . $assignment->id . '%')
            ->where('created_at', '>=', now()->subHours($hours))
            ->exists();
    }

    /**
     * Send a deadline reminder notification.
     */
    private function sendReminder($assignment, $type, $hoursRemaining)
    {
        $messages = [
            'final' => 'Only 2 hours left to submit!',
            'day' => 'Deadline is tomorrow!',
            'days' => '3 days remaining to submit.',
        ];

        $timeText = $this->formatTimeRemaining($hoursRemaining);

        Notification::create([
            'user_id' => $assignment->student->user_id,
            'type' => 'form_reminder',
            'title' => 'Feedback Form Reminder: ' . $assignment->form_title,
            'message' => "⏰ {$messages[$type]} You have {$timeText} to complete the form.",
            'data' => json_encode([
                'assignment_id' => $assignment->id,
                'form_name' => $assignment->form_name,
                'form_title' => $assignment->form_title,
                'deadline' => $assignment->end_date->toISOString(),
                'reminder_type' => $type,
                'hours_remaining' => $hoursRemaining,
            ]),
            'read_at' => null,
        ]);

        $this->line("  → Sent {$type} reminder to {$assignment->student->user->name}");
    }

    /**
     * Send a "form starting soon" notification.
     */
    private function sendStartingReminder($assignment)
    {
        $hoursUntilStart = now()->diffInHours($assignment->start_date, false);
        $timeText = $this->formatTimeRemaining($hoursUntilStart);

        Notification::create([
            'user_id' => $assignment->student->user_id,
            'type' => 'form_available',
            'title' => 'New Feedback Form Available Soon: ' . $assignment->form_title,
            'message' => "📋 A new feedback form will be available in {$timeText}. Please be ready to complete it.",
            'data' => json_encode([
                'assignment_id' => $assignment->id,
                'form_name' => $assignment->form_name,
                'form_title' => $assignment->form_title,
                'start_date' => $assignment->start_date->toISOString(),
                'end_date' => $assignment->end_date ? $assignment->end_date->toISOString() : null,
            ]),
            'read_at' => null,
        ]);

        $this->line("  → Sent 'starting soon' reminder to {$assignment->student->user->name}");
    }

    /**
     * Format time remaining in a human-readable way.
     */
    private function formatTimeRemaining($hours)
    {
        if ($hours < 1) {
            return 'less than 1 hour';
        } elseif ($hours < 24) {
            return round($hours) . ' hour' . (round($hours) > 1 ? 's' : '');
        } else {
            $days = floor($hours / 24);
            $remainingHours = $hours % 24;
            $text = $days . ' day' . ($days > 1 ? 's' : '');
            if ($remainingHours > 0) {
                $text .= ' and ' . round($remainingHours) . ' hour' . (round($remainingHours) > 1 ? 's' : '');
            }
            return $text;
        }
    }
}
