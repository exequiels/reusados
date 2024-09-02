<?php
check_auth(['usuario', 'admin']);
require_once 'utils/user_ranks.php';
require_once 'utils/date_time_functions.php';
$allUsers = $userModel->getAllUsers();

sort($allUsers);
?>
<table class="table table-sm table-bordered border-estilo table-striped">
        <thead>
            <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
            <tr>
                <td class="p-3" colspan="4">
                <input class="w-100" type="text" id="myInput" onkeyup="myFunction()" placeholder="Filtrar usuarios por nickname, rol, etc ..">
                </td>
            </tr>
            <tr>
                <th class="p-3">Nickname</th>
                <th class="p-3">Level</th>
                <th class="p-3">Creado</th>
                <th class="p-3">Status</th>
            </tr>
        </thead>
        <tbody id="myTable">
        <?php if (!empty($allUsers)) : ?>
            <?php foreach ($allUsers as $user) : ?>
                <tr>
                    <td class="p-3">
                        <?= escape($user['username']); ?>
                    </td>
                    <td class="p-3">
                        <?= escape(nivelDeUsuario($user['rol'])); ?>
                    </td>
                    <td class="p-3">
                        <?= escape(convertToLocalTime($user['created_at'])); ?>
                    </td>
                    <td class="p-3">
                        <?= escape($user['status']) == 1 ? 'active' : 'inactive'; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td class="p-3" colspan="6">
                    No hay usuarios..
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<script>
      function myFunction() {
      // Declare variables 
      var input, filter, table, tr, td, i, occurrence;

      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("myTable");
      tr = table.getElementsByTagName("tr");

      // Loop through all table rows, and hide those who don't match the search query
     for (i = 0; i < tr.length; i++) {
         occurrence = false; // Only reset to false once per row.
         td = tr[i].getElementsByTagName("td");
         for(var j=0; j< td.length; j++){                
             currentTd = td[j];
             if (currentTd ) {
                 if (currentTd.innerHTML.toUpperCase().indexOf(filter) > -1) {
                     tr[i].style.display = "";
                     occurrence = true;
                 } 
             }
         }
         if(!occurrence){
             tr[i].style.display = "none";
         } 
     }
   }
</script>