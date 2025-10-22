<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
include './Layout/page_head.php';
$_SESSION['page_title'] = 'Language Skills';
include './db.php';

$toastMsg = '';
$toastType = '';

// Handle Add
if (isset($_POST['add_language'])) {
    $language = $conn->real_escape_string($_POST['language']);
    $proficiency = intval($_POST['proficiency']);
    if ($conn->query("INSERT INTO language_skills (language, proficiency) VALUES ('$language', $proficiency)")) {
        $toastMsg = "Language skill added successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to add language skill.";
        $toastType = "danger";
    }
}
// Handle Edit
if (isset($_POST['edit_language'])) {
    $id = intval($_POST['id']);
    $language = $conn->real_escape_string($_POST['language']);
    $proficiency = intval($_POST['proficiency']);
    if ($conn->query("UPDATE language_skills SET language='$language', proficiency=$proficiency WHERE id=$id")) {
        $toastMsg = "Language skill updated successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to update language skill.";
        $toastType = "danger";
    }
}
// Handle Delete
if (isset($_POST['delete_language'])) {
    $id = intval($_POST['id']);
    if ($conn->query("DELETE FROM language_skills WHERE id=$id")) {
        $toastMsg = "Language skill deleted successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to delete language skill.";
        $toastType = "danger";
    }
}

// Fetch from DB
$languageSkills = [];
$result = $conn->query("SELECT * FROM language_skills ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $languageSkills[] = $row;
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
                <h4 class="mb-0">Language Skills</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Language</th>
                                <th>Proficiency (%)</th>
                                <th style="width:180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($languageSkills as $ls): ?>
                            <tr>
                                <td><?= htmlspecialchars($ls['language']) ?></td>
                                <td><?= htmlspecialchars($ls['proficiency']) ?>%</td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal"
                                        onclick="viewDetail('<?= addslashes($ls['language']) ?>','<?= $ls['proficiency'] ?>')">View</button>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                        onclick="editDetail(<?= $ls['id'] ?>,'<?= addslashes($ls['language']) ?>','<?= $ls['proficiency'] ?>')">Edit</button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        onclick="deleteDetail(<?= $ls['id'] ?>)">Delete</button>
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
                        <h5 class="modal-title">Add Language Skill</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Language</label>
                            <input type="text" class="form-control" name="language" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Proficiency (%)</label>
                            <input type="number" class="form-control" name="proficiency" min="0" max="100" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_language" class="btn btn-primary">Add</button>
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
                        <h5 class="modal-title">View Language Skill</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div><strong>Language:</strong> <span id="viewLanguage"></span></div>
                        <div><strong>Proficiency:</strong> <span id="viewProficiency"></span>%</div>
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
                        <h5 class="modal-title">Edit Language Skill</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId">
                        <div class="mb-2">
                            <label class="form-label">Language</label>
                            <input type="text" class="form-control" name="language" id="editLanguage" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Proficiency (%)</label>
                            <input type="number" class="form-control" name="proficiency" id="editProficiency" min="0" max="100" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="edit_language" class="btn btn-warning">Update</button>
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
                        <h5 class="modal-title">Delete Language Skill</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="deleteId">
                        <p>Are you sure you want to delete this language skill?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="delete_language" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function viewDetail(language, proficiency) {
            document.getElementById('viewLanguage').textContent = language;
            document.getElementById('viewProficiency').textContent = proficiency;
        }
        function editDetail(id, language, proficiency) {
            document.getElementById('editId').value = id;
            document.getElementById('editLanguage').value = language;
            document.getElementById('editProficiency').value = proficiency;
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
