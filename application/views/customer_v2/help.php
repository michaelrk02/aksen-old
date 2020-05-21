<script src="<?php echo base_url('public/captcha.js'); ?>"></script>
<div style="padding: 32px">
        <div id="help-page" class="w3-card w3-round <?php echo COLOR4; ?> w3-cell-row">
            <div class="w3-cell w3-cell-middle w3-mobile" style="width: 25%">
                <div class="w3-padding">
                    <h3><?php echo $title; ?></h3>
                </div>
            </div>
            <div class="w3-cell w3-cell-middle w3-mobile <?php echo COLOR5; ?>">
                <div class="w3-padding">
                    <?php echo form_open('customer_v2/help', 'onsubmit="return yaqueen()"'); ?>
                        <input type="hidden" name="valid_captcha" value="<?php echo $captcha; ?>">
                        <label for="email">Alamat e-mail:</label>
                        <input type="email" id="email" name="email" class="w3-input w3-border" placeholder="Masukkan e-mail" value="<?php echo set_value('email'); ?>"><br>
                        <label for="help-kind">Jenis bantuan:</label>
                        <select id="help-kind" name="help_kind" class="w3-select w3-border" v-model="helpKind">
                            <option value="null">-- Pilih salah satu --</option>
                            <option value="register-re">Kirim ulang link registrasi</option>
                            <option value="recover">Perbarui password</option>
                            <option value="recover-re">Kirim ulang link pembaruan password</option>
                        </select><br>
                        <br>
                        <label for="captcha">Masukkan captcha:</label>
                        <div class="w3-cell-row">
                            <div class="w3-cell w3-cell-top" style="width: 99%">
                                <input type="text" id="captcha" name="captcha" class="w3-input w3-border" placeholder="Masukkan tulisan captcha">
                            </div>
                            <div class="w3-cell w3-cell-top">&nbsp;</div>
                            <div class="w3-cell w3-cell-top">
                                <canvas id="captcha-text" width="128" height="48"></canvas>
                            </div>
                        </div><br>
                        <br>
                        <input type="submit" class="w3-btn w3-round-xxlarge <?php echo COLOR6; ?>" value="Kirim" v-bind:disabled="helpKind === 'null'">
                    </form>
                </div>
            </div>
        </div>
</div>
<script>

window.helpPage = new Vue({
    el: '#help-page',
    data: {
        helpKind: '<?php echo isset($help_kind) ? $help_kind : 'null' ?>'
    }
});

window.addEventListener('load', function() {
    Captcha.render('#captcha-text', '<?php echo $captcha; ?>');
});

</script>
