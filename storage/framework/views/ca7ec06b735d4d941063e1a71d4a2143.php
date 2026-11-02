<?php $__env->startSection('title', 'Tambah Produk'); ?>
<?php $__env->startSection('breadcrumb', 'Produk / Tambah'); ?>
<?php $__env->startSection('content'); ?>
<div class="page-heading"><a href="<?php echo e(route('products.index')); ?>" class="back-link">← Kembali ke produk</a><span class="eyebrow accent-eyebrow">INVENTARIS</span><h1>Tambah produk <span class="heading-dot">.</span></h1><p>Lengkapi informasi barang untuk mulai mengelola persediaan.</p></div>
<section class="panel form-panel"><form method="POST" action="<?php echo e(route('products.store')); ?>"><?php echo csrf_field(); ?> <?php echo $__env->make('products.form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><div class="form-actions"><a href="<?php echo e(route('products.index')); ?>" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan produk <span>→</span></button></div></form></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\AJUNG\Downloads\GudangCerdas-UAS\GudangCerdas\resources\views/products/create.blade.php ENDPATH**/ ?>