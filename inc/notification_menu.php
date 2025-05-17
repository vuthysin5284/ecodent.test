<div class="mb-4">
  <div id="notification-menu" class="row">
    <div class="btn-group" role="group" aria-label="First group">
      <a id="notification-appointment" type="button" class="btn btn-outline-secondary" href="../page/notification_appointment.php?pgid=2">
        <span class="d-sm-none d-none d-md-block my-auto">Appointment</span>
        <i class="bx bx-calendar d-sm-block d-block d-md-none my-auto"></i>
        <?php //echo '<div class="badge bg-warning rounded-pill ms-auto my-auto text-white">'.$NOTI_APPO['n'].'</div>'; ?>
      </a>
      <a id="notification-queue" type="button" class="btn btn-outline-secondary" href="../page/notification_queue.php?pgid=3">
        <span class="d-sm-none d-none d-md-block my-auto">Queue</span>
        <i class="bx bx-calendar-check d-sm-block d-block d-md-none my-auto"></i>
        <?php //echo '<div class="badge bg-primary rounded-pill ms-auto my-auto text-white">'.$NOTI_QUEU['n'].'</div>'; ?>
      </a>
      <a id="notification-serving" type="button" class="btn btn-outline-secondary" href="../page/notification_serving.php?pgid=4">
        <span class="d-sm-none d-none d-md-block my-auto">Serving</span>
        <i class="bx bx-calendar-heart d-sm-block d-block d-md-none my-auto"></i>
        <?php //echo '<div class="badge bg-success rounded-pill ms-auto my-auto text-white">'.$NOTI_SERV['n'].'</div>'; ?>
      </a>
    </div>
  </div>
</div>