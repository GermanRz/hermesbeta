  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
      <img src="vistas/img/logo.png" alt="Hermes Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Hermes</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="vistas/img/usuarios/michael.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $_SESSION["nombre"] . " " . $_SESSION["apellido"] ?></a>
          <a href="#" class="d-block"><?php echo $_SESSION["nombre_rol"] ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <!-- si el usuario tiene algun id_permiso entre 1 y 6 puede ver la opcion de administrar en el menu de lo contrario no -->
          <?php

          if (ControladorValidacion::validarPermisoSesion([19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30])) {
            echo '<li class="nav-item">
                      <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-cogs"></i>
                      <p>
                        Administrar
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">';

          if (ControladorValidacion::validarPermisoSesion([22])) {
                      echo '<li class="nav-item">
                        <a href="fichas" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Fichas</p>
                        </a>
                      </li>';}


          if (ControladorValidacion::validarPermisoSesion([23, 24, 25])) {
                      echo '<li class="nav-item">
                        <a href="sedes" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Sedes</p>
                        </a>
                      </li>';}

          if (ControladorValidacion::validarPermisoSesion([26, 27, 28])) {
                      echo '<li class="nav-item">
                        <a href="roles" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Roles</p>
                        </a>
                      </li>';} 

          if (ControladorValidacion::validarPermisoSesion([30])) {                       
                      echo'<li class="nav-item">
                      <a href="permisos" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Permisos</p>
                      </a>
                    </li>';}

          if (ControladorValidacion::validarPermisoSesion([29])) {    
                      echo'<li class="nav-item">
                        <a href="modulos" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Módulos</p>
                        </a>        
                      </li>' ;}                 
                      
                   echo '</ul>
                  </li>';
          }
          ?>



          <li class="nav-item">
            <a href="inicio" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Inicio
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Usuarios
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="usuarios" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Usuarios</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="permisos" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Permisos</p>
                </a>
              </li> -->
            </ul>


          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-laptop"></i>
              <p>
                Equipos
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="inventario" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inventario</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="recepcion" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Recepción</p>
                </a>
              </li>
            </ul>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-folder"></i>
              <p>
                Solicitudes
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="reservas" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reservas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="inmediatas" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inmediatas</p>
                </a>
              </li>
            </ul>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-check"></i>
              <p>
                Autorizar
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="autorizaciones" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Autorizaciones</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="vencidas" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Solicitudes vencidas</p>
                </a>
              </li>
            </ul>

          <li class="nav-item">
            <a href="devoluciones" class="nav-link">
              <i class="nav-icon fas fa-reply"></i>
              <span class="badge badge-info right">6+</span>
              <p>
                Devoluciones
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="salidas" class="nav-link">
              <i class="nav-icon fas fa-eye"></i>
              <span class="badge badge-info right">3+</span>
              <p>
                Salidas
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="reportes" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Reportes
              </p>
            </a>
          </li>


        </ul>
      </nav>
  </aside>