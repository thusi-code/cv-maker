<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
include './Layout/page_head.php';
$_SESSION['page_title'] = 'Personal Details';
include './db.php';

$toastMsg = '';
$toastType = '';

// Handle Add
if (isset($_POST['add_personal'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $location = $conn->real_escape_string($_POST['location']);
    if ($conn->query("INSERT INTO personal_details (name, email, phone, location) VALUES ('$name', '$email', '$phone', '$location')")) {
        $toastMsg = "Personal detail added successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to add personal detail.";
        $toastType = "danger";
    }
}
// Handle Edit
if (isset($_POST['edit_personal'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $location = $conn->real_escape_string($_POST['location']);
    if ($conn->query("UPDATE personal_details SET name='$name', email='$email', phone='$phone', location='$location' WHERE id=$id")) {
        $toastMsg = "Personal detail updated successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to update personal detail.";
        $toastType = "danger";
    }
}
// Handle Delete
if (isset($_POST['delete_personal'])) {
    $id = intval($_POST['id']);
    if ($conn->query("DELETE FROM personal_details WHERE id=$id")) {
        $toastMsg = "Personal detail deleted successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to delete personal detail.";
        $toastType = "danger";
    }
}

// Fetch from DB
$personalDetails = [];
$result = $conn->query("SELECT * FROM personal_details ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $personalDetails[] = $row;
    }
}
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
                <h4 class="mb-0">Personal Details</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Location</th>
                                <th style="width:180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($personalDetails as $pd): ?>
                            <tr>
                                <td><?= htmlspecialchars($pd['name']) ?></td>
                                <td><?= htmlspecialchars($pd['email']) ?></td>
                                <td><?= htmlspecialchars($pd['phone']) ?></td>
                                <td><?= htmlspecialchars($pd['location']) ?></td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal"
                                        onclick="viewDetail('<?= addslashes($pd['name']) ?>','<?= addslashes($pd['email']) ?>','<?= addslashes($pd['phone']) ?>','<?= addslashes($pd['location']) ?>')">View</button>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                        onclick="editDetail(<?= $pd['id'] ?>,'<?= addslashes($pd['name']) ?>','<?= addslashes($pd['email']) ?>','<?= addslashes($pd['phone']) ?>','<?= addslashes($pd['location']) ?>')">Edit</button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        onclick="deleteDetail(<?= $pd['id'] ?>)">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1">
            <div class="modal-dialog">
                <form class="modal-content" method="post" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Personal Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_personal" class="btn btn-primary">Add</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- View Modal -->
        <div class="modal fade" id="viewModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View Personal Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div><strong>Name:</strong> <span id="viewName"></span></div>
                        <div><strong>Email:</strong> <span id="viewEmail"></span></div>
                        <div><strong>Phone:</strong> <span id="viewPhone"></span></div>
                        <div><strong>Location:</strong> <span id="viewLocation"></span></div>
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
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Personal Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-2">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" id="editName" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="editEmail" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="editPhone" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" id="editLocation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="edit_personal" class="btn btn-warning">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <form class="modal-content" method="post" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Personal Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="deleteId">
                        <p>Are you sure you want to delete this personal detail?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="delete_personal" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function viewDetail(name, email, phone, location) {
            document.getElementById('viewName').textContent = name;
            document.getElementById('viewEmail').textContent = email;
            document.getElementById('viewPhone').textContent = phone;
            document.getElementById('viewLocation').textContent = location;
        }
        function editDetail(id, name, email, phone, location) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editLocation').value = location;
        }
        function deleteDetail(id) {
            document.getElementById('deleteId').value = id;
        }
        document.addEventListener('DOMContentLoaded', function() {
            var toastEl = document.getElementById('loginToast');
            if (toastEl && toastEl.classList.contains('show')) {
                var toast = new bootstrap.Toast(toastEl, { delay: 4000 });
                toast.show();
            }
        });
        </script>
    </div>
</div>
<?php include './Layout/page_fooder.php'; ?>
<?php $conn->close(); ?>
