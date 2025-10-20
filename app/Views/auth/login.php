<?php
    $auth_title = 'Welcome Back';
    require APPROOT . '/Views/auth/wrapper_start.php';
?>
                        <form action="<?php echo URLROOT; ?>/auth/login" method="post" novalidate>
                            <div class="form-group">
                                <input type="email" name="email" id="email" class="form-style <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" placeholder="Your Email" autocomplete="off" value="<?php echo htmlspecialchars($data['email']); ?>">
                                <i class="input-icon uil uil-at"></i>
                                <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                            </div>
                            <div class="form-group mt-2">
                                <input type="password" name="password" id="password" class="form-style <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" placeholder="Your Password" autocomplete="off">
                                <i class="input-icon uil uil-lock-alt"></i>
                                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                            </div>
                            <button type="submit" class="btn mt-4" id="login-button">Login</button>
                            <p class="mb-0 mt-4 text-center"><a href="<?php echo URLROOT; ?>/auth/register" class="link">No account? Register</a></p>
                        </form>
<?php require APPROOT . '/Views/auth/wrapper_end.php'; ?>
