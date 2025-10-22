<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
include './Layout/page_head.php';
$_SESSION['page_title'] = 'Experience';

include './db.php';

$toastMsg = '';
$toastType = '';

// Handle Add
if (isset($_POST['add_experience'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $company = $conn->real_escape_string($_POST['company']);
    $period = $conn->real_escape_string($_POST['period']);
    $description = $conn->real_escape_string($_POST['description']);
    if ($conn->query("INSERT INTO experience (title, company, period, description) VALUES ('$title', '$company', '$period', '$description')")) {
        $toastMsg = "Experience added successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to add experience.";
        $toastType = "danger";
    }
}
// Handle Edit
if (isset($_POST['edit_experience'])) {
    $id = intval($_POST['id']);
    $title = $conn->real_escape_string($_POST['title']);
    $company = $conn->real_escape_string($_POST['company']);
    $period = $conn->real_escape_string($_POST['period']);
    $description = $conn->real_escape_string($_POST['description']);
    if ($conn->query("UPDATE experience SET title='$title', company='$company', period='$period', description='$description' WHERE id=$id")) {
        $toastMsg = "Experience updated successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to update experience.";
        $toastType = "danger";
    }
}
// Handle Delete
if (isset($_POST['delete_experience'])) {
    $id = intval($_POST['id']);
    if ($conn->query("DELETE FROM experience WHERE id=$id")) {
        $toastMsg = "Experience deleted successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to delete experience.";
        $toastType = "danger";
    }
}

// Fetch experience data from DB
$experiences = [];
$result = $conn->query("SELECT * FROM experience ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $experiences[] = $row;
    }
}

$toastMsg = isset($toastMsg) ? $toastMsg : '';
$toastType = isset($toastType) ? $toastType : '';
?>
<link rel="stylesheet" href="./CSS/Layout.css">

<!-- Toast Alert -->
<div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
    <div id="loginToast" class="toast align-items-center text-bg-<?= $toastType ?> border-0<?php if($toastMsg) echo ' show'; ?>" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <?= htmlspecialchars($toastMsg) ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<div class="row vh-100">
    <?php include './Layout/sidebar.php' ?>
    <div class="col-lg-9">
        <?php include './Layout/topbar.php'; ?>

        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Experience</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Company</th>
                                <th>Period</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($experiences as $exp): ?>
                            <tr>
                                <td><?= htmlspecialchars($exp['title']) ?></td>
                                <td><?= htmlspecialchars($exp['company']) ?></td>
                                <td><?= htmlspecialchars($exp['period']) ?></td>
                                <td><?= htmlspecialchars($exp['description']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal" onclick="viewExp('<?= htmlspecialchars(addslashes($exp['title'])) ?>','<?= htmlspecialchars(addslashes($exp['company'])) ?>','<?= htmlspecialchars(addslashes($exp['period'])) ?>','<?= htmlspecialchars(addslashes($exp['description'])) ?>')">View</button>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editExp('<?= $exp['id'] ?>','<?= htmlspecialchars(addslashes($exp['title'])) ?>','<?= htmlspecialchars(addslashes($exp['company'])) ?>','<?= htmlspecialchars(addslashes($exp['period'])) ?>','<?= htmlspecialchars(addslashes($exp['description'])) ?>')">Edit</button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="deleteExp('<?= $exp['id'] ?>')">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Add Modal -->
            <div class="modal fade" id="addModal" tabindex="-1">
                <div class="modal-dialog">
                    <form class="modal-content" method="post" action="">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Experience</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Company</label>
                                <input type="text" name="company" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Period</label>
                                <input type="text" name="period" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="add_experience" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- View Modal -->
            <div class="modal fade" id="viewModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">View Experience</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2"><strong>Title:</strong> <span id="viewTitle"></span></div>
                            <div class="mb-2"><strong>Company:</strong> <span id="viewCompany"></span></div>
                            <div class="mb-2"><strong>Period:</strong> <span id="viewPeriod"></span></div>
                            <div class="mb-2"><strong>Description:</strong> <span id="viewDescription"></span></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1">
                <div class="modal-dialog">
                    <form class="modal-content" method="post" action="">
                        <input type="hidden" name="id" id="editId">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Experience</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" id="editTitle" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Company</label>
                                <input type="text" name="company" id="editCompany" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Period</label>
                                <input type="text" name="period" id="editPeriod" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="editDescription" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="edit_experience" class="btn btn-warning">Update</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1">
                <div class="modal-dialog">
                    <form class="modal-content" method="post" action="">
                        <input type="hidden" name="id" id="deleteId">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Experience</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this experience?
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="delete_experience" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var toastEl = document.getElementById('loginToast');
    if (toastEl && toastEl.classList.contains('show')) {
        var toast = new bootstrap.Toast(toastEl, { delay: 4000 });
        toast.show();
    }
});
</script>
<script>
function viewExp(title, company, period, description) {
    document.getElementById('viewTitle').textContent = title;
    document.getElementById('viewCompany').textContent = company;
    document.getElementById('viewPeriod').textContent = period;
    document.getElementById('viewDescription').textContent = description;
}
function editExp(id, title, company, period, description) {
    document.getElementById('editId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editCompany').value = company;
    document.getElementById('editPeriod').value = period;
    document.getElementById('editDescription').value = description;
}
function deleteExp(id) {
    document.getElementById('deleteId').value = id;
}
</script>
<?php $conn->close(); ?>
<?php include './Layout/page_fooder.php'; ?>
