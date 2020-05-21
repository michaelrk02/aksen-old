<div id="landing" class="w3-content" style="max-width: 640px">
    <?php window_frame($title); ?>
        <?php window_tabs_begin(); ?>
            <?php window_tab($section, 'admin/auth', 'Masuk'); ?>
        <?php window_tabs_end(); ?>
        <br>
        <div class="w3-container">
            <?php echo form_open('admin/auth'); ?>
                <label for="password">Kata sandi:</label>
                <input class="w3-input w3-border" id="password" name="password" type="password" placeholder="Masukkan kata sandi"><br>
                <br>
                <input class="w3-btn <?php echo COLOR6; ?>" type="submit" value="Mash00k">
            </form>
        </div>
    </div>
</div>
