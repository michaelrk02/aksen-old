<script src="<?php echo base_url('public/Chart.min.js'); ?>"></script>
<div class="w3-row">
    <div class="w3-container w3-col w3-half w3-mobile">
        <h3>Informasi Umum:</h3>
        <div class="w3-responsive">
            <table class="w3-table-all w3-white">
                <tr class="<?php echo COLOR2; ?>">
                    <th>Spesifikasi</th>
                    <th>Keterangan</th>
                    <th class="w3-center">Tindakan</th>
                </tr>
                <tr>
                    <td>Berita</td>
                    <td><?php echo $info->highlight; ?></td>
                    <td class="w3-center"><a class="w3-btn w3-blue" href="<?php echo site_url('admin/settings'); ?>">Ubah</a></td>
                </tr>
                <tr>
                    <td>ID pesanan selanjutnya</td>
                    <td><?php echo $info->order_id_next; ?></td>
                    <td class="w3-center">-</td>
                </tr>
                <tr>
                    <td>Waktu terakhir checking</td>
                    <td><?php echo date('r', $info->last_check_timestamp); ?></td>
                    <td class="w3-center"><a class="w3-btn w3-blue" href="<?php echo site_url('admin/settings'); ?>">Ubah</a></td>
                </tr>
                <tr>
                    <td>Kategori tiket</td>
                    <td><?php echo $categories[$info->category_id]['name']; ?></td>
                    <td class="w3-center"><a class="w3-btn w3-blue" href="<?php echo site_url('admin/settings'); ?>">Ubah</a></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="w3-container w3-col w3-half w3-mobile">
        <h3>Daftar Kategori Tiket</h3>
        <div class="w3-responsive">
            <table class="w3-table-all w3-white">
                <tr class="<?php echo COLOR2; ?>">
                    <th>ID</th>
                    <th>Jenis</th>
                    <th>Harga</th>
                    <th>Kapasitas</th>
                    <th>Tersedia</th>
                    <th>Terjual</th>
                    <th>Pendapatan</th>
                </tr>
                <?php foreach ($categories as $category) { ?>
                    <tr class="<?php echo ($category['id'] == $info->category_id) ? 'w3-pale-yellow' : ''; ?>">
                        <td><?php echo $category['id']; ?></td>
                        <td><?php echo $category['name']; ?></td>
                        <td><?php echo rupiah($category['price']); ?></td>
                        <td><?php echo $category['capacity']; ?></td>
                        <td><?php echo $category['available']; ?></td>
                        <td><?php echo $category['capacity'] - $category['available']; ?> (<?php echo round(($category['capacity'] - $category['available']) / ($category['capacity'] == 0 ? 1 : $category['capacity']) * 100, 2); ?>%)</td>
                        <td><?php echo rupiah(($category['capacity'] - $category['available']) * $category['price']); ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="3"><b>TOTAL:</b></td>
                    <td><b><?php echo $total_capacity; ?></b></td>
                    <td><b><?php echo $total_available; ?></b></td>
                    <td><b><?php echo $total_sold; ?> (<?php echo round($total_sold / ($total_capacity == 0 ? 1 : $total_capacity) * 100, 2); ?>)%</b></td>
                    <td><b><?php echo rupiah($total_revenue); ?></b></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<br>
<div class="w3-row">
    <div class="w3-container w3-col w3-mobile">
        <canvas id="categories-chart"></canvas>
    </div>
</div>
<br>

<script>

window.addEventListener('load', function() {
    var chartLabels = [];
    var targetDataset = [];
    var targetBGColors = [];
    var targetBDColors = [];
    var currentDataset = [];
    var currentBGColors = [];
    var currentBDColors = [];

    <?php foreach ($categories as $category) { ?>
        chartLabels.push('<?php echo $category['name']; ?>');
        targetDataset.push('<?php echo $category['capacity']; ?>');
        targetBGColors.push('rgba(160, 255, 160, 0.2)');
        targetBDColors.push('rgba(160, 255, 160, 1.0)');
        currentDataset.push('<?php echo $category['capacity'] - $category['available']; ?>');
        currentBGColors.push('rgba(160, 160, 255, 0.2)');
        currentBDColors.push('rgba(160, 160, 255, 1.0)');
    <?php } ?>

    var categoriesChartCanvas = document.querySelector('#categories-chart');
    var categoriesChart = new Chart(categoriesChartCanvas.getContext('2d'), {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Target',
                    data: targetDataset,
                    backgroundColor: targetBGColors,
                    borderColor: targetBDColors
                },
                {
                    label: 'Capaian',
                    data: currentDataset,
                    backgroundColor: currentBGColors,
                    borderColor: currentBDColors
                }
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
});

</script>
