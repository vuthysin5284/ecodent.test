$(document).ready(function () {
  const id = $("#business_id").val();
  $.getJSON("../api/v1/clinic/setting-general.php", { id: id })
    .done((resp) => {
      if (!resp.success) {
        return alert(resp.message || "Failed to load patient.");
      }
      const p = resp.data;
      $("#id").val(id);
      $("#name_en").val(p.name_en);
      $("#name_kh").val(p.name_kh);
      $("#slug").val(p.slug);
      $("#description").val(p.description);
      $("#phone").val(p.phone);
      $("#email").val(p.email);
      loadProvince(
        p.province_code,
        p.district_code,
        p.commune_code,
        p.village_code
      );
      $("#address").val(p.address);
    })
    .fail(() => alert("Server error fetching patient."));
});

$("#province").change(function () {
  loadDistrict(this.value);
});
$("#district").change(function () {
  loadCommune(this.value);
});
$("#commune").change(function () {
  loadVillage(this.value);
});

// 1) Load Provinces, then Districts → Communes → Villages
function loadProvince(selectedProv, selectedDist, selectedComm, selectedVill) {
  $.getJSON("../api/v1/clinic/address.php", { level: "province" }, (resp) => {
    if (!resp.success) return console.error(resp.message);
    const $prov = $("#province")
      .empty()
      .append('<option value="">Select Province</option>');
    resp.data.forEach((p) => {
      $prov.append(`<option value="${p.code}">${p.name_en}</option>`);
    });
    if (selectedProv) {
      $prov.val(selectedProv).trigger("change");
      // only proceed if we have a province
      loadDistrict(selectedProv, selectedDist, selectedComm, selectedVill);
    }
  });
  console.log(selectedProv, selectedDist, selectedComm, selectedVill);
}

function loadDistrict(provCode, selectedDist, selectedComm, selectedVill) {
  if (!provCode) {
    $("#district").html('<option value="">Select District</option>');
    return;
  }
  $.getJSON(
    "../api/v1/clinic/address.php",
    {
      level: "district",
      parent: provCode,
    },
    (resp) => {
      if (!resp.success) return console.error(resp.message);
      const $dist = $("#district")
        .empty()
        .append('<option value="">Select District</option>');
      resp.data.forEach((d) => {
        $dist.append(`<option value="${d.code}">${d.name_en}</option>`);
      });
      if (selectedDist) {
        $dist.val(selectedDist).trigger("change");
        loadCommune(selectedDist, selectedComm, selectedVill);
      }
    }
  );
}

function loadCommune(distCode, selectedComm, selectedVill) {
  if (!distCode) {
    $("#commune").html('<option value="">Select Commune</option>');
    return;
  }
  $.getJSON(
    "../api/v1/clinic/address.php",
    {
      level: "commune",
      parent: distCode,
    },
    (resp) => {
      if (!resp.success) return console.error(resp.message);
      const $comm = $("#commune")
        .empty()
        .append('<option value="">Select Commune</option>');
      resp.data.forEach((c) => {
        $comm.append(`<option value="${c.code}">${c.name_en}</option>`);
      });
      if (selectedComm) {
        $comm.val(selectedComm);
        loadVillage(selectedComm, selectedVill);
      }
    }
  );
}

function loadVillage(commCode, selectedVill) {
  if (!commCode) {
    $("#village").html('<option value="">Select Village</option>');
    return;
  }
  $.getJSON(
    "../api/v1/clinic/address.php",
    {
      level: "village",
      parent: commCode,
    },
    (resp) => {
      if (!resp.success) return console.error(resp.message);
      const $vill = $("#village")
        .empty()
        .append('<option value="">Select Village</option>');
      resp.data.forEach((v) => {
        $vill.append(`<option value="${v.code}">${v.name_en}</option>`);
      });
      if (selectedVill) {
        $vill.val(selectedVill);
      }
    }
  );
}

$("#formGeneralSetting").on("submit", function (e) {
  e.preventDefault();
  const payload = {
    id: $("#id").val(),
    name_en: $("#name_en").val(),
    name_kh: $("#name_kh").val(),
    slug: $("#slug").val(),
    phone: $("#phone").val(),
    email: $("#email").val(),
    province: $("#province").val(),
    district: $("#district").val(),
    commune: $("#commune").val(),
    village: $("#village").val(),
    address: $("#address").val(),
  };
  $.ajax({
    url: "../api/v1/clinic/setting-general.php",
    method: "PUT",
    contentType: "application/json",
    data: JSON.stringify(payload),
  })
    .done((resp) => {
      if (resp.success) {
        alert(resp.message || "General setting has been saved.");
      } else {
        alert(resp.message || "Update failed.");
      }
    })
    .fail(() => alert("Server error during update."));
});

// $(function () {
//   // 1) On Edit click, fetch data and show the form
//   $("#patientTable").on("click", ".btn-edit", function () {
//     // const id = $(this).data("id");
//     const id = 1;
//     $.getJSON("/api/v1/settings/general-settings.php", { id: id })
//       .done((resp) => {
//         if (!resp.success) {
//           return alert(resp.message || "Failed to load patient.");
//         }
//         const p = resp.data;
//         // Populate form fields
//         $("#name_en").val(p.id);
//         $("#name_kh").val(p.first_name);
//         $("#slug").val(p.last_name);
//         $("#phone").val(p.phone);
//         $("#email").val(p.email);
//         $("#province").val(p.province);
//         $("#district").val(p.district);
//         $("#commune").val(p.commune);
//         $("#village").val(p.village);
//         $("#address").val(p.address);
//         // Show modal
//         // $("#editPatientModal").modal("show");
//       })
//       .fail(() => alert("Server error fetching patient."));
//   });

//   // 2) On form submit, send PUT to update
//   // $("#editPatientForm").on("submit", function (e) {
//   //   e.preventDefault();
//   //   const payload = {
//   //     id: $("#patient-id").val(),
//   //     first_name: $("#first_name").val(),
//   //     last_name: $("#last_name").val(),
//   //     dob: $("#dob").val(),
//   //     phone: $("#phone").val(),
//   //     email: $("#email").val(),
//   //     address: $("#address").val(),
//   //   };

//   //   $.ajax({
//   //     url: "/api/v1/settings/general-setting.php",
//   //     method: "PUT",
//   //     contentType: "application/json",
//   //     data: JSON.stringify(payload),
//   //   })
//   //     .done((resp) => {
//   //       if (resp.success) {
//   //         $("#editPatientModal").modal("hide");
//   //         location.reload(); // or update the table row in‐place
//   //       } else {
//   //         alert(resp.message || "Update failed.");
//   //       }
//   //     })
//   //     .fail(() => alert("Server error during update."));
//   // });
// });
