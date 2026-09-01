<?php

class AnnouncementController extends Controller
{
    public function index(): void
    {
        $m = new Announcement(Database::connection());
        $result = $m->all();
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Auth::requireRole(['admin', 'organizer']);
            $title = trim($_POST['title'] ?? '');
            $body = trim($_POST['message'] ?? '');

            if ($title === '' || $body === '') {
                $message = 'Title and message are required.';
            } else {
                $m->create($title, $body, Auth::id());
                $this->redirect('announcements');
            }
        }

        $role = Auth::role();
        $this->render('announcements/index', compact('result', 'message', 'role'));
    }
}  
?>