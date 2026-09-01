<?php 
$title = 'User Management';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-header">
        <h1>User Management</h1>
    </div>

    <div class="table-wrapper">
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php while ($u = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $u['user_id'] ?></td>
                    <td><?= e($u['name']) ?></td>
                    <td><?= e($u['email']) ?></td>
                    <td><?= e($u['role']) ?></td>
                    <td>
                        <a href="<?= url('admin/edit/' . $u['user_id']) ?>">Edit</a>
                        <?php if ($u['user_id'] != Auth::id()): ?>
                            <a class="link-delete" onclick="return confirm('Delete this user and related data?')" href="<?= url('admin/delete/' . $u['user_id']) ?>">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>