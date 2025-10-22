<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
include './Layout/page_head.php';
$_SESSION['page_title'] = 'Education';

include './db.php';

$toastMsg = '';
$toastType = '';

// Handle Add
if (isset($_POST['add_education'])) {
    $degree = $conn->real_escape_string($_POST['degree']);
    $institute = $conn->real_escape_string($_POST['institute']);
    $period = $conn->real_escape_string($_POST['period']);
    if ($conn->query("INSERT INTO education (degree, institute, period) VALUES ('$degree', '$institute', '$period')")) {
        $toastMsg = "Education added successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to add education.";
        $toastType = "danger";
    }
}
// Handle Edit
if (isset($_POST['edit_education'])) {
    $id = intval($_POST['id']);
    $degree = $conn->real_escape_string($_POST['degree']);
    $institute = $conn->real_escape_string($_POST['institute']);
    $period = $conn->real_escape_string($_POST['period']);
    if ($conn->query("UPDATE education SET degree='$degree', institute='$institute', period='$period' WHERE id=$id")) {
        $toastMsg = "Education updated successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to update education.";
        $toastType = "danger";
    }
}
// Handle Delete
if (isset($_POST['delete_education'])) {
    $id = intval($_POST['id']);
    if ($conn->query("DELETE FROM education WHERE id=$id")) {
        $toastMsg = "Education deleted successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to delete education.";
        $toastType = "danger";
    }
}

// Fetch education data from DB
$education = [];
$result = $conn->query("SELECT id, degree, institute, period FROM education ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $education[] = $row;
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
                <h4 class="mb-0">Education</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Degree</th>
                                <th>Institute</th>
                                <th>Period</th>
                                <th style="width:180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($education as $edu): ?>
                            <tr>
                                <td><?= htmlspecialchars($edu['degree']) ?></td>
                                <td><?= htmlspecialchars($edu['institute']) ?></td>
                                <td><?= htmlspecialchars($edu['period']) ?></td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal"
                                        onclick="viewEdu('<?= addslashes($edu['degree']) ?>','<?= addslashes($edu['institute']) ?>','<?= addslashes($edu['period']) ?>')">View</button>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                        onclick="editEdu(<?= $edu['id'] ?>,'<?= addslashes($edu['degree']) ?>','<?= addslashes($edu['institute']) ?>','<?= addslashes($edu['period']) ?>')">Edit</button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        onclick="deleteEdu(<?= $edu['id'] ?>)">Delete</button>
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
                        <h5 class="modal-title">Add Education</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Degree</label>
                            <input type="text" class="form-control" name="degree" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Institute</label>
                            <input type="text" class="form-control" name="institute" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Period</label>
                            <input type="text" class="form-control" name="period" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_education" class="btn btn-primary">Add</button>
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
                        <h5 class="modal-title">View Education</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div><strong>Degree:</strong> <span id="viewDegree"></span></div>
                        <div><strong>Institute:</strong> <span id="viewInstitute"></span></div>
                        <div><strong>Period:</strong> <span id="viewPeriod"></span></div>
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
                        <h5 class="modal-title">Edit Education</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-2">
                            <label class="form-label">Degree</label>
                            <input type="text" class="form-control" name="degree" id="editDegree" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Institute</label>
                            <input type="text" class="form-control" name="institute" id="editInstitute" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Period</label>
                            <input type="text" class="form-control" name="period" id="editPeriod" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="edit_education" class="btn btn-warning">Update</button>
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
                        <h5 class="modal-title">Delete Education</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="deleteId">
                        <p>Are you sure you want to delete this education record?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="delete_education" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function viewEdu(degree, institute, period) {
            document.getElementById('viewDegree').textContent = degree;
            document.getElementById('viewInstitute').textContent = institute;
            document.getElementById('viewPeriod').textContent = period;
        }
        function editEdu(id, degree, institute, period) {
            document.getElementById('editId').value = id;
            document.getElementById('editDegree').value = degree;
            document.getElementById('editInstitute').value = institute;
            document.getElementById('editPeriod').value = period;
        }
        function deleteEdu(id) {
            document.getElementById('deleteId').value = id;
        }
        </script>
    </div>
</div>
<?php include './Layout/page_fooder.php'; ?>
<?php $conn->close(); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var toastEl = document.getElementById('loginToast');
    if (toastEl && toastEl.classList.contains('show')) {
        var toast = new bootstrap.Toast(toastEl, { delay: 4000 });
        toast.show();
    }
});
</script>
