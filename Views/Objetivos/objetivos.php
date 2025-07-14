<?php headerPublic($data);?>
<?php header_body_Public($data);?>


      <div id="content" role="main">
        <div class="layer-metas">
          <div class="side-list-metas">
            <div class="side-list-metas-title color-1"><span class="circle-meta">1</span> Lorem ipsum dolor sit consectetur 
            </div>

            <div id="accordion" class="accordion x-accordion">
              <div class="accordion-header">
                1.1 De aquí a 2030, erradicar para todas las personas y en todo el mundo la pobreza extrema (actualmente se considera que sufren pobreza extrema las personas que viven con menos de 1,25 dólares de los Estados Unidos al día)
                <svg class="arrow" viewBox="0 0 24 24"><path fill="#fff" d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
              </div>
              <div class="accordion-content">
                <div class="accordion-content_inner active" onclick="xsl.fn.openMeta(event, this, '#metaTest1')">
                  
                    1.1.1 Proporción de la población que vive por debajo del umbral internacional de pobreza, desglosada por sexo, edad, situación laboral y ubicación geográfica (urbana o rural)
                  
                </div>
              </div>
          
              <div class="accordion-header">
                1.2 De aquí a 2030, reducir al menos a la mitad la proporción de hombres, mujeres y niños de todas las edades que viven en la pobreza en todas sus dimensiones con arreglo a las definiciones nacionales
                <svg class="arrow" viewBox="0 0 24 24"><path fill="#fff" d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
              </div>
              <div class="accordion-content">
                <div class="accordion-content_inner" onclick="xsl.fn.openMeta(event, this, '#metaTest2')">

                  1.2.1 Proporción de la población que vive por debajo del umbral nacional de pobreza, desglosada por sexo y edad
           
                </div>
              </div>

              <div class="accordion-header">
                1.3 De aquí a 2030, reducir al menos a la mitad la proporción de hombres, mujeres y niños de todas las edades que viven en la pobreza en todas sus dimensiones con arreglo a las definiciones nacionales
                <svg class="arrow" viewBox="0 0 24 24"><path fill="#fff" d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
              </div>
              <div class="accordion-content">
                <div class="accordion-content_inner" onclick="xsl.fn.openMeta(event, this, '#metaTest3')">
              
                  1.2.1 Proporción de la población que vive por debajo del umbral nacional de pobreza, desglosada por sexo y edad
                
                </div>
              </div>
          
            </div>
          </div>
          <div class="content-metas">
            <div id="metaTest1" class="item-meta active">
              Lorem ipsum dolor sit amet consectetur, adipisicing elit. Fugit tempora alias, recusandae exercitationem soluta, et ipsa accusantium fuga minus placeat sapiente dolorem. Eligendi ut assumenda culpa enim doloremque libero incidunt.
            </div>
            <div id="metaTest2" class="item-meta">
              adipisicing elit. Fugit tempora alias, recusandae exercitationem soluta, et ipsa accusantium fuga minus placeat sapiente dolorem. Eligendi ut assumenda culpa enim doloremque libero incidunt.
            </div>
            <div id="metaTest3" class="item-meta">
              1234
            </div>
          </div>
        </div>
      </div>

<?php footerPublic($data);?>

<script>
  objRef = document.body;
  objRef.classList.remove('home');
  objRef.classList.add('app');

  objRef.setAttribute('data-x','tab');
</script>