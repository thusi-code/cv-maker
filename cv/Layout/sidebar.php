<div class="col-lg-3 sidebar-bg d-flex flex-column min-vh-100 p-0" style="background: linear-gradient(135deg, #232526 0%, #a38154cc 100%); box-shadow: 2px 0 10px rgba(0,0,0,0.08);">
    <div class="col-lg-12 user-profile d-flex justify-content-center align-items-center flex-column pt-5 pb-4" style="border-bottom: 1px solid rgba(255,255,255,0.08);">
        <div class="profile-img-wrapper mb-3" style="background: #fff; padding: 6px; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.10);">
            <img src="https://placehold.co/100" class="rounded-circle" style="width:90px; height:90px; object-fit:cover;" />
        </div>
        <h5 class="text-white mt-2 mb-1" style="font-weight:600; letter-spacing:0.5px;">NMM. Nuhman</h5>
        <span class="badge mb-2" style="font-size:0.9rem; background:#a38154cc;">Program Manager</span>
    </div>

    <div class="col-lg-12 nav-container mt-4 flex-grow-1">
        <a href="dashboard.php" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 mb-1<?php if(basename($_SERVER['PHP_SELF'])=='dashboard.php') echo ' active'; ?>" style="border-radius:8px; transition:background 0.2s;">
            <i class='bx bx-pie-chart-alt me-2' style="font-size:1.3rem;"></i> Dashboard
        </a>
        <a href="personal_details.php" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 mb-1<?php if(basename($_SERVER['PHP_SELF'])=='personal_details.php') echo ' active'; ?>" style="border-radius:8px; transition:background 0.2s;">
            <i class='bx bx-id-card me-2 bx-sm' style="font-size:1.3rem;"></i> Personal Details
        </a>
        <a href="programming_skills.php" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 mb-1<?php if(basename($_SERVER['PHP_SELF'])=='programming_skills.php') echo ' active'; ?>" style="border-radius:8px; transition:background 0.2s;">
            <i class='bx bx-code-alt me-2' style="font-size:1.3rem;"></i> Programming Skills
        </a>
        <a href="soft_skills.php" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 mb-1<?php if(basename($_SERVER['PHP_SELF'])=='soft_skills.php') echo ' active'; ?>" style="border-radius:8px; transition:background 0.2s;">
            <i class='bx bx-user-voice me-2' style="font-size:1.3rem;"></i> Soft Skills
        </a>
        <a href="language_skills.php" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 mb-1<?php if(basename($_SERVER['PHP_SELF'])=='language_skills.php') echo ' active'; ?>" style="border-radius:8px; transition:background 0.2s;">
            <i class='bx bx-globe me-2' style="font-size:1.3rem;"></i> Language Skills
        </a>
        <a href="education.php" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 mb-1<?php if(basename($_SERVER['PHP_SELF'])=='education.php') echo ' active'; ?>" style="border-radius:8px; transition:background 0.2s;">
            <i class='bx bx-book-bookmark me-2' style="font-size:1.3rem;"></i> Education
        </a>
        <a href="professional_qualification.php" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 mb-1<?php if(basename($_SERVER['PHP_SELF'])=='professional_qualification.php') echo ' active'; ?>" style="border-radius:8px; transition:background 0.2s;">
            <i class='bx bx-certification me-2' style="font-size:1.3rem;"></i> Professional Qualification
        </a>
        <a href="experience.php" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 mb-1<?php if(basename($_SERVER['PHP_SELF'])=='experience.php') echo ' active'; ?>" style="border-radius:8px; transition:background 0.2s;">
            <i class='bx bx-briefcase-alt me-2' style="font-size:1.3rem;"></i> Experience
        </a>
        <a href="references.php" class="sidebar-link text-white text-decoration-none d-flex align-items-center p-3 mb-1<?php if(basename($_SERVER['PHP_SELF'])=='references.php') echo ' active'; ?>" style="border-radius:8px; transition:background 0.2s;">
            <i class='bx bx-user-check me-2' style="font-size:1.3rem;"></i> References
        </a>
    </div>

    <div class="col-lg-12 mt-auto mb-4 d-flex justify-content-center">
        <a href="logout.php" class="btn btn-outline-light d-flex align-items-center px-4 py-2" style="border-radius: 25px; font-weight:500; border-color:#a38154cc; color:#a38154cc;">
            <i class='bx bx-log-out me-2'></i> Logout
        </a>
    </div>

    <style>
        .sidebar-link:hover, .sidebar-link.active {
            background: #a38154cc !important;
            color: #fff !important;
            text-decoration: none;
        }
        .sidebar-link i {
            min-width: 24px;
        }
        .user-profile h5, .user-profile span {
            text-shadow: 0 1px 2px rgba(0,0,0,0.10);
        }
        .btn-outline-light:hover, .btn-outline-light:focus {
            background: #a38154cc !important;
            color: #fff !important;
            border-color: #a38154cc !important;
        }
    </style>
</div>