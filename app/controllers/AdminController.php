<?php
class AdminController extends Controller
{
    private User $users;

    public function __construct()
    {
        $this->users = new User(Database::connection());
    }

    public function index(): void
    {
        Auth::requireRole(['admin']);
        $db = Database::connection();
        $counts = [];

        foreach (['users', 'events', 'registrations', 'tickets', 'check_in'] as $t) {
            $counts[$t] = (int)$db->query("SELECT COUNT(*) c FROM `$t`")->fetch_assoc()['c'];
        }

        $this->render('admin/index', compact('counts'));
    }

    public function users(): void
    {
        Auth::requireRole(['admin']);
        $result = $this->users->all();
        $this->render('admin/users', compact('result'));
    }

    public function edit(): void
    {
        Auth::requireRole(['admin']);
        $id = (int)($_GET['id'] ?? $_POST['user_id'] ?? 0);
        $user = $this->users->find($id);

        if (!$user) {
            exit('User not found.');
        }

        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $role = $_POST['role'] ?? 'Participant';

            if ($this->users->emailExists($email, $id)) {
                $message = 'Email already in use.';
            } else {
                $this->users->update($id, $name, $email, $phone, $role);
                $this->redirect('admin/users');
            }
        }

        $this->render('admin/edit_user', compact('user', 'message'));
    }

    public function delete(): void
    {
        Auth::requireRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);

        if ($id === Auth::id()) {
            exit('You cannot delete your own account.');
        }

        $this->users->delete($id);
        $this->redirect('admin/users');
    }
}
?>