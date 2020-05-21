<div>
    <div class="w3-cell-row w3-mobile" style="padding: 32px">
        <div class="w3-cell w3-cell-top w3-mobile" style="width: 25%">
            <div class="w3-card w3-pale-yellow w3-border-yellow w3-leftbar">
                <div class="w3-padding">
                    <?php echo $info->highlight; ?>
                </div>
            </div>
        </div>
        <div class="w3-cell w3-cell-middle w3-mobile" style="width: 16px; height: 16px"></div>
        <div class="w3-cell w3-cell-middle w3-mobile">
            <div id="auth-page" class="w3-card w3-round <?php echo COLOR4; ?> w3-cell-row w3-mobile">
                <div class="w3-cell w3-cell-middle w3-mobile" style="width: 25%">
                    <div class="w3-padding">
                        <h3><?php echo $title; ?></h3>
                        <?php echo (uri_string() === 'customer_v2/auth/sign_in') ? '<h4>&raquo; Masuk</h4>' : ''; ?>
                        <?php echo (uri_string() === 'customer_v2/auth/sign_up') ? '<h4>&raquo; Daftar</h4>' : ''; ?>
                    </div>
                </div>
                <div class="w3-cell w3-cell-middle <?php echo COLOR5; ?> w3-mobile">
                    <?php if (uri_string() === 'customer_v2/auth/sign_in') { ?>
                        <div class="w3-padding-large" id="login-page">
                            <?php echo form_open('customer_v2/auth/sign_in'); ?>
                                <a href="<?php echo site_url('customer_v2/auth/sign_up').'#auth-page'; ?>">Belum mempunyai akun?</a><br>
                                <br>
                                <label for="email">Alamat e-mail:</label>
                                <input type="email" id="email" name="email" class="w3-input w3-border" placeholder="Masukkan e-mail" value="<?php echo set_value('email'); ?>"><br>
                                <label for="password">Kata sandi:</label>
                                <input v-bind:type="showPassword ? 'text' : 'password'" id="password" name="password" class="w3-input w3-border" placeholder="Masukkan kata sandi"><br>
                                <input type="checkbox" class="w3-check" v-model="showPassword">&nbsp;Perlihatkan kata sandi<br>
                                <br>
                                <input type="submit" class="w3-btn w3-round-xxlarge <?php echo COLOR6; ?>" value="Masuk"> atau <a href="<?php echo site_url('customer_v2/auth/sign_up').'#auth'; ?>">Daftar</a><br>
                                <br>
                                <a href="<?php echo site_url('customer_v2/help/recover').'#help-page'; ?>">Lupa password anda?</a><br>
                            </form>
                        </div>
                        <script>
                            window.loginPage = new Vue({
                                el: '#login-page',
                                data: {
                                    showPassword: false
                                }
                            });
                        </script>
                    <?php } else if (uri_string() === 'customer_v2/auth/sign_up') { ?>
                        <script src="<?php echo base_url('public/captcha.js'); ?>"></script>
                        <div class="w3-padding-large" id="register-page" >
                            <?php echo form_open('customer_v2/auth/sign_up', 'onsubmit="return yaqueen()"'); ?>
                                <a href="<?php echo site_url('customer_v2/auth/sign_in').'#auth-page'; ?>">Atau sudah mempunyai akun?</a><br>
                                <br>
                                <input type="hidden" name="valid_captcha" value="<?php echo $captcha; ?>">
                                <label for="email">Alamat e-mail:</label>
                                <input type="email" id="email" name="email" class="w3-input w3-border" placeholder="Masukkan e-mail (contoh: johndoe@gmail.com)" value="<?php echo set_value('email'); ?>"><br>
                                <label for="password">Kata sandi: (min. 8 karakter)</label>
                                <input v-bind:type="showPassword ? 'text' : 'password'" id="password" name="password" class="w3-input w3-border" placeholder="Buat password untuk akun khusus event ini"><br>
                                <label for="password-repeat">Ulangi kata sandi:</label>
                                <input v-bind:type="showPassword ? 'text' : 'password'" id="password-repeat" name="password_repeat" class="w3-input w3-border" placeholder="Masukkan password"><br>
                                <input type="checkbox" class="w3-check" v-model="showPassword">&nbsp;Perlihatkan kata sandi<br>
                                <br>
                                <label for="captcha">Masukkan captcha:</label>
                                <div class="w3-cell-row">
                                    <div class="w3-cell w3-cell-top" style="width: 99%">
                                        <input type="text" id="captcha" name="captcha" class="w3-input w3-border" placeholder="Masukkan kode captcha berikut disini">
                                    </div>
                                    <div class="w3-cell w3-cell-top">&nbsp;</div>
                                    <div class="w3-cell w3-cell-top">
                                        <canvas id="captcha-text" width="128" height="48"></canvas>
                                    </div>
                                </div>
                                <br>
                                <input type="checkbox" class="w3-check" v-model="responsible">&nbsp;Saya bertanggung jawab atas informasi dan data-data yang saya tulis<br>
                                <input type="checkbox" class="w3-check" v-model="smagant">&nbsp;Saya bukan merupakan siswa SMAN 3 Surakarta<br>
                                <!-- <input type="checkbox" class="w3-check" v-model="highschoolStudent">&nbsp;Saya merupakan siswa SMA/SMK/sederajat atau alumni SMAN 3 Surakarta<br> -->
                                <input type="checkbox" class="w3-check" v-model="agree">&nbsp;Saya telah menyetujui <a href="<?php echo site_url('customer_v2/terms').'#account'; ?>" target="_blank">Syarat dan Ketentuan</a><br>
                                <br>
                                <input type="submit" class="w3-btn w3-round-xxlarge <?php echo COLOR6; ?>" value="Daftar" v-bind:disabled="!(responsible && agree && highschoolStudent && smagant)"> atau <a href="<?php echo site_url('customer_v2/auth/sign_in').'#auth-page'; ?>">Masuk</a><br>
                            </form>
                        </div>
                        <script>
                            window.registerPage = new Vue({
                                el: '#register-page',
                                data: {
                                    showPassword: false,
                                    responsible: false,
                                    smagant: false,
                                    highschoolStudent: true,
                                    agree: false
                                }
                            });

                            window.addEventListener('load', function() {
                                Captcha.render('#captcha-text', '<?php echo $captcha; ?>');
                            });
                        </script>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
