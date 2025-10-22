<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
include './Layout/page_head.php';
$_SESSION['page_title'] = 'Professional Qualification';
include './db.php';

$toastMsg = '';
$toastType = '';

// Handle Add
if (isset($_POST['add_prof_qual'])) {
    $qualification = $conn->real_escape_string($_POST['qualification']);
    $institute = $conn->real_escape_string($_POST['institute']);
    $year = $conn->real_escape_string($_POST['year']);
    if ($conn->query("INSERT INTO professional_qualification (qualification, institute, year) VALUES ('$qualification', '$institute', '$year')")) {
        $toastMsg = "Professional qualification added successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to add professional qualification.";
        $toastType = "danger";
    }
}
// Handle Edit
if (isset($_POST['edit_prof_qual'])) {
    $id = intval($_POST['id']);
    $qualification = $conn->real_escape_string($_POST['qualification']);
    $institute = $conn->real_escape_string($_POST['institute']);
    $year = $conn->real_escape_string($_POST['year']);
    if ($conn->query("UPDATE professional_qualification SET qualification='$qualification', institute='$institute', year='$year' WHERE id=$id")) {
        $toastMsg = "Professional qualification updated successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to update professional qualification.";
        $toastType = "danger";
    }
}
// Handle Delete
if (isset($_POST['delete_prof_qual'])) {
    $id = intval($_POST['id']);
    if ($conn->query("DELETE FROM professional_qualification WHERE id=$id")) {
        $toastMsg = "Professional qualification deleted successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to delete professional qualification.";
        $toastType = "danger";
    }
}

// Fetch from DB
$profQualifications = [];
$result = $conn->query("SELECT * FROM professional_qualification ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $profQualifications[] = $row;
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
                <h4 class="mb-0">Professional Qualification</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Qualification</th>
                                <th>Institute</th>
                                <th>Year</th>
                                <th style="width:180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($profQualifications as $pq): ?>
                            <tr>
                                <td><?= htmlspecialchars($pq['qualification']) ?></td>
                                <td><?= htmlspecialchars($pq['institute']) ?></td>
                                <td><?= htmlspecialchars($pq['year']) ?></td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal"
                                        onclick="viewPQ('<?= addslashes($pq['qualification']) ?>','<?= addslashes($pq['institute']) ?>','<?= addslashes($pq['year']) ?>')">View</button>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                        onclick="editPQ(<?= $pq['id'] ?>,'<?= addslashes($pq['qualification']) ?>','<?= addslashes($pq['institute']) ?>','<?= addslashes($pq['year']) ?>')">Edit</button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        onclick="deletePQ(<?= $pq['id'] ?>)">Delete</button>
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
                        <h5 class="modal-title">Add Professional Qualification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Qualification</label>
                            <input type="text" class="form-control" name="qualification" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Institute</label>
                            <input type="text" class="form-control" name="institute" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Year</label>
                            <input type="text" class="form-control" name="year" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_prof_qual" class="btn btn-primary">Add</button>
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
                        <h5 class="modal-title">View Professional Qualification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div><strong>Qualification:</strong> <span id="viewQualification"></span></div>
                        <div><strong>Institute:</strong> <span id="viewInstitute"></span></div>
                        <div><strong>Year:</strong> <span id="viewYear"></span></div>
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
                        <h5 class="modal-title">Edit Professional Qualification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-2">
                            <label class="form-label">Qualification</label>
                            <input type="text" class="form-control" name="qualification" id="editQualification" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Institute</label>
                            <input type="text" class="form-control" name="institute" id="editInstitute" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Year</label>
                            <input type="text" class="form-control" name="year" id="editYear" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="edit_prof_qual" class="btn btn-warning">Update</button>
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
                        <h5 class="modal-title">Delete Professional Qualification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="deleteId">
                        <p>Are you sure you want to delete this professional qualification?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="delete_prof_qual" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function viewPQ(qualification, institute, year) {
            document.getElementById('viewQualification').textContent = qualification;
            document.getElementById('viewInstitute').textContent = institute;
            document.getElementById('viewYear').textContent = year;
        }
        function editPQ(id, qualification, institute, year) {
            document.getElementById('editId').value = id;
            document.getElementById('editQualification').value = qualification;
            document.getElementById('editInstitute').value = institute;
            document.getElementById('editYear').value = year;
        }
        function deletePQ(id) {
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
