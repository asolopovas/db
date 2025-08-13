<?php

namespace App\Helpers;

use App\Exceptions\IncorrectEmailException;
use Illuminate\Mail\PendingMail;
use Illuminate\Support\Facades\Mail;

trait MailerSetup
{

    /**
     * @param        $emails
     * @param string $loc
     *
     * @throws IncorrectEmailException
     */
    public function validateEmails($emails, $loc = 'unknown'): void
    {
        if (is_array($emails)) {
            $errors = array_filter(
                $emails,
                function ($mail) {
                    return $mail && !filter_var($mail, FILTER_VALIDATE_EMAIL);
                }
            );
        } else {
            $errors = [];
            if (!filter_var($emails, FILTER_VALIDATE_EMAIL))
                $errors[] = $emails;
        }
        if (!empty($errors))
            throw new IncorrectEmailException($errors, $loc);
    }

    /**
     * @param $data
     *
     * @return object
     * @throws IncorrectEmailException
     */
    public function prepareOrderMailAddresses($data): object
    {
        $customer = $data['customer'];

        $emails = [
            'to'  => $customer->email1,
            'cc'  => array_filter(merge($data['cc'], $customer->email2, $customer->email3)),
            'bcc' => ['info@3oak.co.uk'],
        ];

        foreach ($emails as $location => $value) {
            $this->validateEmails($value, $location);
        }

        return (object)$emails;
    }

    /**
     * @param $orderData
     *
     * @return PendingMail
     * @throws IncorrectEmailException
     */
    public function orderMailer($orderData): PendingMail
    {
        if (env('APP_ENV') === 'production') {
            $mails = $this->prepareOrderMailAddresses($orderData);
            $mailer = Mail::to($mails->to)->cc($mails->cc);
            $mailer->bcc($mails->bcc);

            return $mailer;
        }

        return Mail::to('info@mail.dev');
    }

    /**
     * @return PendingMail
     */
    public function internalMailer(): PendingMail
    {
        if (env('APP_ENV') === 'production') {
            $mailer = Mail::to('info@3oak.co.uk');
        } else {
            $mailer = Mail::to('info@mail.dev');
        }
        return $mailer;
    }
}
