<div class="mb-4">
  <div id="invoice-menu" class="row">
    <div class="btn-group" role="group" aria-label="First group">
      <a id="invoice-draft" type="button" class="btn btn-outline-secondary" href="../page/invoice_draft.php?pgid=9">
        <span class="d-sm-none d-none d-md-block my-auto">QUOTE</span>
        <i class="bx bx-receipt d-sm-block d-block d-md-none my-auto"></i>
        <?php //echo '<div class="badge bg-warning rounded-pill ms-auto my-auto text-white">'.$NOTI_DRAFT['n'].'</div>'; ?>
      </a>
      <a id="invoice-pending" type="button" class="btn btn-outline-secondary" href="../page/invoice_pending.php?pgid=10">
        <span class="d-sm-none d-none d-md-block my-auto">Pending</span>
        <i class="bx bx-credit-card d-sm-block d-block d-md-none my-auto"></i>
        <?php //echo '<div class="badge bg-primary rounded-pill ms-auto my-auto text-white">'.$NOTI_PENDING['n'].'</div>'; ?>
      </a>
      <a id="invoice-complete" type="button" class="btn btn-outline-secondary" href="../page/invoice_completed.php?pgid=11">
        <span class="d-sm-none d-none d-md-block my-auto">Completed</span>
        <i class="bx bx-wallet d-sm-block d-block d-md-none my-auto"></i>
        <?php //echo '<div class="badge bg-success rounded-pill ms-auto my-auto text-white">'.$NOTI_COMPLETE['n'].'</div>'; ?>
      </a>
    </div>
  </div>
</div>