<!-- ✅ Load jQuery FIRST -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- ✅ Then Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<!-- ✅ Then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<!-- ✅ Vendor & App Scripts -->
<script src="<?= base_url(); ?>assets/js/vendor.min.js"></script>
<script src="<?= base_url(); ?>assets/js/app.min.js"></script>

<!-- ✅ SweetAlert2 (single source, avoid conflicts) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ✅ Other Libraries -->
<script src="<?= base_url(); ?>assets/libs/moment/moment.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/jquery-scrollto/jquery.scrollTo.min.js"></script>

<!-- ❌ REMOVE / COMMENT THESE TO AVOID VERSION CONFLICTS
<script src="<?= base_url(); ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="<?= base_url(); ?>assets/js/sweetalert.min.js"></script>
-->

<!-- ✅ Emoji Picker -->
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>

<!-- ✅ DataTables -->
<script src="<?= base_url(); ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.buttons.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/jszip/jszip.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/buttons.html5.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/buttons.print.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.keyTable.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/datatables/dataTables.select.min.js"></script>
<script src="<?= base_url(); ?>assets/js/pages/datatables.init.js"></script>

<!-- ✅ Plugins -->
<script src="<?= base_url(); ?>assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/switchery/switchery.min.js"></script>
<script src="<?= base_url(); ?>assets/libs/select2/select2.min.js"></script>
<script src="<?= base_url(); ?>assets/js/pages/form-advanced.init.js"></script>
<script src="<?= base_url('assets/js/req-bell.js'); ?>"></script>

<script>
  window.APP = window.APP || {};
  APP.baseUrl = '<?= base_url(); ?>';
  APP.req = {
    count: '<?= base_url('request/ajax_pending_count'); ?>',
    list: '<?= base_url('request/ajax_pending_list'); ?>',
    markSeen: '<?= base_url('request/ajax_mark_seen'); ?>',
    index: '<?= base_url('request'); ?>'
  };
</script>

<!-- ✅ Bootstrap Dropdown Activation -->
<script>
  $(document).ready(function() {
    $('.dropdown-toggle').dropdown(); // Make sure dropdowns work
  });

  // (optional) keep your ensureSwalReady + ezToast if you still want them
  const ensureSwalReady = (() => {
    let loadingPromise = null;
    return () => {
      const hasSwal = typeof Swal !== 'undefined' && typeof Swal.fire === 'function';
      if (hasSwal) {
        return Promise.resolve(Swal);
      }
      if (loadingPromise) {
        return loadingPromise;
      }
      loadingPromise = new Promise((resolve) => {
        const cdn = document.createElement('script');
        cdn.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js';
        cdn.onload = () => resolve(typeof Swal !== 'undefined' ? Swal : null);
        cdn.onerror = () => resolve(null);
        document.head.appendChild(cdn);
      });
      return loadingPromise;
    };
  })();
  ensureSwalReady();

  window.ezToast = function(type, message) {
    return ensureSwalReady().then((swalLib) => {
      if (!swalLib || typeof swalLib.mixin !== 'function') {
        alert(message);
        return;
      }
      const toast = swalLib.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
      });
      toast.fire({
        icon: type,
        title: message
      });
    });
  };

  <?php $flashSuccess = $this->session->flashdata('success') ?? ''; ?>
  <?php $flashError   = $this->session->flashdata('error') ?? ''; ?>
  <?php if (!empty($flashSuccess)): ?>
    ezToast('success', <?= json_encode($flashSuccess); ?>);
  <?php endif; ?>
  <?php if (!empty($flashError)): ?>
    ezToast('error', <?= json_encode($flashError); ?>);
  <?php endif; ?>
</script>