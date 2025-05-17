  <?php 
    $cid = $_GET['cid'];
    $pgid = $_GET['pgid']; 
    $apid = $_GET['apid']; 
  ?>
  <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo justify-content-center">
      <a href="index.php" class="app-brand-link">
        <span class="app-brand-logo demo"><img src="../assets/img/icons/brands/icon.png" height="50"></span>
      </a>
      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
      </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <div class="menu-inner-shadow"></div>
          <ul class="menu-inner py-1">
            <?php
              $result = mysqli_query($CON, "
                  SELECT * FROM sidebar_menus 
                  WHERE (parent_id IS NULL OR parent_id = 0) AND is_active = 1 
                  ORDER BY id ASC, label_group ASC, sort_order ASC
              ");
              $grouped = [];

              while ($row = mysqli_fetch_assoc($result)) {
                $group = $row['label_group'] ?: '';

                if (!isset($grouped[$group])) {
                    $grouped[$group] = [];
                }

                if ($row['is_dropdown'] == 1) {
                    $children = [];
                    $child_result = mysqli_query($CON, "SELECT * FROM sidebar_menus WHERE parent_id = {$row['id']} AND is_active = 1 ORDER BY sort_order ASC");
                    while ($child = mysqli_fetch_assoc($child_result)) {
                        $children[] = $child;
                    }
                    $row['children'] = $children;
                }

                $grouped[$group][] = $row;
              }

              foreach ($grouped as $group => $items) {
                echo '<li class="menu-header small text-uppercase"><span class="menu-header-text">' . htmlspecialchars($group) . '</span></li>';
                foreach ($items as $item) {
                  if (isset($item['children'])) {
                    echo '<li class="menu-item" id="'.$item['title'].'">';
                    echo '<a href="javascript:void(0);" class="menu-link menu-toggle">';
                    echo '<i class="menu-icon tf-icons ' . htmlspecialchars($item['icon']) . '"></i>';
                    echo '<div data-i18n="' . htmlspecialchars($item['title']) . '">' . htmlspecialchars($item['title']) . '</div>';
                    echo '</a>';
                    echo '<ul class="menu-sub">';
                    foreach ($item['children'] as $child) {
                      echo '<li class="menu-item" id="sub-'.$child['title'].'">';
                      echo '<a href="../' . htmlspecialchars($child['url']) . '" class="menu-link">';
                      echo '<div data-i18n="' . htmlspecialchars($child['title']) . '">' . htmlspecialchars($child['title']) . '</div>';
                      echo '</a>';
                      echo '</li>';
                    }
                    echo '</ul>';
                    echo '</li>';
                  } else {
                    echo '<li class="menu-item" id="'.$item['title'].'">';
                    echo '<a href="../' . htmlspecialchars($item['url']) . '" class="menu-link">';
                    echo '<i class="menu-icon tf-icons ' . htmlspecialchars($item['icon']) . '"></i>';
                    echo '<div data-i18n="' . htmlspecialchars($item['title']) . '">' . htmlspecialchars($item['title']) . '</div>';
                    echo '</a>';
                    echo '</li>';
                  }
                }
              }

            ?>
          </ul>
    
  </aside>