<script type='text/javascript'>
    var gmpExistsGroups = JSON.parse('<?php echo utilsGmp::jsonEncode($this->groupsList)?>');
</script>
<table class="gmpTable" id="GmpTableGroups">
      <thead>
          <tr>
              <th><?php langGmp::_e("ID");?>    </th>
              <th><?php langGmp::_e("Title");?> </th>
              <th>
                  <?php langGmp::_e("Description");?>
              </th>
              <th class='thOperations'>
                  <?php langGmp::_e("Operations");?>
              </th>
          </tr>
      </thead>
      <tbody>
          <?php
              foreach($this->groupsList as $group){
                  ?>
          <tr id='groupRow_<?php echo $group['id']?>'>
              <td>
                  <?php echo $group['id']?>
              </td>
              <td>
                  <?php echo $group['title'];?>
              </td>
              <td>
                  <?php echo $group['description'];?>
              </td>
              <td>
                  <a class='btn btn-warning gmpEditBtn gmpListActBtn' id='<?php echo $group['id']?>'
                      onClick='gmpEditGroupItem(<?php echo $group['id']?>)'>
                      <span class='gmpIcon gmpIconEdit '></span>
                      <?php langGmp::_e("Edit")?></a>
                  <a class='btn btn-danger gmpRemoveBtn gmpListActBtn' id='<?php echo $group['id']?>'
                     onclick="gmpRemoveGroupItem(<?php echo $group['id']?>)">
                    <span class='gmpIcon gmpIconRemove '></span>
                      <?php langGmp::_e("Remove")?>
                  </a>
                  <span id="gmpGroupListTableLoader_<?php echo $group['id'];?>"></span>
              </td>

          </tr> 

                  <?php
              }
          ?>
      </tbody>
  </table>   