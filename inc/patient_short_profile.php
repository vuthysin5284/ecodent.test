<div class="row">
  <div class="col-6" >
    <div class="d-flex align-items-start align-items-sm-center gap-4">
      <?php
        echo '<img src="'.$image_path.'" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar"/>';
        echo '<div class="button-wrapper">';
        echo '<h4 class="body-text mb-2">'.$fname.'</h4>'.$gender.'&nbsp;&nbsp; - &nbsp;&nbsp;'.$age.' Years <br><i class="bx bxs-phone-call mb-2 mt-2"></i> '.$contact.'<br><i class="bx bxs-map"></i> '.$address;
        echo '</div>';
      ?>
    </div>
  </div>
  <div class="col-6" style='text-align:right'>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalReschedule">
      <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; Re-Schedule</span> <!-- its meaning re appointment-->
      <i class="bx bx bx-plus d-block d-sm-none"></i>
    </button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalVisitPlan">
      <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; Visit Plant</span> <!-- its meaning follow up by times set-->
      <i class="bx bx bx-plus d-block d-sm-none"></i>
    </button>
    <!-- follow up set time for patient should be come next -->
  </div>
</div>