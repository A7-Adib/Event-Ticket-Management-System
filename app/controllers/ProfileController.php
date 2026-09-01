<?php

class ProfileController extends Controller
{
    private User $users;

    public function __construct()
    {
        $this->users = new User(Database::connection());
    }

    public function show(): void
    {
        Auth::requireLogin();
        $user = $this->users->find(Auth::id());
        $this->render('profile/show', compact('user'));
    }

    public function update(): void
    {
        Auth::requireLogin();
        $user = $this->users->find(Auth::id());
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');

            if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = 'Please enter valid name and email.';
            } elseif ($this->users->emailExists($email, Auth::id())) {
                $message = 'Email already in use.';
            } else {
                $this->users->update(Auth::id(), $name, $email, $phone, $user['role']);
                Auth::start();
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $this->flash('success', 'Profile updated.');
                $this->redirect('profile');
            }
        }

        $this->render('profile/update', compact('user', 'message'));
    }
}
?>