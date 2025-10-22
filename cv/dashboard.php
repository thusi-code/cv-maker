<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
include './Layout/page_head.php';
$_SESSION['page_title'] = 'Dashboard'; 
// Include DB connection
include 'db.php';

// Fetch Projects Completed (count from 'experience' table)
$projectsCompleted = 0;
$certifications = 0;
$yearsExperience = 0;

// Projects Completed (count rows in 'experience')
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM experience");
if ($result && $row = mysqli_fetch_assoc($result)) {
    $projectsCompleted = $row['total'];
}

// Certifications (count rows in 'professional_qualification')
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM professional_qualification");
if ($result && $row = mysqli_fetch_assoc($result)) {
    $certifications = $row['total'];
}

// Years Experience: sum up years from 'period' column in 'experience'
$result = mysqli_query($conn, "SELECT period FROM experience");
if ($result) {
    $yearsExperience = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        // Assume period format is "YYYY-YYYY" or "YYYY-Present"
        $period = $row['period'];
        if (preg_match('/(\d{4})\s*-\s*(\d{4}|Present)/', $period, $matches)) {
            $start = (int)$matches[1];
            $end = ($matches[2] === 'Present') ? (int)date('Y') : (int)$matches[2];
            if ($end >= $start) {
                $yearsExperience += ($end - $start);
            }
        }
    }
}

// Fetch programming skills for the bar chart
$skills = [];
$proficiencies = [];
$result = mysqli_query($conn, "SELECT skill, proficiency FROM programming_skills");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $skills[] = $row['skill'];
        $proficiencies[] = (int)$row['proficiency'];
    }
}

// Fetch experience by role for the pie chart
$roles = [];
$roleCounts = [];
$result = mysqli_query($conn, "SELECT title, COUNT(*) as count FROM experience GROUP BY title");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $roles[] = $row['title'];
        $roleCounts[] = (int)$row['count'];
    }
}
?>

<link rel="stylesheet" href="./CSS/Layout.css">
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row vh-100">
    <?php include './Layout/sidebar.php' ?>
    <div class="col-lg-9">
        <?php include './Layout/topbar.php'; ?>

        <!-- Dashboard Summary Cards -->
        <div class="row mt-4 mb-4 px-3">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0" style="border-left: 5px solid #a38154cc;">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Projects Completed</h6>
                        <h3 class="card-text fw-bold"><?php echo $projectsCompleted; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0" style="border-left: 5px solid #a38154cc;">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Certifications</h6>
                        <h3 class="card-text fw-bold"><?php echo $certifications; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0" style="border-left: 5px solid #a38154cc;">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Years Experience</h6>
                        <h3 class="card-text fw-bold"><?php echo $yearsExperience; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row px-3">
            <div class="col-md-7 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Technical Skills Proficiency</h6>
                        <canvas id="skillsChart" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Experience by Role</h6>
                        <canvas id="rolePieChart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Technical Skills Proficiency Bar Chart (dynamic from DB)
            const ctxSkills = document.getElementById('skillsChart').getContext('2d');
            new Chart(ctxSkills, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($skills); ?>,
                    datasets: [{
                        label: 'Proficiency (%)',
                        data: <?php echo json_encode($proficiencies); ?>,
                        backgroundColor: '#a38154cc'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, max: 100 } }
                }
            });

            // Experience by Role Pie Chart (dynamic from DB)
            const ctxRole = document.getElementById('rolePieChart').getContext('2d');
            new Chart(ctxRole, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($roles); ?>,
                    datasets: [{
                        data: <?php echo json_encode($roleCounts); ?>,
                        backgroundColor: [
                            '#a38154cc', '#232526', '#414345', '#e0c097', '#b68973', '#f7f1e5'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        </script>
    </div>
</div>

<?php
// Toast logic (set $toastMsg and $toastType in your PHP logic as needed)
$toastMsg = isset($toastMsg) ? $toastMsg : '';
$toastType = isset($toastType) ? $toastType : '';
?>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    var toastEl = document.getElementById('loginToast');
    if (toastEl && toastEl.classList.contains('show')) {
        var toast = new bootstrap.Toast(toastEl, { delay: 4000 });
        toast.show();
    }
});
</script>

<?php
include './Layout/page_fooder.php';
?>