<div class="w3-container">
    <h3>Pengaturan:</h3>
    <?php echo form_open('admin/settings', 'onsubmit="return yaqueen()"'); ?>
        <div class="w3-responsive">
            <table class="w3-table-all <?php echo COLOR3; ?>">
                <tr class="<?php echo COLOR2; ?>">
                    <th>Spesifikasi</th>
                    <th>Keterangan</th>
                </tr>
                <tr>
                    <td>Highlight</td>
                    <td><textarea type="textarea" class="w3-input w3-border w3-code" rows="5" style="width: 100%; resize: none" name="admin_highlight" placeholder="Masukkan highlight..."><?php echo $info->highlight; ?></textarea></td>
                </tr>
                <tr>
                    <td>Waktu cek terakhir</td>
                    <td><input type="date" class="w3-input w3-border" name="admin_last_check_timestamp" value="<?php echo date('Y-m-d', $info->last_check_timestamp); ?>"></td>
                </tr>
                <tr>
                    <td>Kategori tiket</td>
                    <td>
                        <select class="w3-select w3-border" name="admin_category_id">
                            <?php foreach ($categories as $category) { ?>
                                <option value="<?php echo $category->id; ?>" <?php echo ($category->id == $info->category_id) ? 'selected' : '' ?>><?php echo $category->name; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Kapasitas tiket</td>
                    <td><input type="text" class="w3-input w3-border" name="admin_capacities" value="<?php echo $capacities; ?>"></td>
                </tr>
            </table>
        </div>
        <br>
        <input type="submit" class="w3-btn <?php echo COLOR6; ?>" value="Sunting">
    </form>
</div>
