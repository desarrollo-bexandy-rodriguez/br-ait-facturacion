<h3><?php _e("Datos del Cliente para Facturas", "blank"); ?></h3>

      <table class="form-table">
      <tr>
          <th><label for="razonsocial"><?php _e("Razón Social"); ?></label></th>
          <td>
              <input type="text" name="razonsocial" id="razonsocial" value="<?php echo esc_attr( get_the_author_meta( 'razonsocial', $user->ID ) ); ?>" class="regular-text" /><br />
              <span class="description"><?php _e("Por favor escriba su nombre ó razón social ."); ?></span>
          </td>
      </tr>
    
    <tr>
          <th><label for="cifnit"><?php _e("CIF ó NIT"); ?></label></th>
          <td>
              <input type="text" name="cifnit" id="cifnit" value="<?php echo esc_attr( get_the_author_meta( 'cifnit', $user->ID ) ); ?>" class="regular-text" /><br />
              <span class="description"><?php _e("Por favor escriba su CIF ó NIT."); ?></span>
          </td>
      </tr>

      <tr>
          <th><label for="direccion"><?php _e("Direccion"); ?></label></th>
          <td>
              <input type="text" name="direccion" id="direccion" value="<?php echo esc_attr( get_the_author_meta( 'direccion', $user->ID ) ); ?>" class="regular-text" /><br />
              <span class="description"><?php _e("Por favor escriba su direccion."); ?></span>
          </td>
      </tr>

      <tr>
      <th><label for="codigopostal"><?php _e("Código Postal"); ?></label></th>
          <td>
              <input type="text" name="codigopostal" id="codigopostal" value="<?php echo esc_attr( get_the_author_meta( 'codigopostal', $user->ID ) ); ?>" class="regular-text" /><br />
              <span class="description"><?php _e("Por favor escriba su código postal."); ?></span>
          </td>
      </tr>

      <tr>
          <th><label for="provincia"><?php _e("Provincia"); ?></label></th>
          <td>
              <input type="text" name="provincia" id="provincia" value="<?php echo esc_attr( get_the_author_meta( 'provincia', $user->ID ) ); ?>" class="regular-text" /><br />
              <span class="description"><?php _e("Please enter your provincia."); ?></span>
          </td>
      </tr>
      
      <tr>
          <th><label for="pais"><?php _e("País"); ?></label></th>
          <td>
              <input type="text" name="pais" id="pais" value="<?php echo esc_attr( get_the_author_meta( 'pais', $user->ID ) ); ?>" class="regular-text" /><br />
              <span class="description"><?php _e("Please enter your país."); ?></span>
          </td>
      </tr>

      </table>