<?php $__env->startSection('title', 'Tambah Pemasok'); ?>
<?php $__env->startSection('breadcrumb', 'Pemasok / Tambah'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading"><a href="<?php echo e(route('suppliers.index')); ?>" class="back-link">← Kembali ke pemasok</a><span class="eyebrow accent-eyebrow">MITRA BISNIS</span><h1>Tambah pemasok <span class="heading-dot">.</span></h1><p>Masukkan informasi perusahaan dan narahubung pemasok.</p></div>
<section class="panel form-panel"><form method="POST" action="<?php echo e(route('suppliers.store')); ?>"><?php echo csrf_field(); ?> <?php echo $__env->make('suppliers.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><div class="form-actions"><a href="<?php echo e(route('suppliers.index')); ?>" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan pemasok <span>→</span></button></div></form></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\AJUNG\Downloads\GudangCerdas-UAS\GudangCerdas\resources\views/suppliers/create.blade.php ENDPATH**/ ?>