<?php 
    $auth_title = 'Create An Account';
    require APPROOT . '/Views/auth/wrapper_start.php';
?>
                        <?php if (!empty($data['general_err'])) : ?>
                            <div class="alert alert-danger"><?php echo $data['general_err']; ?></div>
                        <?php endif; ?>
                        <form id="register-form" action="<?php echo URLROOT; ?>/auth/register" method="post" novalidate>
                            <div class="form-group">
                                <input type="text" name="first_name" id="first_name" class="form-style" placeholder="First Name" autocomplete="off" value="<?php echo htmlspecialchars($data['first_name']); ?>">
                                <i class="input-icon uil uil-user"></i>
                                <span class="invalid-feedback"><?php echo $data['first_name_err']; ?></span>
                            </div>
                            <div class="form-group mt-2">
                                <input type="text" name="last_name" id="last_name" class="form-style" placeholder="Last Name" autocomplete="off" value="<?php echo htmlspecialchars($data['last_name']); ?>">
                                <i class="input-icon uil uil-user"></i>
                                <span class="invalid-feedback"><?php echo $data['last_name_err']; ?></span>
                            </div>
                            <div class="form-group mt-2">
                                <input type="email" name="email" id="email" class="form-style" placeholder="Your Email" autocomplete="off" value="<?php echo htmlspecialchars($data['email']); ?>">
                                <i class="input-icon uil uil-at"></i>
                                <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                            </div>
                             <div class="form-group mt-2">
                                <input type="tel" name="phone_number" id="phone_number" class="form-style" placeholder="Phone Number" autocomplete="off" value="<?php echo htmlspecialchars($data['phone_number']); ?>">
                                <i class="input-icon uil uil-phone"></i>
                                <span class="invalid-feedback"><?php echo $data['phone_number_err']; ?></span>
                            </div>
                            <div class="form-group mt-2">
                                <input type="password" name="password" id="password" class="form-style" placeholder="Your Password" autocomplete="off">
                                <i class="input-icon uil uil-lock-alt"></i>
                                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                            </div>
                            <div class="form-group mt-2">
                                <input type="password" name="confirm_password" id="confirm_password" class="form-style" placeholder="Confirm Password" autocomplete="off">
                                <i class="input-icon uil uil-lock-alt"></i>
                                <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                            </div>
                            <button type="submit" class="btn mt-4" id="register-button" >Register</button>
                            <p class="mb-0 mt-4 text-center"><a href="<?php echo URLROOT; ?>/auth/login" class="link">Have an account? Login</a></p>
                        </form>
<?php require APPROOT . '/Views/auth/wrapper_end.php'; ?>
