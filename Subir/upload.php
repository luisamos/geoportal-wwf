<?php
$uploadDir = 'Assets/imagenes/';
$target = $uploadDir . 'portada_actual.jpg';

if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
  if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
    echo "✅ Imagen actualizada correctamente.";
  } else {
    echo "❌ Error al guardar la imagen.";
  }
} else {
  echo "⚠️ No se recibió ninguna imagen.";
}
?>