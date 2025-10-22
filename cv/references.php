<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
include './Layout/page_head.php';
$_SESSION['page_title'] = 'References';
include './db.php';

$toastMsg = '';
$toastType = '';

// Handle Add
if (isset($_POST['add_reference'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $position = $conn->real_escape_string($_POST['position']);
    $organization = $conn->real_escape_string($_POST['organization']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    if ($conn->query("INSERT INTO `references` (name, position, organization, email, phone) VALUES ('$name', '$position', '$organization', '$email', '$phone')")) {
        $toastMsg = "Reference added successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to add reference.";
        $toastType = "danger";
    }
}
// Handle Edit
if (isset($_POST['edit_reference'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $position = $conn->real_escape_string($_POST['position']);
    $organization = $conn->real_escape_string($_POST['organization']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    if ($conn->query("UPDATE `references` SET name='$name', position='$position', organization='$organization', email='$email', phone='$phone' WHERE id=$id")) {
        $toastMsg = "Reference updated successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to update reference.";
        $toastType = "danger";
    }
}
// Handle Delete
if (isset($_POST['delete_reference'])) {
    $id = intval($_POST['id']);
    if ($conn->query("DELETE FROM `references` WHERE id=$id")) {
        $toastMsg = "Reference deleted successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to delete reference.";
        $toastType = "danger";
    }
}

// Fetch from DB
$references = [];
$result = $conn->query("SELECT * FROM `references` ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $references[] = $row;
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
                <h4 class="mb-0">References</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Organization</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th style="width:180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($references as $ref): ?>
                            <tr>
                                <td><?= htmlspecialchars($ref['name']) ?></td>
                                <td><?= htmlspecialchars($ref['position']) ?></td>
                                <td><?= htmlspecialchars($ref['organization']) ?></td>
                                <td><?= htmlspecialchars($ref['email']) ?></td>
                                <td><?= htmlspecialchars($ref['phone']) ?></td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal"
                                        onclick="viewRef('<?= addslashes($ref['name']) ?>','<?= addslashes($ref['position']) ?>','<?= addslashes($ref['organization']) ?>','<?= addslashes($ref['email']) ?>','<?= addslashes($ref['phone']) ?>')">View</button>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                        onclick="editRef(<?= $ref['id'] ?>,'<?= addslashes($ref['name']) ?>','<?= addslashes($ref['position']) ?>','<?= addslashes($ref['organization']) ?>','<?= addslashes($ref['email']) ?>','<?= addslashes($ref['phone']) ?>')">Edit</button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        onclick="deleteRef(<?= $ref['id'] ?>)">Delete</button>
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
                        <h5 class="modal-title">Add Reference</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-control" name="position" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Organization</label>
                            <input type="text" class="form-control" name="organization" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_reference" class="btn btn-primary">Add</button>
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
                        <h5 class="modal-title">View Reference</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div><strong>Name:</strong> <span id="viewName"></span></div>
                        <div><strong>Position:</strong> <span id="viewPosition"></span></div>
                        <div><strong>Organization:</strong> <span id="viewOrganization"></span></div>
                        <div><strong>Email:</strong> <span id="viewEmail"></span></div>
                        <div><strong>Phone:</strong> <span id="viewPhone"></span></div>
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
                        <h5 class="modal-title">Edit Reference</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-2">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="editName" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-control" name="position" id="editPosition" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Organization</label>
                            <input type="text" class="form-control" name="organization" id="editOrganization" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="editEmail">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="editPhone">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="edit_reference" class="btn btn-warning">Update</button>
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
                        <h5 class="modal-title">Delete Reference</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="deleteId">
                        <p>Are you sure you want to delete this reference?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="delete_reference" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function viewRef(name, position, organization, email, phone) {
            document.getElementById('viewName').textContent = name;
            document.getElementById('viewPosition').textContent = position;
            document.getElementById('viewOrganization').textContent = organization;
            document.getElementById('viewEmail').textContent = email;
            document.getElementById('viewPhone').textContent = phone;
        }
        function editRef(id, name, position, organization, email, phone) {
            document.getElementById('editId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editPosition').value = position;
            document.getElementById('editOrganization').value = organization;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPhone').value = phone;
        }
        function deleteRef(id) {
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
