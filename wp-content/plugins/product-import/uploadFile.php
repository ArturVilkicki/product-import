<?php

// Upload file
if(isset($_POST['but_submit'])){

  if($_FILES['file']['name'] != ''){
    $file_name = $_FILES['file']['name'];
    $file_temp = $_FILES['file']['tmp_name'];
    $upload_dir = wp_upload_dir();

    $file_data = file_get_contents( $file_temp );
    $filename = basename( $file_name );
    $filetype = wp_check_filetype($file_name);

    $filename = time().'.xml';

    if ( wp_mkdir_p( $upload_dir['path'] ) ) {
                  $file = $upload_dir['path'] . '/' . $filename;
    }
    else {
      $file = $upload_dir['basedir'] . '/' . $filename;
    }


    file_put_contents( $file, $file_data );

    $xml=simplexml_load_file($file);

    $xml = json_decode(json_encode($xml), true);

    $products = $xml['Product'];
    $count = count($products);

    if($count <=500) {
      foreach ($products as $key => $product) {

          $post_id = post_exists($product['Name']);

          if ($post_id>0) {
            update_post_meta( $post_id, '_price',  $product['Retail_Price']);
            update_post_meta( $post_id, '_featured', 'yes' );
            update_post_meta( $post_id, '_stock', $product['Inventory_Count'] );
            update_post_meta( $post_id, '_stock_status', 'instock');
          }else {
            $post_id = wp_insert_post( array(
            'post_title' => $product['Name'],
            'post_content' => $product['Description'],
            'post_status' => 'publish',
            'post_type' => "product",
            ) );
            wp_set_object_terms( $post_id, 'simple', 'product_type' );

            update_post_meta( $post_id, '_price',  $product['Retail_Price']);
            update_post_meta( $post_id, '_featured', 'yes' );
            update_post_meta( $post_id, '_stock', $product['Inventory_Count'] );
            update_post_meta( $post_id, '_stock_status', 'instock');
          }
      }

      echo 'Your file is imported successfully';
    } else {
      echo "Your document have a lot of items";
    }


  }

}

?>

<h1>Upload XML File</h1>

<!-- Form -->
<form method='post' action='' name='myform' enctype='multipart/form-data'>
  <table>
    <tr>
      <td>Upload file</td>
      <td><input type='file' name='file' accept="text/xml"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type='submit' name='but_submit' value='Submit'></td>
    </tr>
  </table>
</form>
