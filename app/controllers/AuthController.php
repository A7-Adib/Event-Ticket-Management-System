<?php

class AuthController extends Controller
{
    private User $users;

    public function __construct()
    {
        $this->users = new User(Database::connection());
    }

    public function login(): void
    {
        Auth::start();

        if (Auth::check()) {
            $this->redirect('');
        }

        $message = '';
        $type = 'error';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $pass = $_POST['password'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $pass === '') {
                $message = 'Please enter a valid email and password.';
            } else {
                $u = $this->users->findByEmail($email);

                if ($u && password_verify($pass, $u['password'])) {
                    Auth::login($u);
                    $this->redirect('');
                }

                $message = 'Invalid email or password.';
            }
        }

        $this->render('auth/login', compact('message', 'type'));
    }

    public function register(): void
    {
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $pass = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6 || $pass !== $confirm) {
                $message = 'Please provide valid details and matching passwords (minimum 6 characters).';
            } elseif ($this->users->emailExists($email)) {
                $message = 'This email is already registered.';
            } else {
                $this->users->create($name, $email, password_hash($pass, PASSWORD_DEFAULT), $phone);
                $this->flash('success', 'Registration successful. Please login.');
                $this->redirect('login');
            }
        }

        $this->render('auth/register', compact('message'));
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('login');
    }
}
?>