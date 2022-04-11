<?php include $_ENV['VIEW_PATH'] . 'include/header.php'; ?>

<div class="page-wrap d-flex flex-row align-items-center py-5 my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <span class="display-1 d-block"><?php echo $errorType; ?></span>
                <div class="mb-4 lead"><?php echo $message; ?></div>
                <a href="/" class="btn btn-link">Back to Home</a>
            </div>
        </div>
    </div>
</div>

<?php include $_ENV['VIEW_PATH'] . 'include/footer.php'; ?>