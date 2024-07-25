<?php
check_auth(['usuario', 'admin']);
$allUsers = $userModel->getAllUsers();
?>
<table class="table table-sm table-bordered border-estilo table-striped">
        <thead>
            <?php include_once "views/layout/encabezado_de_tabla.php"; ?>
            <tr class="p-3">
                <td class="p-3" colspan="3">
                <input class="w-100" type="text" id="myInput" onkeyup="myFunction()" placeholder="Filtrar usuarios por nickname, rol, etc ..">
                </td>
            </tr>
            <tr class="p-3">
                <th class="p-3">Nickname</th>
                <th class="p-3">Rol</th>
                <th class="p-3">Creado</th>
            </tr>
        </thead>
        <tbody id="myTable">
        <?php if (!empty($allUsers)) : ?>
            <?php foreach ($allUsers as $user) : ?>
                <tr class="p-3">
                    <td class="p-3">
                        <?= htmlspecialchars($user['username']); ?>
                    </td>
                    <td class="p-3">
                        <?= htmlspecialchars($user['rol']); ?>
                    </td>
                    <td class="p-3">
                        <?= htmlspecialchars($user['created_at']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr class="p-3">
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