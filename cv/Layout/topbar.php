<div class="row">
    <div class="col-lg-12 d-flex align-items-center border-bottom px-4 py-3" style="background: #fff; box-shadow: 0 2px 8px rgba(163,129,84,0.05);">
        <h2 class="mb-0" style="font-weight:600; color:#232526;">
            <?php echo isset($_SESSION['page_title']) ? $_SESSION['page_title'] : 'Dashboard'; ?>
        </h2>
        <a href="logout.php" class="btn ms-auto" style="background:#a38154cc; color:#fff; font-weight:500; border-radius:20px; padding:8px 28px; box-shadow:0 1px 4px rgba(163,129,84,0.10); transition:background 0.2s;">
            Logout
        </a>
    </div>
</div>