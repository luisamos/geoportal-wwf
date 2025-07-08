  </head>

  <body class="home" data-x="home">
      <div id="wrap">
          <header class="header" id="headerSite">
              <div class="container">
                  <div class="header-inner">
                      <div class="top-header flex between">
                          <div class="start-header align-center flex">
                              <div class="logo-wwf"><a href="<?= base_url() ?>home"><img
                                          src="<?= media(); ?>/public/img/wwf-logo.svg" alt=""></a></div>
                              <div class="slogan-wwf">GEOPORTAL WWF</div>
                              <div class="main-menu">
                                  <div class="item-nav"><a href="<?= base_url() ?>home">INICIO</a></div>
                                  <div class="item-nav"><a href="https://www.wwf.org.pe/">NOSOTROS </a></div>
                              </div>
                          </div>
                          <div class="end-header">
                              <div class="menu-mobile"
                                  onclick="xsl.fn.toggle({data: {'to':'.main-menu', 'class':'active'}})">
                                  <svg width="26" height="18" viewBox="0 0 19 13" fill="none"
                                      xmlns="http://www.w3.org/2000/svg">
                                      <path fill="#fff"
                                          d="M0.595215 0.185547H18.5952V2.18555H0.595215V0.185547ZM0.595215 5.18555H18.5952V7.18555H0.595215V5.18555ZM0.595215 10.1855H18.5952V12.1855H0.595215V10.1855Z"
                                          fill="black" />
                                  </svg>
                              </div>
                              <div class="language">
                                  <span onclick="toogleTranslate(event, this);">
                                      <svg width="35" height="35" viewBox="0 0 23 23" fill="none"
                                          xmlns="http://www.w3.org/2000/svg">
                                          <path
                                              d="M10.2402 0.883606H2.24023C1.14023 0.883606 0.240234 1.78361 0.240234 2.88361V14.8836L3.24023 11.8836H8.24023V10.8836C8.24023 8.68361 10.0302 6.88361 12.2402 6.88361V2.88361C12.2402 1.78361 11.3402 0.883606 10.2402 0.883606ZM10.2402 3.88361H8.74023C8.40023 5.07361 7.78023 6.18361 6.92023 7.14361L6.90023 7.16361L8.16023 8.41361L7.79023 9.42361L6.24023 7.88361L3.74023 10.3836L3.05023 9.65361L5.58023 7.16361C4.96023 6.47361 4.46023 5.70361 4.10023 4.88361H5.09023C5.40023 5.48361 5.78023 6.05361 6.24023 6.56361C6.96023 5.76361 7.48023 4.85361 7.81023 3.88361H2.24023V2.88361H5.74023V1.88361H6.74023V2.88361H10.2402V3.88361ZM20.2402 8.88361H12.2402C11.1402 8.88361 10.2402 9.78361 10.2402 10.8836V17.8836C10.2402 18.9836 11.1402 19.8836 12.2402 19.8836H19.2402L22.2402 22.8836V10.8836C22.2402 9.78361 21.3402 8.88361 20.2402 8.88361ZM18.8702 18.8836L18.0202 16.6336H14.4602L13.6202 18.8836H12.1202L15.4902 9.88361H16.9902L20.3702 18.8836H18.8702ZM16.2402 11.8836L17.4602 15.1336H15.0302L16.2402 11.8836Z"
                                              fill="#000" />
                                      </svg>
                                  </span>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </header>