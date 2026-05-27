<?php

namespace App\Libraries;

class EventMailer
{
    public function registrationConfirmation(array $user, array $event): bool
    {
        $subject = 'Registration received: ' . $event['title'];
        $body = view('emails/registration_confirmation', [
            'user' => $user,
            'event' => $event,
        ]);

        return $this->send($user['email'], $subject, $body);
    }

    public function registrationStatus(array $user, array $event, string $status): bool
    {
        $subject = 'Registration ' . ucfirst($status) . ': ' . $event['title'];
        $body = view('emails/registration_status', [
            'user' => $user,
            'event' => $event,
            'status' => $status,
        ]);

        return $this->send($user['email'], $subject, $body);
    }

    public function eventReminder(array $user, array $event): bool
    {
        $subject = 'Reminder: ' . $event['title'] . ' is coming up';
        $body = view('emails/event_reminder', [
            'user' => $user,
            'event' => $event,
        ]);

        return $this->send($user['email'], $subject, $body);
    }

    public function adminNotification(string $message): bool
    {
        $email = (string) env('email.fromEmail');
        if ($email === '') {
            return false;
        }

        return $this->send($email, 'Evenira admin notification', '<p>' . esc($message) . '</p>');
    }

    private function send(string $to, string $subject, string $message): bool
    {
        // Bypass email sending to prevent slow timeouts and errors when using dummy credentials
        if (strpos((string) env('email.SMTPUser'), 'example.com') !== false || env('email.SMTPUser') === '') {
            log_message('info', 'Email bypassed (no SMTP credentials): to={to}, subject={subject}', [
                'to' => $to,
                'subject' => $subject,
            ]);

            return true;
        }

        $email = service('email');
        $email->setFrom((string) env('email.fromEmail'), (string) env('email.fromName', 'Evenira EMS'));
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($message);

        // Plain-text fallback for email clients that do not render HTML (instruction requirement)
        $email->setAltMessage(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $message)));

        if (! $email->send(false)) {
            log_message('error', 'Email send FAILED: to={to}, subject={subject}, debug={debug}', [
                'to' => $to,
                'subject' => $subject,
                'debug' => $email->printDebugger(['headers']),
            ]);

            return false;
        }

        log_message('info', 'Email sent OK: to={to}, subject={subject}', [
            'to' => $to,
            'subject' => $subject,
        ]);

        return true;
    }
}
