<?= $this->include('layout/header'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">OTP Verification</h2>
        
        <?php if(session()->getFlashdata('msg')):?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif;?>

        <div class="row justify-content-center">
            <div class="col-md-6">
            <form action="<?= site_url('verify-otp') ?>" method="POST">
                    <?= csrf_field(); ?>
                    
                    <div class="form-group">
                        <label for="otp">Enter OTP</label>
                        <input type="text" class="form-control" id="otp" name="otp" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Verify OTP</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
