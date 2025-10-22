<?php
include "./Layout/page_head.php";
?>

<!-- Toast Alert -->
<div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
    <div id="regToast" class="toast align-items-center text-bg-<?= $toastType ?> border-0<?php if($toastMsg) echo ' show'; ?>" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <?= htmlspecialchars($toastMsg) ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<div class="row d-flex justify-content-center align-items-center vh-100" style="background: linear-gradient(135deg, #232526 0%, #a38154cc 100%);">
    <div class="col-lg-4 col-md-6">
        <div class="card shadow-lg border-0" style="border-radius:18px;">
            <div class="card-header text-center bg-white" style="border-top-left-radius:18px; border-top-right-radius:18px;">
                <img src="https://placehold.co/80x80" class="rounded-circle mb-2" alt="profile" />
                <h5 class="card-title mb-0" style="font-weight:600; color:#a38154cc;">Create New Account</h5>
                <small class="text-muted">Fill the form to register</small>
            </div>
            <div class="card-body px-4 py-4">
                <form >
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class='bx bx-user'></i></span>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class='bx bx-user-circle'></i></span>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class='bx bx-lock'></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class='bx bx-lock-alt'></i></span>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                        </div>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <input type="button" class="btn" id="registerButton" value="Create" style="background:#a38154cc; color:#fff; font-weight:500;">
                        <a href="index.php" class="btn btn-outline-secondary">Already Have An Account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Boxicons CDN for icons -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<script>

    $(document).ready(function () {
        $("#registerButton").click(function(){

            let _data = {
                name : $("#name").val(),
                username : $("#username").val(),
                password : $("#password").val(),
                confirm_password : $("#confirm_password").val()
            }
   
            $.ajax({
                url : "registrationAPI.php",
                type : "POST",
                dataType : "json",
                data : _data,
                success : function(data) {
                    alert(data.toastMsg);
                }
            });
        });
    });


document.addEventListener('DOMContentLoaded', function() {
    var toastEl = document.getElementById('regToast');
    if (toastEl && toastEl.classList.contains('show')) {
        var toast = new bootstrap.Toast(toastEl, { delay: 4000 });
        toast.show();
    }
});
</script>

<?php include "./Layout/page_fooder.php"; ?>