<?php $__env->startSection('title', 'Tambah Kategori'); ?>
<?php $__env->startSection('breadcrumb', 'Kategori / Tambah'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading"><a href="<?php echo e(route('categories.index')); ?>" class="back-link">← Kembali ke kategori</a><span class="eyebrow accent-eyebrow">MASTER DATA</span><h1>Tambah kategori <span class="heading-dot">.</span></h1><p>Buat kelompok baru untuk mengorganisasi produk.</p></div>
<section class="panel form-panel"><form method="POST" action="<?php echo e(route('categories.store')); ?>"><?php echo csrf_field(); ?> <?php echo $__env->make('categories.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><div class="form-actions"><a href="<?php echo e(route('categories.index')); ?>" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan kategori <span>→</span></button></div></form></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\AJUNG\Downloads\GudangCerdas-UAS\GudangCerdas\resources\views/categories/create.blade.php ENDPATH**/ ?>