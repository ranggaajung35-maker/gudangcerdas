<?php $__env->startSection('title', 'Edit Transaksi'); ?>
<?php $__env->startSection('breadcrumb', 'Transaksi Stok / Edit'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading"><a href="<?php echo e(route('stock-movements.index')); ?>" class="back-link">← Kembali ke transaksi</a><span class="eyebrow accent-eyebrow">AKTIVITAS GUDANG</span><h1>Edit transaksi <span class="heading-dot">.</span></h1><p>Perubahan transaksi akan dihitung ulang terhadap saldo stok.</p></div>
<section class="panel form-panel"><form method="POST" action="<?php echo e(route('stock-movements.update', $movement)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?> <?php echo $__env->make('stock-movements.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><div class="form-actions"><a href="<?php echo e(route('stock-movements.index')); ?>" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan perubahan <span>→</span></button></div></form></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\AJUNG\Downloads\GudangCerdas-UAS\GudangCerdas\resources\views/stock-movements/edit.blade.php ENDPATH**/ ?>