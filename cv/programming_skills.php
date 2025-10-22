<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
include './Layout/page_head.php';
$_SESSION['page_title'] = 'Programming Skills';

include './db.php';

$toastMsg = '';
$toastType = '';

// Handle Add
if (isset($_POST['add_programming_skill'])) {
    $skill = $conn->real_escape_string($_POST['skill']);
    $proficiency = intval($_POST['proficiency']);
    if ($conn->query("INSERT INTO programming_skills (skill, proficiency) VALUES ('$skill', $proficiency)")) {
        $toastMsg = "Programming skill added successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to add programming skill.";
        $toastType = "danger";
    }
}
// Handle Edit
if (isset($_POST['edit_programming_skill'])) {
    $id = intval($_POST['id']);
    $skill = $conn->real_escape_string($_POST['skill']);
    $proficiency = intval($_POST['proficiency']);
    if ($conn->query("UPDATE programming_skills SET skill='$skill', proficiency=$proficiency WHERE id=$id")) {
        $toastMsg = "Programming skill updated successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to update programming skill.";
        $toastType = "danger";
    }
}
// Handle Delete
if (isset($_POST['delete_programming_skill'])) {
    $id = intval($_POST['id']);
    if ($conn->query("DELETE FROM programming_skills WHERE id=$id")) {
        $toastMsg = "Programming skill deleted successfully.";
        $toastType = "success";
    } else {
        $toastMsg = "Failed to delete programming skill.";
        $toastType = "danger";
    }
}

// Fetch programming skills from DB
$skills = [];
$result = $conn->query("SELECT * FROM programming_skills ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
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
                <h4 class="mb-0">Programming Skills</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Skill</th>
                                <th>Proficiency (%)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($skills as $skill): ?>
                            <tr>
                                <td><?= htmlspecialchars($skill['skill']) ?></td>
                                <td><span class="badge rounded-pill" style="background:#a38154cc;"><?= intval($skill['proficiency']) ?>%</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal" onclick="viewSkill('<?= htmlspecialchars(addslashes($skill['skill'])) ?>','<?= intval($skill['proficiency']) ?>')">View</button>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editSkill('<?= $skill['id'] ?>','<?= htmlspecialchars(addslashes($skill['skill'])) ?>','<?= intval($skill['proficiency']) ?>')">Edit</button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="deleteSkill('<?= $skill['id'] ?>')">Delete</button>
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
                            <h5 class="modal-title">Add Programming Skill</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Skill</label>
                                <input type="text" name="skill" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Proficiency (%)</label>
                                <input type="number" name="proficiency" class="form-control" min="0" max="100" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="add_programming_skill" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- View Modal -->
            <div class="modal fade" id="viewModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">View Programming Skill</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-2"><strong>Skill:</strong> <span id="viewSkill"></span></div>
                            <div class="mb-2"><strong>Proficiency:</strong> <span id="viewProficiency"></span>%</div>
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
                            <h5 class="modal-title">Edit Programming Skill</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Skill</label>
                                <input type="text" name="skill" id="editSkill" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Proficiency (%)</label>
                                <input type="number" name="proficiency" id="editProficiency" class="form-control" min="0" max="100" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="edit_programming_skill" class="btn btn-warning">Update</button>
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
                            <h5 class="modal-title">Delete Programming Skill</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this programming skill?
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="delete_programming_skill" class="btn btn-danger">Delete</button>
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
function viewSkill(skill, proficiency) {
    document.getElementById('viewSkill').textContent = skill;
    document.getElementById('viewProficiency').textContent = proficiency;
}
function editSkill(id, skill, proficiency) {
    document.getElementById('editId').value = id;
    document.getElementById('editSkill').value = skill;
    document.getElementById('editProficiency').value = proficiency;
}
function deleteSkill(id) {
    document.getElementById('deleteId').value = id;
}
</script>
<?php $conn->close(); ?>
<?php include './Layout/page_fooder.php'; ?>
