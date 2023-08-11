<?php

namespace App\Controllers;

use App\DB;
use App\Models\User;
use App\View;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class AdminController
{
    protected User $user;

    public function __construct()
    {
        $this->user = new User;


        $auth = $_SESSION['auth'] ?? null;
        $user = $this->user->findById($auth['id']);
        if (!$auth || $user['role'] != 'admin') {
            $_SESSION['msg'] = ['color' => 'danger', 'message' => 'Please Login First.'];
            header('Location: http://localhost:8000/login');
            exit;
        }
    }

    public function index()
    {
        $users = $this->user->getUsers();

//        var_dump($users);die;
        return View::make('admins.users', ['users' => $users ?? []]);
    }

    public function verifiyEmail()
    {
        if (!$id = $_POST['id']) {
            $msg = ['color' => 'danger', 'message' => 'Something wrong'];
            $_SESSION['msg'] = $msg;
            header('Location: http://localhost:8000/admin');
            exit;
        }

        try {
            DB::getInstace()->beginTransaction();

            $user = $this->user->findById($id);
            if (!$user->updateStatus('verified') || !$this->sendMail($user)) {
                throw new Exception('');
            }

            $_SESSION['msg'] = ['color' => 'success', 'message' => "Email Verification && Email sent successfully."];

            DB::getInstace()->commit();
        } catch (\Throwable) {
            $_SESSION['msg']  = ['color' => 'danger', 'message' => 'Something wrong'];
            DB::getInstace()->rollBack();
        }

        header('Location: http://localhost:8000/admin');
        exit;

    }

    public function destroy()
    {
        $id = $_POST['id'] ?? 0;

        $msg = '';
        if (!$this->user->delete($id)) {
            $msg = ['color' => 'danger', 'message' => 'Something wrong'];
        } else {
            $msg = ['color' => 'success', 'message' => 'item deleted successfully.'];
        }

        $_SESSION['msg'] = $msg;

        header('Location: http://localhost:8000/admin');
        exit;
    }

    public function sendMail($user)
    {
        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->SMTPAuth = true;

            $mail->Host = "smtp.gmail.com";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->Username = "";
            $mail->Password = "";

            $mail->setFrom('php@gmail.com', $this->user->findById($_SESSION['auth']['id'])->name);
            $mail->addAddress($user['email'], $user['name']);

            $mail->isHTML(true);

            $mail->Subject = 'Verified Email';

            $date = date('Y-m-d g:i a');
            $mail->Body = <<<Msg
        Hi <h2>{$user->name}</h2>,
        you Email is Verified,
        {$date}
Msg;

            $mail->send();
        } catch (\Throwable $e) {
            return false;
        }

        return true;
    }
}