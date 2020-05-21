<div>
    <div style="padding: 32px">
        <div id="order-page" class="w3-card w3-round <?php echo COLOR4; ?> w3-cell-row w3-mobile">
            <div class="w3-cell w3-cell-middle w3-mobile" style="width: 25%">
                <div class="w3-padding">
                    <h3><?php echo $title; ?></h3>
                </div>
            </div>
            <div id="recover-page" class="w3-padding w3-cell w3-cell-middle <?php echo COLOR5; ?> w3-mobile">
                <?php echo form_open('customer_v2/recover/'.$recovery, 'onsubmit="return yaqueen()"'); ?>
                    <label for="email">Alamat e-mail:</label>
                    <input disabled type="email" id="email" name="email" class="w3-input w3-border" value="<?php echo $email; ?>"><br>
                    <label for="password">Kata sandi:</label>
                    <input v-bind:type="showPassword ? 'text' : 'password'" id="password" name="password" class="w3-input w3-border" placeholder="Buat kata sandi yang baru (minimal 8 karakter)"><br>
                    <label for="password-repeat">Ulangi kata sandi:</label>
                    <input v-bind:type="showPassword ? 'text' : 'password'" id="password-repeat" name="password_repeat" class="w3-input w3-border" placeholder="Masukkan kata sandi"><br>
                    <input type="checkbox" class="w3-check" v-model="showPassword">&nbsp;Perlihatkan kata sandi<br>
                    <br>
                    <input type="submit" class="w3-btn w3-round-xxlarge <?php echo COLOR6; ?>" value="Perbarui"><br>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

window.recoverPage = new Vue({
    el: '#recover-page',
    data: {
        showPassword: false
    }
});

</script>
